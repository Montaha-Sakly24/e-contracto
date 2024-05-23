<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Placeholder;

class PlaceholderController extends Controller
{

    public function createPlaceholder(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:25',
            'identification' =>'required',
        ]);

        $placeholder = Placeholder::create($validated);
        return response()->json($placeholder, 201);
    }

    public function getPlaceholders(){

        $placeholders=Placeholder::all();
        return response()->json($placeholders,200);
    }


    public function updatePlaceholder(Request $request, Placeholder $placeholder)
{
    $validated = $request->validate([
        'name' => 'required|max:25',
        'identification' => 'required',
    ]);
    $placeholder->update($validated);
    return response()->json($placeholder, 200);
}

    public function deletePlaceholder(Placeholder $placeholder)
    {
        try {
            $placeholder->delete();
            return response()->json(['success' => true, 'message' => 'placeholder deleted successfully !']);
        }catch(\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong..Please try again later !']);
        }
    }

}
