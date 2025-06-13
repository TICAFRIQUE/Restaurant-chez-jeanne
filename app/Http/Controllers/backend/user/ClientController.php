<?php

namespace App\Http\Controllers\backend\user;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class ClientController extends Controller
{
    public function index()
    {
        try {
            $clients = User::whereHas('roles', function ($query) {
                $query->where('name', 'client');
            })->get();
            return view('backend.pages.auth-client.index', compact('clients'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function store(Request $request)
    {
        try {
            // Validation des données
            $validatedData = $request->validate([
                'last_name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'phone' => '',
            ]);

            // Vérifier si le numéro de téléphone existe déjà
            if (User::where('phone', $validatedData['phone'])->exists()) {
                Alert::error('Erreur', 'Ce numéro ' . $validatedData['phone'] . ' est déjà associé à un utilisateur');
                return back();
            }

            // Vérification supplémentaire pour le numéro de téléphone
            if (!preg_match('/^[0-9]{10}$/', $validatedData['phone'])) {
                Alert::error('Erreur', 'Le numéro de téléphone doit contenir exactement 10 chiffres.');
                return back();
            }
            // Création ou récupération de l'utilisateur
            $user = User::firstOrCreate(
                ['phone' => $validatedData['phone']],
                [
                    'last_name' => $validatedData['last_name'],
                    'first_name' => $validatedData['first_name'],
                    'role' => 'client',
                ]
            );

            // Attribution du rôle 'client'
            $user->assignRole('client');
            Alert::success('Succès', 'Client créé avec succès');

            return back()->with('success', 'Client créé avec succès');
        } catch (\Throwable $th) {
            Alert::error('Erreur', $th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $client = User::find($id);
            $client->update($request->all());
            Alert::success('Succès', 'Client modifié avec succès');
            return redirect()->route('client.index')->with('success', 'Client modifié avec succès');
        } catch (\Throwable $th) {
            Alert::error('Erreur', $th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function delete($id)
    {
        User::find($id)->forceDelete();
        return response()->json([
            'status' => 200,
        ]);
    }
}
