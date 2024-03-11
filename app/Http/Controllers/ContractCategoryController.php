<?php

namespace App\Http\Controllers;

use App\Models\ContractCategory;
use Illuminate\Http\Request;

class ContractCategoryController extends Controller
{
   /* public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:create-contract-category|edit-contract-category|delete-contract-category', ['only' => ['showlisteCategories','showCategory']]);
       $this->middleware('permission:create-contract-category', ['only' => ['createCategory']]);
       $this->middleware('permission:edit-contract-category', ['only' => ['updateCategory']]);
       $this->middleware('permission:delete-contract-category', ['only' => ['deleteCategory']]);
    }
*/


    //Céer une nouvelle catégorie de contrat
    public function createCategory(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' =>'required',
        ]);

        $category = ContractCategory::create($validated);
        return response()->json($category, 201);
    }


    //Consulter liste catégories de contrat
    public function showlisteCategories()
    {
        $categories = ContractCategory::all();
        return response()->json($categories,200);
    }


    //Consulter catégorie de contrat
    public function showCategory(ContractCategory $category)
    {
        return response()->json($category,200);
    }


    //Modifier une catégorie de contrat
    public function updateCategory(Request $request,ContractCategory $category)
    {


        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' =>'required',
        ]);
        // Vérifie si la catégorie de contrat existe
        if (is_null($category)) {
            return response()->json(['error' => 'Catégorie de contrat non trouvée'], 404);
        }

         $category->update($validated);
        return response()->json($category,200);

    }


    //Supprimer une catégorie de contrat
    public function deleteCategory(ContractCategory $category)
    {
        $category->delete();
        return response ('deleted',200);
    }
}
