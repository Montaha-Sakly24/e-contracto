<?php

namespace App\Http\Controllers;

use App\Models\Gabarit;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GabaritController extends Controller
{
    public function createGabarit(Request $request){
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:25',
                'content' => 'required|string|max:3000',
                'placeholders' => 'array',
                'placeholders.*' => 'integer|exists:placeholders,id'
            ]);

            $gabarit = Gabarit::create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content']
            ]);

            if ($request->filled('placeholders')) {
                $gabarit->placeholders()->attach($validatedData['placeholders']);
            }

            return response()->json(['gabarit' => $gabarit], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['internal' => 'An error occurred while processing your request.']], 500);
        }
    }

    public function getAllGabarits()
    {
        $gabarits = Gabarit::all();
        return response()->json($gabarits,200);
    }

    public function getGabarit(Gabarit $gabarit)
    {
        return response()->json($gabarit,200);
    }

    public function updateGabarit(Request $request, Gabarit $gabarit)
    {
        $validated = $request->validate([
            'title' => 'required|max:25',
            'content' => 'required|max:3000',
            'placeholders' => 'array',
            'placeholders.*' => 'integer|exists:placeholders,id'
        ]);

        $gabarit->update([
            'title' => $validated['title'],
            'content' => $validated['content']
        ]);

        if (isset($validated['placeholders'])) {
            $gabarit->placeholders()->sync($validated['placeholders']);
        }

        return response()->json($gabarit, 200);
    }



    public function deleteGabarit(Gabarit $gabarit)
    {
        try {
            $gabarit->delete();
            return response()->json(['success' => true, 'message' => 'Gabarit deleted successfully !']);
        }catch(\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong..Please try again later !']);
        }
    }
}
