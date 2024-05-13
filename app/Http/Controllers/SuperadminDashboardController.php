<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ContractModel;
use App\Models\Contract;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class SuperadminDashboardController extends Controller
{
    public function getAdminsCount()
    {
        $totalAdmins = User::role('Admin')->count();
        return response()->json(['total_admins' => $totalAdmins], 200);
    }

    public function getSamplesUsersCount()
    {
        $totalUsers = User::role('Simple User')->count();
        return response()->json(['total_users' => $totalUsers], 200);
    }

    public function getModelsCountByAdmin()
    {

    $adminRole = Role::where('name', 'Admin')->first();

    if (!$adminRole) {
        return response()->json(['error' => 'Admin role not found'], 404);
    }

    $modelsCountByAdmin = ContractModel::join('model_has_roles', 'contract_models.user_id', '=', 'model_has_roles.model_id')
        ->where('model_has_roles.role_id', $adminRole->id)
        ->join('users', 'contract_models.user_id', '=', 'users.id')
        ->select('users.name', DB::raw('count(contract_models.id) as total_models'))
        ->groupBy('users.name')
        ->get();

    return response()->json($modelsCountByAdmin, 200);
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

    public function getSignedContractsPercentage()
    {
        $totalContracts = Contract::count();
        $signedContracts = Contract::where('status', 'signed')->count();

        // Calculate the percentage
        $signedPercentage = $totalContracts > 0 ? ($signedContracts / $totalContracts) * 100 : 0;

        return response()->json(['signed_percentage' => $signedPercentage], 200);
    }

    public function getContractsCountLastFourWeeks()
    {
        // Initialize an array to store the contracts count for each week
        $contractsCountLastFourWeeks = [];

        // Calculate the start and end date of each week in the last month
        for ($i = 1; $i <= 4; $i++) {
            $startDate = Carbon::now()->subMonth()->startOfMonth()->addWeeks($i - 1);
            $endDate = Carbon::now()->subMonth()->startOfMonth()->addWeeks($i)->subDay(); // End of the week is one day before the start of the next week

            // Query contracts created between the start and end date of the current week
            $contractsCount = Contract::whereBetween('created_at', [$startDate, $endDate])->count();

            // Store the contracts count for the current week
            $contractsCountLastFourWeeks[] = [
                'week' => $i,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'contracts_count' => $contractsCount
            ];
        }

        return response()->json($contractsCountLastFourWeeks, 200);
    }

    public function getNewSampleUsersByMonth()
    {
        $newSampleUsersByMonth = User::role('Simple User')
            ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count'))
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
            ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
            ->get();

        return response()->json($newSampleUsersByMonth, 200);
    }

}
