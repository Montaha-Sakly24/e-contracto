<?php

namespace App\Http\Controllers;


use App\Models\ContractCategory;
use App\Models\Gabarit;
use App\Models\ContractModel;

class AdminDashboardController extends Controller
{
    public function getCategoriesCount()
    {
        $categoriesCount = ContractCategory::count();
        return response()->json($categoriesCount, 200);
    }

    public function getModelsCountByCategory($id)
    {
        $category = ContractCategory::withCount('models')->findOrFail($id);
        return response()->json(['category_title' => $category->title, 'total_models' => $category->models_count], 200);
    }

    public function getGabaritUsageCount()
    {
        $gabarits = Gabarit::all();

        foreach ($gabarits as $gabarit) {
            $gabarit->contract_models_count = $gabarit->contractModels()->count();
        }

        return response()->json($gabarits, 200);
    }

    public function getTopContractModels()
    {
        $topContractModels = ContractModel::withCount('contracts')
            ->orderByDesc('contracts_count')
            ->take(5)
            ->get();

        return response()->json($topContractModels, 200);
    }

}
