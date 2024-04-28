<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractModel;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Symfony\Component\HttpFoundation\Response;


class ContractController extends Controller
{
    public function createContract(Request $request)
    {
        $validated = $request->validate([
            'title' => 'max:255',

        ]);

        $contract = Contract::create($validated);
        return response()->json($contract, 201);
    }
//lzm tbadaleha myfetchi kn liste el contrat ela generahum huwa
    public function getContracts()
    {
        $models = Contract::all();
        return response()->json($models,200);
    }

    public function getContract(Contract $contract)
    {
        return response()->json($contract,200);
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
            $section->addText(htmlspecialchars($contract->content));

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
        // Vérifiez l'authentification de l'utilisateur et ses permissions si nécessaire

        // Récupérez les données de la signature depuis la requête
        $signatureData = $request->input('signature_data');

        // Sauvegardez les données de la signature dans la base de données
        $contract->signature = $signatureData;
        $contract->save();


        return response()->json(['message' => 'Contract signed successfully']);
    }
}
