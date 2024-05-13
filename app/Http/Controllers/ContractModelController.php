<?php

namespace App\Http\Controllers;

use App\Models\ContractModel;
use App\Models\Gabarit;
use App\Models\ContractCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractModelController extends Controller
{
    public function createModel(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required|max:50',
            'description' => 'required|max:255',
            'content' =>'required',
            'gabarits' => 'required|array',
            'gabarits.*' => 'exists:gabarits,id',
            'category_id' => 'required|exists:contract_categories,id'
        ]);


        try{
            $user = Auth::user();

            $contractModel = ContractModel::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'content' => $validatedData['content'],
                'category_id' => $validatedData['category_id'],
                'user_id' => $user->id,
            ]);

            if (!empty($validatedData['gabarits'])) {
                $contractModel->gabarits()->attach($validatedData['gabarits']);
            }

            $category = ContractCategory::findOrFail($validatedData['category_id']);
            $category->models()->save($contractModel);

            return response()->json($contractModel, 201);
        } catch (\Exception $e) {
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
        $model = ContractModel::with('category')->find($model->id);
        return response()->json($model);
    }

    public function getModelDetails($modelId)
    {
        $model = ContractModel::with(['gabarits.placeholders'])
                    ->find($modelId);

        if (!$model) {
            return response()->json(['message' => 'Model not found'], 404);
        }

        // Tjib données l associés lb3dhha lkol
        $modelDetails = [
            'title' => $model->title,
            'description' => $model->description,
            'content' => $model->content,
            'gabarits' => $model->gabarits->map(function ($gabarit) {
                return [
                    'title' => $gabarit->title,
                    'content' => $gabarit->content,
                    'placeholders' => $gabarit->placeholders->map(function ($placeholder) {
                        return [
                            'name' => $placeholder->name,
                           // 'type' => $placeholder->type
                        ];
                    })
                ];
            })
        ];

        return response()->json($modelDetails);
    }

    public function updateModel (Request $request,ContractModel $model)
    {
        $validated = $request->validate([
            'title' => 'max:50',
            'description'=>'max:255',
            'content' =>'max:10000',

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

    public function filterModels(Request $request)
    {
        $categoryIds = $request->input('categories', []);

        $models = ContractModel::whereIn('category_id', $categoryIds)->get();

        return response()->json($models, 200);
    }


}
