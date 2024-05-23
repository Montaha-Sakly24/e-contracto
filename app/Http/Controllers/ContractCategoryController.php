<?php

namespace App\Http\Controllers;

use App\Models\ContractCategory;
use Illuminate\Http\Request;

class ContractCategoryController extends Controller
{
    //Céer une nouvelle catégorie de contrat
    public function createCategory(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:25',
            'description' =>'required|max:225',
        ]);
        //422 Unprocessable Entity status code along with the validation errors in a format JSON
        $category = ContractCategory::create($validated);
        return response()->json($category, 201);
    }


    //Consulter liste catégories de contrat
    public function getListCategories()
    {
        $categories = ContractCategory::all();
        return response()->json($categories,200);
    }


    //Consulter catégorie de contrat
    public function getCategory($id)
    {
        $category = ContractCategory::with('models')->findOrFail($id);  // 'models' is the name of the relationship method defined in the model
        return response()->json($category);
    }


    //Modifier une catégorie de contrat
    public function updateCategory(Request $request,ContractCategory $category)
    {
        $validated = $request->validate([
            'title' => 'required|max:25',
            'description' =>'required|max:225',
        ]);

         $category->update($validated);
        return response()->json($category,200);

    }

    //Supprimer une catégorie de contrat
    public function deleteCategory(ContractCategory $category)
    {
        try {
            $category->delete();
            return response()->json(['success' => true, 'message' => 'category deleted successfully !']);
        }catch(\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong..Please try again later !']);
        }
    }
}
