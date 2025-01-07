<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Report;
use App\Models\Question;
use App\Models\PdfTemplate;
use Illuminate\Support\Str;
use App\Models\ClientAnswer;
use Illuminate\Http\Request;
use App\Models\QuesitonCategory;
use App\Http\Controllers\PdfController;

class ReportController extends Controller
{
    protected $pdfController;

    public function __construct(PdfController $pdfController)
    {
        $this->pdfController = $pdfController;
    }

    public function makeReport()
    {
        $clients = Client::all();
        $questions = Question::all();
        $pdfs = PdfTemplate::all();
        $categories = QuesitonCategory::with('questions')->get();
        return view('backend.report.add_report', compact('clients', 'questions', 'categories', 'pdfs'));
    }

    public function checkReport(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'pdf_template_id' => 'required|exists:pdf_templates,id',
        ]);

        $pdfTemplate = PdfTemplate::find($data['pdf_template_id']);
        if ($pdfTemplate && $pdfTemplate->version !== 'v1') {
            return response()->json([
                'exists' => true,
                'error' => true,
                'message' => 'This PDF template is not compatible. Please select a V1 template.',
            ]);
        }

        $existingReport = Report::where('client_id', $data['client_id'])->first();

        if ($existingReport) {
            return response()->json([
                'exists' => true,
                'error' => false,
                'message' => 'A report for this client already exists. Do you want to proceed and generate a new report?',
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function saveReport(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'pdf_template_id' => 'required|exists:pdf_templates,id',
            'questions' => 'required|array',
            'questions.*.result' => 'required|in:good,average,poor',
            'questions.*.value' => 'required|numeric',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image_url')) {
            $imageFolder = 'images';
            $destinationPath = public_path($imageFolder);

            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $filename = Str::uuid() . '.' . $request->file('image_url')->getClientOriginalExtension();
            $request->file('image_url')->move($destinationPath, $filename);
            $imagePath = $imageFolder . '/' . $filename;
        }

        // Prepare the client answer array
        $categorizedQuestions = [];
        foreach ($data['questions'] as $questionId => $questionData) {
            // Create the ClientAnswer entry
            $clientAnswer = ClientAnswer::create([
                'client_id' => $data['client_id'],
                'question_id' => $questionId,
                'value' => $questionData['value'],
                'result' => $questionData['result'],
            ]);

            // Fetch the question text for each client answer
            $question = Question::with('category')->find($questionId);
            if ($question && $question->category) {
                $categoryName = $question->category->name;
                $categorizedQuestions[$categoryName][] = [
                    'id' => $clientAnswer->id,
                    'question_id' => $clientAnswer->question_id,
                    'question' => $question->text,
                    'value' => $clientAnswer->value,
                    'result' => $clientAnswer->result,
                ];
            }
        }

        // Create the report entry
        $client = Client::find($data['client_id']);
        $report = Report::create([
            'client_id' => $data['client_id'],
            'pdf_template_id' => $data['pdf_template_id'],
            'file_path' => null,
            'score' => $request->input('website_score'),
            'website_image' => $imagePath,
            'generated_at' => now(),
        ]);

        // Add the report data to the array
        $clientData = [
            'id' => $report->id,
            'client' => $client ? $client->name : null,
            'designition' => $client ? $client->designation : null,
            'website' => $client ? $client->website : null,
            'date' => $client ? $client->date : null,
            'score' => $report->score,
            'website_image' => $report->website_image,
            'pdf_template_id' =>  $data['pdf_template_id'],
        ];
        $response =   $this->pdfController->editPDF($categorizedQuestions, $clientData);
        // return $response;
        $pdfpath = $response->getData()->path;
        $report->update(['file_path' => $pdfpath]);
        // return redirect()->route('getReportsList')->with('success', 'Report saved successfully for ' . $client->name . '.');
        return response()->json(['success' => 'Report saved successfully for <b>' . $client->name . '</b>.']);
        //   echo 'response: <pre>' .print_r($response,true). '</pre>'; die;

    }

    public function getLatestClientReports(Request $request)
    {
        $query = Report::with('client')->whereIn(
            'id',
            Report::selectRaw('MAX(id) AS id')
                ->whereNotNull('client_id')
                ->groupBy('client_id')
                ->pluck('id')
        )->orderBy('created_at', 'desc');

        // Apply filter by client name if provided
        if ($request->filled('name')) {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('name') . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $reports = $query->paginate(25);
        $clients = Client::all();
        if ($request->ajax()) {
            return response()->json(['reports' => $reports, 'clients' => $clients]);
        }

        return view('backend.report.latest_reports', compact('reports', 'clients'));
    }

    public function downloadPDF($file_path)
    {
        $file = public_path('generated/' . $file_path);

        if (file_exists($file)) {
            return response()->download($file);
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }
}
