<?php

namespace App\Http\Controllers;

use DateTime;
use setasign\Fpdi\Fpdi;
use App\Models\Question;
use App\Models\PdfTemplate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\QuesitonCategory;;
use Illuminate\Support\Facades\File;



class PdfController extends Controller
{

    public function editPDF($questionArray, $clientInfo)
    {
        $pdf_template = PdfTemplate::find($clientInfo['pdf_template_id']);
        $pathToPdf = public_path('pdf/' . $pdf_template->name);

        if (!file_exists($pathToPdf)) {
            // Debugging line, file not found
            dd("Template file does not exist: " . $pathToPdf);
        }
        $date = $clientInfo['date'];
        $dateObject = new DateTime($date);
        $clientDate = $dateObject->format('F d, Y');

        $pdf = new FPDI();
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

                'date' =>  $clientDate,
                'date_x' => 40,
                'date_y' => 249.3,

                'website' => $clientInfo['website'],
                'website_x' => 49,
                'website_y' => 260.2
            ],

            2 => [
                'name_2' => $clientInfo['client'],
                'x' => 62.5,
                'y' => 39.5,

                'type_2' => $clientInfo['designition'],
                'type2_x' => 45,
                'type2_y' => 54,

                'score' => $clientInfo['score'] . '/100',
                'score_x' => 117,
                'score_y' => 222.5,

                'image' => $clientInfo['website_image'],
                'img_x' => 37.8,
                'img_y' => 129,
                'img_w' => 141,
                'img_h' => 80
            ],
        ];

        $questionPage = 3;
        $startX = 21;
        $startvalueX = 153;
        $resultvalueX = 176.5;
        $startY = 103;
        $colorvalueX = 171.5;
        $ColorstartY = 98;
        $colorWidth = 25.8;
        $colorHeight = 11.5;
        $colorlineHeight = 14.1;
        $lineHeight = 14.1;

        foreach ($questionArray as $category => $questions) {
            if ($category === 'Web Presence') {
                foreach ($questions as $index => $question) {
                    if ($question['result'] == 'good') {
                        $resultImage = public_path('assets/img/Greenbutton.png');
                    } elseif ($question['result'] == 'average') {
                        $resultImage = public_path('assets/img/Yellowbutton.png');
                    } else {
                        $resultImage = public_path('assets/img/Redbutton.png');
                    }

                    $data[$questionPage][] = [
                        'text' => $question['question'],
                        'x' => $startX,
                        'y' => $startY + ($index) * $lineHeight,

                        'value' => $question['value'],
                        'value_x' => $startvalueX,
                        'value_y' => $startY + ($index) * $lineHeight,

                        'result' => ucfirst($question['result']),
                        'result_x' => $resultvalueX,
                        'result_y' => $startY + ($index) * $lineHeight,

                        'result_image' => $resultImage,
                        'color_x' => $colorvalueX,
                        'color_y' => $ColorstartY + ($index) * $colorlineHeight,
                        'color_w' => $colorWidth,
                        'color_h' => $colorHeight,
                    ];
                }
            }
        }

        $questionPage = 4;
        $startX = 21;
        $startvalueX = 153;
        $resultvalueX = 176.5;
        $startY = 54.5;
        $colorvalueX = 172;
        $ColorstartY = 49;
        $colorWidth = 25;
        $colorHeight = 11.5;
        $colorlineHeight = 14.1;
        $lineHeight = 14.1;
        foreach ($questionArray as $category => $questions) {
            if ($category === 'SEO') {
                foreach ($questions as $index => $question) {
                    if ($question['result'] == 'good') {
                        $resultImage = public_path('assets/img/Greenbutton.png');
                    } elseif ($question['result'] == 'average') {
                        $resultImage = public_path('assets/img/Yellowbutton.png');
                    } else {
                        $resultImage = public_path('assets/img/Redbutton.png');
                    }
                    $data[$questionPage][] = [
                        'text' => $question['question'],
                        'x' => $startX,
                        'y' => $startY + ($index) * $lineHeight,

                        'value' => $question['value'],
                        'value_x' => $startvalueX,
                        'value_y' => $startY + ($index) * $lineHeight,

                        'result' => ucfirst($question['result']),
                        'result_x' => $resultvalueX,
                        'result_y' => $startY + ($index) * $lineHeight,

                        'result_image' => $resultImage,
                        'color_x' => $colorvalueX,
                        'color_y' => $ColorstartY + ($index) * $colorlineHeight,
                        'color_w' => $colorWidth,
                        'color_h' => $colorHeight
                    ];
                }
            }
        }

        $questionPage = 4;
        $startX = 20.5;
        $startvalueX = 153;
        $resultvalueX = 176.5;
        $startY = 175;
        $lineHeight = 15.4;
        $colorvalueX = 171;
        $ColorstartY = 169;
        $colorWidth = 20;
        $colorHeight = 20;
        $colorlineHeight = 15;
        $additionalColorHeight = 0;
        $additionalLineHeight = 4;
        foreach ($questionArray as $category => $questions) {
            if ($category === 'Site Content') {
                foreach ($questions as $index => $question) {

                    if ($question['result'] == 'good') {
                        $resultImage = public_path('assets/img/Greenbutton.png');
                    } elseif ($question['result'] == 'average') {
                        $resultImage = public_path('assets/img/Yellowbutton.png');
                    } else {
                        $resultImage = public_path('assets/img/Redbutton.png');
                    }

                    $value_y = $startY + ($index) * $lineHeight;
                    $result_y = $value_y;

                    // Check for specific phrases to break lines
                    if (strpos($question['question'], 'colors, good') !== false) {
                        $colorWidth = 26;
                        $colorHeight = 15;


                        if ($question['result'] == 'good') {
                            $resultImage = public_path('assets/img/Green-button-2.png');
                        } elseif ($question['result'] == 'average') {
                            $resultImage = public_path('assets/img/Yellow-button-2.png');
                        } else {
                            $resultImage = public_path('assets/img/Red-button-2.png');
                        }

                        [$firstLine, $secondLine] = explode('colors, good', $question['question'], 2);

                        $data[$questionPage][] = [
                            'text' => $firstLine . 'colors, good',
                            'x' => $startX,
                            'y' => $startY + ($index) * $lineHeight,

                        ];
                        $lineHeight = 15.5;

                        $data[$questionPage][] = [
                            'text' => trim($secondLine),
                            'x' => $startX,
                            'y' => $startY + ($index) * $lineHeight + $additionalLineHeight,
                        ];
                    } elseif (strpos($question['question'], 'contact form') !== false) {
                        $colorWidth = 26;
                        $colorHeight = 15;

                        if ($question['result'] == 'good') {
                            $resultImage = public_path('assets/img/Green-button-2.png');
                        } elseif ($question['result'] == 'average') {
                            $resultImage = public_path('assets/img/Yellow-button-2.png');
                        } else {
                            $resultImage = public_path('assets/img/Red-button-2.png');
                        }

                        [$firstLine, $secondLine] = explode('contact form', $question['question'], 2);
                        $lineHeight = 14.5;
                        $colorlineHeight = 14.5;
                        $data[$questionPage][] = [
                            'text' => $firstLine . 'contact form',
                            'x' => $startX,
                            'y' => $startY + ($index) * $lineHeight,
                        ];
                        $lineHeight = 15.1;
                        $data[$questionPage][] = [
                            'text' => trim($secondLine),
                            'x' => $startX,
                            'y' => $startY + ($index) * $lineHeight + $additionalLineHeight,
                        ];
                    } else {
                        $colorWidth = 26;
                        $colorHeight = 12;
                        $additionalColorHeight = 2;
                        $colorlineHeight = 14.8;
                        $startX = 19;
                        $data[$questionPage][] = [
                            'text' => $question['question'],
                            'x' => $startX,
                            'y' => $startY + ($index) * $lineHeight,
                        ];
                    }

                    $data[$questionPage][] = [
                        'value' => $question['value'],
                        'value_x' => $startvalueX,
                        'value_y' => $value_y,

                        'result' => ucfirst($question['result']),
                        'result_x' => $resultvalueX,
                        'result_y' => $result_y,

                        'result_image' => $resultImage,
                        'color_x' => $colorvalueX,
                        'color_y' => $ColorstartY + ($index) * $colorlineHeight + $additionalColorHeight,
                        'color_w' => $colorWidth,
                        'color_h' => $colorHeight
                    ];
                }
            }
        }

        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $pdf->importPage($i);
            $pdf->AddPage();
            $pdf->useTemplate($templateId);
            if (isset($data[$i])) {


                // Check and add name
                if (isset($data[$i]['name'])) {
                    $pdf->SetFont('helvetica', 'B', 17);
                    $pdf->SetTextColor(255, 255, 255); // White text for visibility
                    $pdf->SetXY($data[$i]['x'], $data[$i]['y']);
                    $pdf->Write(0, $data[$i]['name']);
                }

                // Check and add type
                if (isset($data[$i]['type'])) {
                    $pdf->SetFont('helvetica', 'B', 16);
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
                        $pdf->SetFont('helvetica', '', 12);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetXY($item['result_x'], $item['result_y']);
                        $pdf->Write(0, $item['result']);

                        // Add the result image if available
                        if (isset($item['result_image'])) {

                            $pdf->Image(
                                $item['result_image'],
                                $item['color_x'],
                                $item['color_y'],
                                $item['color_w'],
                                $item['color_h']
                            );

                            $pdf->SetXY($item['result_x'], $item['result_y']);
                            $pdf->SetFont('helvetica', 'B', 12);
                            $pdf->SetTextColor(255, 255, 255);
                            $pdf->Write(0, $item['result']);
                        }
                    }
                }
            }
        } // Output the edited PDF

        $outputName = $clientInfo['client'] . "_edited.pdf";
        $outputName = $clientInfo['client'] . "_" . now()->timestamp . "_edited.pdf";
        $path = public_path('generated/' . $outputName);
        // Ensure the 'generated' directory exists
        if (!File::exists(public_path('generated'))) {
            File::makeDirectory(public_path('generated'), 0755, true);
        }
        $pdfContent = $pdf->Output('S', $outputName); // Get the PDF content as a string
        File::put($path, $pdfContent);
        // $outputName = $clientInfo['client'] . "_edited.pdf";

        return response()->json([
            'message' => 'PDF successfully saved.',
            'path' => $outputName,
        ]);
        // return response($pdf->Output('S', $outputName))->header('Content-Type', 'application/pdf');
    }

    public function getPdfList()
    {
        $pdfs = PdfTemplate::all();
        return view('backend.pdf.list', compact('pdfs'));
    }


    public function uploadPDF(Request $request)
    {
        ini_set('memory_limit', '-1');
        $request->validate(['pdf' => 'required|mimes:pdf',]);
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

            return response()->json([
             'success' =>true,
             'newItem' => $pdfTemplate,
             'message' => 'PDF uploaded successfully. Version: ' . $newVersion]);
        }
        return response()->json(['error' => true, 'message' => 'File upload failed.'], 500);
    }

    public function deletePDF(Request $request)
    {
        $pdfTemplate = PdfTemplate::find($request->input('id'));

        if ($pdfTemplate) {
            // Construct the file path
            $filePath = public_path('pdf/' . $pdfTemplate->name);

            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            // Delete the database record
            $pdfTemplate->delete();

            return response()->json(['success' => true, 'message' => 'PDF deleted successfully.']);
        }

        return response()->json(['error' => true, 'message' => 'PDF not found.'], 404);
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

    public function deleteFactor(Request $request)
    {
        $factor = Question::find($request->input('id'));
        if ($factor) {
            $factor->delete();
            return response()->json(['success' => 'Factor deleted successfully.']);
        }
        return response()->json(['error' => 'Factor not found.'], 404);
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

    public function deleteCategory(Request $request)
    {
        $category = QuesitonCategory::find($request->input('id'));
        if ($category) {
            $category->delete();
            return response()->json(['success' => 'Category deleted successfully.']);
        }
        return response()->json(['error' => 'Category not found.'], 404);
    }
}
