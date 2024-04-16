<?php

namespace App\Http\Controllers;

use app\Models\ContractModel;
use app\Models\Gabarit;
use app\Models\ContractCategory;
use Illuminate\Http\Request;

class ContractModelController extends Controller
{
    public function createModel(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'content' =>'required',
            //'gabarits' => 'required|array'
            'category_id' => 'required|exists:contract_categories,id'
        ]);

        $contractModel = ContractModel::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'content' => $validatedData['content']
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
    }

    public function getAllModels()
    {
        $models = ContractModel::all();
        return response()->json($models,200);
    }

    public function getModel()
    {

    }

    public function updateModel ()
    {

    }

    public function deleteModel()
    {

    }
}
