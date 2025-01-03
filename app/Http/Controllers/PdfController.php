<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Factor;
use App\Models\Report;
use setasign\Fpdi\Fpdi;
use App\Models\Question;
use App\Models\PdfTemplate;
use Illuminate\Support\Str;
use App\Models\ClientAnswer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\QuesitonCategory;
use Illuminate\Support\Facades\URL;


class PdfController extends Controller
{
    public function generatePDF()
    {
        $data = ['name' => 'John Doe', 'type' => 'Example Type', 'image' => 'path/to/image.jpg', 'website' => 'https://example.com', 'score' => 85];
        $pdf = Pdf::loadView('backend.template.v1.pdf_template', $data);
        return $pdf->download('document.pdf');
    }

    public function editPDF($questionArray , $clientInfo)
    {
        $pdf_template = PdfTemplate::find(1);
        $pathToPdf = public_path('pdf/' . $pdf_template->name);
        if (!file_exists($pathToPdf)) {
            // Debugging line, file not found
            dd("Template file does not exist: " . $pathToPdf);
        }

        $pdf = new FPDI(); // Set the source file
        $pageCount = $pdf->setSourceFile($pathToPdf);
        if ($pageCount <= 0) {
            dd("No pages found in the template.");
        }
        $data = [
            1 => [
                'name' => $clientInfo['client'],
                'x' => 11,
                'y' => 139.8,

                'type' => $clientInfo['designition'],
                'type_x' => 10.5,
                'type_y' => 150,

                'date' =>  $clientInfo['date'],
                'date_x' => 40,
                'date_y' => 249,

                'website' => $clientInfo['website'],
                'website_x' => 49,
                'website_y' => 261
            ],

            2 => [
                'name_2' => $clientInfo['client'],
                'x' => 62.5,
                'y' => 39.5,

                'type_2' => $clientInfo['designition'],
                'type2_x' => 45,
                'type2_y' => 54,

                'score' => $clientInfo['score'].'/100',
                'score_x' => 116,
                'score_y' => 222,

                'image' => $clientInfo['website_image'],
                'img_x' => 37.8,
                'img_y' => 129,
                'img_w' => 141,
                'img_h' => 80
            ],
            // Add more pages as needed
        ];
        $questionPage = 3;
        $startX = 21;
        $startvalueX = 153;
        $resultvalueX = 178;
        $startY = 102;
        $lineHeight = 15;

        foreach ($questionArray as $category => $questions) {
            if ($category === 'Web Presence') {
                foreach ($questions as $index => $question) {
                    if ($question['result'] == 'good') {
                        $color = [0, 128, 0]; // Green (RGB)
                    } elseif ($question['result'] == 'average') {
                        $color = [255, 255, 0]; // Yellow (RGB)
                    } else {
                        $color = [255, 0, 0]; // Red (RGB)
                    }
                    $data[$questionPage][] = [
                        'text' => $question['question'],
                        'x' => $startX,
                        'y' => $startY + ($index) * $lineHeight,

                        'value' => $question['value'],
                        'value_x' => $startvalueX,
                        'value_y' => $startY + ($index) * $lineHeight,

                        'result' => ucfirst($question['result']), // The result (e.g., good, average, poor)
                        'result_x' => $resultvalueX,
                        'result_y' => $startY + ($index) * $lineHeight,

                        'color' => $color,
                        'color_x' => $resultvalueX,
                        'color_y' => $startY + ($index) * $lineHeight,
                        'color_w' => 20,
                        'color_h' => 20
                    ];
                }
            }
        }

        $questionPage = 4;
        $startX = 21;
        $startvalueX = 153;
        $resultvalueX = 178;
        $startY = 53;
        $lineHeight = 15;
        foreach ($questionArray as $category => $questions) {
            if ($category === 'SEO') {
                foreach ($questions as $index => $question) {
                    if ($question['result'] == 'good') {
                        $color = [0, 128, 0]; // Green (RGB)
                    } elseif ($question['result'] == 'average') {
                        $color = [255, 255, 0]; // Yellow (RGB)
                    } else {
                        $color = [255, 0, 0]; // Red (RGB)
                    }
                    $data[$questionPage][] = [
                        'text' => $question['question'],
                        'x' => $startX,
                        'y' => $startY + ($index) * $lineHeight,

                        'value' => $question['value'],
                        'value_x' => $startvalueX,
                        'value_y' => $startY + ($index) * $lineHeight,

                        'result' => $question['result'], // The result (e.g., good, average, poor)
                        'result_x' => $resultvalueX,
                        'result_y' => $startY + ($index) * $lineHeight,

                        'color' => $color,
                        'color_x' => $resultvalueX,
                        'color_y' => $startY + ($index) * $lineHeight,
                        'color_w' => 20,
                        'color_h' => 20
                    ];
                }
            }
        }

        $questionPage = 4;
        $startX = 21;
        $startvalueX = 153;
        $resultvalueX = 178;
        $startY = 176;
        $lineHeight = 15;
        $additionalLineHeight = 5;
        foreach ($questionArray as $category => $questions) {
            if ($category === 'Site Content') {
                foreach ($questions as $index => $question) {

                    if ($question['result'] == 'good') {
                        $color = [0, 128, 0]; // Green (RGB)
                    } elseif ($question['result'] == 'average') {
                        $color = [255, 255, 0]; // Yellow (RGB)
                    } else {
                        $color = [255, 0, 0]; // Red (RGB)
                    }
                    $value_y = $startY + ($index) * $lineHeight;
                    $result_y = $value_y;

                    // Check for specific phrases to break lines
                    if (strpos($question['question'], 'colors, good') !== false) {
                        [$firstLine, $secondLine] = explode('colors, good', $question['question'], 2);

                        $data[$questionPage][] = [
                            'text' => $firstLine . 'colors, good',
                            'x' => $startX,
                            'y' => $startY + ($index) * $lineHeight,
                        ];

                        $data[$questionPage][] = [
                            'text' => trim($secondLine),
                            'x' => $startX,
                            'y' => $startY + ($index) * $lineHeight + $additionalLineHeight,
                        ];
                    } elseif (strpos($question['question'], 'contact form') !== false) {
                        [$firstLine, $secondLine] = explode('contact form', $question['question'], 2);

                        $data[$questionPage][] = [
                            'text' => $firstLine . 'contact form',
                            'x' => $startX,
                            'y' => $startY + ($index) * $lineHeight,
                        ];

                        $data[$questionPage][] = [
                            'text' => trim($secondLine),
                            'x' => $startX,
                            'y' => $startY + ($index) * $lineHeight + $additionalLineHeight,
                        ];
                    } else {
                        $data[$questionPage][] = [
                            'text' => $question['question'],
                            'x' => $startX,
                            'y' => $startY + ($index) * $lineHeight,
                        ];
                    }

                    // Add the value and result aligned with the first line
                    $data[$questionPage][] = [
                        'value' => $question['value'],
                        'value_x' => $startvalueX,
                        'value_y' => $value_y,

                        'result' => $question['result'],
                        'result_x' => $resultvalueX,
                        'result_y' => $result_y,
                        'color' => $color,

                        'color_x' => $resultvalueX,
                        'color_y' => $startY + ($index) * $lineHeight,
                        'color_w' => 20,
                        'color_h' => 20
                    ];
                }
            }
        }

        // Loop through all pages
        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $pdf->importPage($i);
            $pdf->AddPage();
            $pdf->useTemplate($templateId); // Check if there is any text to add on this page
            if (isset($data[$i])) {


                   // Check and add name
                if (isset($data[$i]['name'])) {
                    $pdf->SetFont('helvetica', 'B', 22);
                    $pdf->SetTextColor(255, 255, 255); // White text for visibility
                    $pdf->SetXY($data[$i]['x'], $data[$i]['y']);
                    $pdf->Write(0, $data[$i]['name']);
                }

                // Check and add type
                if (isset($data[$i]['type'])) {
                    $pdf->SetFont('helvetica', 'B', 22.5);
                    $pdf->SetTextColor(255, 255, 255); // White text for visibility
                    $pdf->SetXY($data[$i]['type_x'], $data[$i]['type_y']);
                    $pdf->Write(0, $data[$i]['type']);
                }

                // Add date
                if (isset($data[$i]['date'])) {
                    $pdf->SetFont('helvetica', '', 16);
                    $pdf->SetXY($data[$i]['date_x'], $data[$i]['date_y']);
                    $pdf->Write(0, $data[$i]['date']);
                }


                 // Add website
                if (isset($data[$i]['website'])) {
                    $pdf->SetFont('helvetica', '', 16);
                    $pdf->SetXY($data[$i]['website_x'], $data[$i]['website_y']);
                    $pdf->Write(0, $data[$i]['website']);
                }

                // Add second name
                if (isset($data[$i]['name_2'])) {
                    $pdf->SetFont('helvetica', 'B', 33);
                    $pdf->SetTextColor(0, 0, 0); // Black text for visibility
                    $pdf->SetXY($data[$i]['x'], $data[$i]['y']);
                    $pdf->Write(0, $data[$i]['name_2']);
                }

                // Add second type
                if (isset($data[$i]['type_2'])) {
                    $pdf->SetFont('helvetica', 'B', 33);
                    $pdf->SetTextColor(0, 0, 0); // Black text for visibility
                    $pdf->SetXY($data[$i]['type2_x'], $data[$i]['type2_y']);
                    $pdf->Write(0, $data[$i]['type_2']);
                }

                // Add score
                if (isset($data[$i]['score'])) {
                    $pdf->SetFont('helvetica', 'B', 18);
                    $pdf->SetTextColor(0, 0, 0); // Black text for visibility
                    $pdf->SetXY($data[$i]['score_x'], $data[$i]['score_y']);
                    $pdf->Write(0, $data[$i]['score']);
                }

                // Add image
                if (isset($data[$i]['image'])) {
                    if (file_exists(public_path($data[$i]['image']))) {
                        $pdf->Image(
                            public_path($data[$i]['image']),
                            $data[$i]['img_x'],
                            $data[$i]['img_y'],
                            $data[$i]['img_w'],
                            $data[$i]['img_h']
                        );
                    } else {
                        dd("Image file does not exist: " . public_path($data[$i]['image']));
                    }
                }

                foreach ($data[$i] as $item) {
                    // Add text for each question
                    if (isset($item['text'])) {
                        $pdf->SetFont('helvetica', '', 12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY($item['x'], $item['y']);
                        $pdf->Write(0, $item['text']);
                    }

                    if (isset($item['value'])) {
                        $pdf->SetFont('helvetica', '', 12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY($item['value_x'], $item['value_y']);
                        $pdf->Write(0, $item['value']);
                    }

                    if (isset($item['result'])) {
                        $pdf->SetFillColor($item['color'][0], $item['color'][1], $item['color'][2]); // RGB
                        $pdf->Rect($item['result_x'] - 2, $item['result_y'] - 4, 20, 6, 'F');
                        $pdf->SetFont('helvetica', '', 12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY($item['result_x'], $item['result_y']);
                        $pdf->Write(0, $item['result']);
                    }

                }
                // echo 'Data: <pre>' .print_r($pdf,true). '</pre>'; die;
            }
        } // Output the edited PDF
        $outputName = $clientInfo['client'] . "_edited.pdf";
        return response($pdf->Output('S', $outputName))->header('Content-Type', 'application/pdf');
    }

    public function getPdfList()
    {
        $pdfs = PdfTemplate::all();
        return view('backend.pdf.list', compact('pdfs'));
    }


    public function uploadPDF(Request $request)
    {
        $request->validate(['pdf' => 'required|mimes:pdf|max:2048',]);
        if ($request->file('pdf')) {
            $pdfFolderPath = public_path('pdf');
            if (!file_exists($pdfFolderPath)) {
                mkdir($pdfFolderPath, 0755, true);
            }
        }
        if ($request->file('pdf')) {
            $fileName = time() . '.' . $request->pdf->extension();
            $request->pdf->move($pdfFolderPath, $fileName);
            // Determine the new version
            $latestVersion = PdfTemplate::orderBy('created_at', 'desc')->first();
            $newVersion = $latestVersion ? 'v' . (substr($latestVersion->version, 1) + 1) : 'v1'; // Save to database
            $pdfTemplate = new PdfTemplate();
            $pdfTemplate->name = $fileName;
            $pdfTemplate->version = $newVersion;
            $pdfTemplate->save();
            return response()->json(['success' => 'PDF uploaded successfully. Version: ' . $newVersion]);
        }
        return response()->json(['error' => 'File upload failed.'], 500);
    }

    public function getFactorList(Request $request)
    {
        $query = Question::with('category');

        if ($request->filled('type')) {
            $query->where('question_category_id', $request->input('type'));
        }

        $factors = $query->paginate(25);

        $pdfs = PdfTemplate::all();
        $categories = QuesitonCategory::all();

        if ($request->ajax()) {
            return response()->json(['factors' => $factors, 'pdfs' => $pdfs, 'categories' => $categories]);
        }
        return view('backend.factors.list', compact('factors', 'pdfs', 'categories'));
    }

    public function saveFactor(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'category_id' => 'required|exists:question_categories,id', // Validate existence in the database
        ]);

        // Create a new Question instance
        $factor = new Question();
        $factor->question_category_id = $request->input('category_id');
        $factor->text = $request->input('text');
        $factor->save();

        // Load the category relationship for the response
        $factor->load('category');

        return response()->json([
            'success' => 'Factor saved successfully.',
            'data' => [
                'id' => $factor->id,
                'text' => $factor->text,
                'type' => $factor->category->name,
                'created_at' => $factor->created_at,
            ],
        ]);
    }

    public function getCategoryList()
    {
        $categories = QuesitonCategory::all();
        return view('backend.category.list', compact('categories'));
    }

    public function saveCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = QuesitonCategory::create([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'success' => true,
            'newItem' => $category,
        ]);
    }

    public function makeReport()
    {
        $clients = Client::all();
        $questions = Question::all();
        $pdfs = PdfTemplate::all();
        $categories = QuesitonCategory::with('questions')->get();
      return view('backend.report.add_report', compact('clients', 'questions', 'categories', 'pdfs'));
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
        ];
        // return response()->json(['success' => 'Report saved successfully.', 'data' => $categorizedQuestions, 'clientData' => $clientData]);
          $response =   $this->editPDF($categorizedQuestions , $clientData);
        return $response;
    }



}
