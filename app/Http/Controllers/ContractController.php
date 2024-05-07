<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractModel;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use Symfony\Component\HttpFoundation\Response;
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
        try{
            $contract = Contract::create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'model_id' => $validatedData['model_id'],
                'status' => 'unsigned'
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
        $models = Contract::all();
        return response()->json($models,200);
    }

    public function getContract(Contract $contract)
    {
        // Initialize the response with the basic contract details
        $response = [
            'id' => $contract->id,
            'title' => $contract->title,
            'content' => $contract->content,
            'model_id' => $contract->model_id,
            'status' => $contract->status,
            'created_at' => $contract->created_at->toDateTimeString(),
            'updated_at' => $contract->updated_at->toDateTimeString()
        ];

        // Add the signature to the response if it exists
        if (!empty($contract->signature)) {
            $response['signature'] = $contract->signature;
        }

        return response()->json($response, 200);
    }

    public function updateContract (Request $request,Contract $contract)
    {
        $validated = $request->validate([
            'title' => 'max:255',

        ]);
        $contract->update($validated);
        return response()->json($contract,200);
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

    public function exportContract(Contract $contract) {
        try {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $section->addText(
                $contract->title,
                array('name' => 'Arial', 'size' => 14, 'bold' => true)
            );

            $section->addTextBreak(1);
            Html::addHtml($section, $contract->content, false, false);

            if (!empty($contract->signature)) {
                $section->addTextBreak();

                $section->addText('Signature:', ['name' => 'Arial', 'size' => 12, 'bold' => true]);
                $signaturePath = $this->getSignaturePath($contract->signature);
                $section->addImage($signaturePath, [
                    'width' => 200,
                    'height' => 100,
                    'alignment'     => Jc::CENTER,
                    'wrappingStyle' => 'behind'
                ]);
            }

            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $fileName = 'Contract_' . $contract->title . '.docx';

            $temp_file = tempnam(sys_get_temp_dir(), $fileName);
            $writer->save($temp_file);

            return response()->download($temp_file, $fileName, ['Content-Type' => 'application/msword'])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to export contract. Please try again later.'], 500);
        }
    }

    public function signContract(Request $request, Contract $contract)
    {
        // Vérifiez l'authentification de l'utilisateur

        // Récupérez les données de la signature
        $signatureData = $request->input('signature_data');

        $contract->signature = $signatureData;
        $contract->status = 'signed';
        $contract->save();


        return response()->json(['message' => 'Contract signed successfully']);
    }

    public function sendContract(Request $request)
    {
        $request->validate([
            'recipientEmail' => 'required|email',
            'contract_id' => 'required|exists:contracts,id',
        ]);

        try {

            $contract = Contract::findOrFail($request->input('contract_id'));


            Mail::to($request->recipientEmail)->send(new ContractMailable($contract));

            return response()->json(['message' => 'Contract sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send contract. Please try again later.'], 500);
        }
    }


}
