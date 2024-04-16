<?php

namespace App\Http\Controllers;

use App\Models\Gabarit;
use Illuminate\Http\Request;

class GabaritController extends Controller
{
    public function createGabarit(Request $request){
        // Valider les données de la requête
        $request->validate([
            'title' => 'required|string',
            'content' => 'string|max:3000',
            'placeholders' => 'array'
        ]);

        // Créer un nouvel gabarit avec le titre et la contenu fournis
        $gabarit = Gabarit::create([
            'title' => $request->input('title'),
            'content' => $request->input('content')
        ]);

        // Ajouter les placeholders à la contenu de gabarit
        if ($request->filled('placeholders')) {
            $content = $request->input('content');

            // Parcourir les placeholders et les ajouter au gabarit
            foreach ($request->input('placeholders') as $placeholder) {
                $content .= " {{$placeholder}}"; // Ajouter le placeholder à la contenu
            }

            // Mettre à jour la contenu de gabarit avec les placeholders
            $gabarit->update(['content' => $content]);
        }

        // Répondre avec le nouveau gabarit
        return response()->json(['gabarit' => $gabarit], 201);
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

    public function updateGabarit (Request $request,Gabarit $gabarit)
    {
        $validated = $request->validate([
            'title' => 'max:25',
            'content' =>'max:3000',
        ]);
        $gabarit->update($validated);
        return response()->json($gabarit,200);

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
