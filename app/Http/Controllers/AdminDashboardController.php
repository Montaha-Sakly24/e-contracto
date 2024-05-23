<?php

namespace App\Http\Controllers;


use App\Models\ContractCategory;
use App\Models\Gabarit;
use App\Models\ContractModel;
use App\Models\Contract;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function getCategoriesCount()
    {
        $categoriesCount = ContractCategory::count();
        return response()->json($categoriesCount, 200);
    }

    public function getModelsCountByCategory()
    {
        $categories = ContractCategory::withCount('models')->get();
        $data = [];

        foreach ($categories as $category) {
            $data[] = [
                'category_title' => $category->title,
                'total_models' => $category->models_count
            ];
        }

        return response()->json($data, 200);
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

    public function getContractsCountBySampleUser()
    {
        $sampleUserRole = Role::where('name', 'Simple User')->first();

        if (!$sampleUserRole) {
            return response()->json(['error' => 'Sample User role not found'], 404);
        }

        $contractsCountBySampleUser = Contract::join('model_has_roles', 'contracts.user_id', '=', 'model_has_roles.model_id')
            ->where('model_has_roles.role_id', $sampleUserRole->id)
            ->join('users', 'contracts.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('count(contracts.id) as total_contracts'))
            ->groupBy('users.name')
            ->get();

        return response()->json($contractsCountBySampleUser, 200);
    }
}
