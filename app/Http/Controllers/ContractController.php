<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PDF;

use Illuminate\Support\Facades\Mail;
use App\Mail\ContractMailable;


class ContractController extends Controller
{
    public function generateContract(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'content' =>'required',
            'model_id' => 'required|exists:contract_models,id'
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        try{
            $contract = Contract::create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'model_id' => $validatedData['model_id'],
                'status' => 'unsigned',
                'user_id' => $user->id,
            ]);

            $model = ContractModel::findOrFail($validatedData['model_id']);
            $model->contracts()->save($contract);

            return response()->json($contract, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        };

    }
//lzm tbadaleha myfetchi kn liste el contrat ela generahum huwa
    public function getContracts()
    {
        $user = Auth::user();
        $contracts = $user->contracts;
        return response()->json($contracts, 200);
    }

    public function getContract(Contract $contract)
    {
        try {

            $content = $contract->content;

        // Generate the PDF stream
        $pdf = PDF::loadHTML($content);

         if (!empty($contract->signature)) {

            $dompdf = $pdf->getDomPDF();

            $pageWidth = $dompdf->getCanvas()->get_width();
            $pageHeight = $dompdf->getCanvas()->get_height();

            $signatureWidth = 180;
            $signatureHeight = 80;
            $signatureX = $pageWidth - $signatureWidth - 10;
            $signatureY = $pageHeight - $signatureHeight - 40;

            // Add the signature to the PDF
            $dompdf->getCanvas()->image($contract->signature, $signatureX, $signatureY, $signatureWidth, $signatureHeight);
        }

        return $pdf->stream();

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteContract(Contract $contract)
    {
        try {
            $contract->delete();
            return response()->json(['success' => true, 'message' => 'Model deleted successfully !']);
        }catch(\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong..Please try again later !']);
        }
    }

    private function getSignaturePath($signatureBase64)
    {
        // Example: Data comes in as base64, needs to be converted to an image file.
        $imageData = explode(';base64,', $signatureBase64);
        if (count($imageData) == 2) {
            $data = base64_decode($imageData[1]);
            $filePath = tempnam(sys_get_temp_dir(), 'signature_') . '.png';
            file_put_contents($filePath, $data);
            return $filePath;
        }
        throw new \Exception("Invalid signature format");
    }

    public function exportContract(Contract $contract) {
        try {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            Html::addHtml($section, $contract->content, false, false);

            if (!empty($contract->signature)) {
                $section->addTextBreak();

                $rightAlignedStyle = [
                    'alignment' => Jc::END,
                    'indentation' => ['right' => 1000]
                ];

                $section->addText(
                    'Signature:',
                    [
                        'name' => 'Arial',
                        'size' => 12,
                        'bold' => true
                    ],
                    $rightAlignedStyle
                );

                try {
                    $signaturePath = $this->getSignaturePath($contract->signature);
                    $section->addImage($signaturePath, [
                        'width'         => 140,
                        'height'        => 100,
                        'alignment'     => Jc::END,
                        'wrappingStyle' => 'behind'
                    ]);
                } catch (\Exception $e) {
                    error_log('Error processing signature: ' . $e->getMessage());
                }
            }

            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $fileName = 'Contract_' . $contract->title . '.docx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);
            $writer->save($temp_file);
            return response()->download($temp_file, $fileName, ['Content-Type' => 'application/msword'])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function signContract(Request $request, Contract $contract)
    {
        $signatureData = $request->input('signature_data');

        $contract->signature = $signatureData;
        $contract->status = 'signed';
        $contract->save();


        return response()->json(['message' => 'Contract signed successfully']);
    }


    public function sendContract(Request $request)
    {
        try {
            $request->validate([
                'recipient_email' => 'required|email',
                'subject' => 'required|string',
                'body' => 'required|string',
                'contract_id' => 'required|exists:contracts,id',

            ]);

            $contractId = $request->input('contract_id');
            $contract = Contract::findOrFail($contractId);

            $pdf = PDF::loadHTML($contract->content);

            if (!empty($contract->signature)) {
                $dompdf = $pdf->getDomPDF();
                $pageWidth = $dompdf->getCanvas()->get_width();
                $pageHeight = $dompdf->getCanvas()->get_height();

                $signatureWidth = 180;
                $signatureHeight = 80;
                $signatureX = $pageWidth - $signatureWidth - 10;
                $signatureY = $pageHeight - $signatureHeight - 40;

                $dompdf->getCanvas()->image($contract->signature, $signatureX, $signatureY, $signatureWidth, $signatureHeight);
            }

            $pdfContent = $pdf->output();

            $recipientEmail = $request->input('recipient_email');
            $subject = $request->input('subject');
            $body = $request->input('body');

            $senderEmail = $request->input('sender_email');

            Mail::raw($body, function ($message) use ($recipientEmail, $subject, $pdfContent, $senderEmail) {
                $message->to($recipientEmail)
                    ->from($senderEmail)
                    ->subject($subject)
                    ->attachData($pdfContent, 'contract.pdf');
            });

            return response()->json(['message' => 'Contract sent successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
