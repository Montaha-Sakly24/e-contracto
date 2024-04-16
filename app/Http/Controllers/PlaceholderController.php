<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Placeholder;

class PlaceholderController extends Controller
{
    public function getPlaceholders(){

        $placeholders=Placeholder::all();
        return response()->json($placeholders,200);
    }
}
