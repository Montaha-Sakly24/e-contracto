<?php

namespace App\Http\Controllers;

use App\Models\ContractModel;
use App\Models\Gabarit;
use App\Models\ContractCategory;
use Illuminate\Http\Request;

class ContractModelController extends Controller
{
    public function createModel(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required|max:50',
            'description' => 'required|max:255',
            'content' =>'required',
            // 'gabarits' => 'required|array',
            'category_id' => 'required|exists:contract_categories,id'
        ]);
        try{
            $contractModel = ContractModel::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'content' => $validatedData['content'],
                'category_id' => $validatedData['category_id']
            ]);

            // // Ajouter les gabarits au modèle de contrat
            // foreach ($validatedData['gabarits'] as $gabaritId) {
            //     // Vérifiez si le gabarit existe
            //     $gabarit = Gabarit::find($gabaritId);

            //     // Si le gabarit existe, associez-le au modèle de contrat
            //     if ($gabarit) {
            //         $contractModel->gabarits()->attach($gabaritId);
            //     }
            // }

            // Ajoutez le modèle de contrat à la catégorie correspondante
            $category = ContractCategory::findOrFail($validatedData['category_id']);
            $category->models()->save($contractModel);

            // Retourner une réponse avec le modèle de contrat créé
            return response()->json($contractModel, 201);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the creation process
            return response()->json(['error' => $e->getMessage()], 500);
        };
    }

    public function getAllModels()
    {
        $models = ContractModel::all();
        return response()->json($models,200);
    }

    public function getModel(ContractModel $model)
    {
        return response()->json($model,200);
    }

    public function updateModel (Request $request,ContractModel $model)
    {
        $validated = $request->validate([
            'title' => 'max:50',
            'description'=>'max:255',
            'content' =>'max:3000',
        ]);
        $model->update($validated);
        return response()->json($model,200);
    }

    public function deleteModel(ContractModel $model)
    {
        try {
            $model->delete();
            return response()->json(['success' => true, 'message' => 'Model deleted successfully !']);
        }catch(\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong..Please try again later !']);
        }
    }

}
