<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vente;
use App\Models\Reglement;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class ReglementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     try {
    //         // ✅ Validation des données du formulaire
    //         $data = $request->validate([
    //             'vente_id' => 'required|exists:ventes,id',       // ID de la vente
    //             'montant_vente' => 'required|numeric',           // Montant total
    //             'montant_reglement' => 'required|numeric',       // Montant versé
    //             'montant_restant' => 'required|numeric',         // Reste à payer
    //             'mode_paiement' => 'required|string',            // Espèces, mobile money, etc.
    //             'montant_rendu' => 'required|numeric',           // Rendu à rendre au client
    //         ]);

    //         // 🔐 Ajout des données système (code + utilisateur connecté)
    //         $data['code'] = 'REG-' . strtoupper(Str::random(5)); // Génère un code aléatoire du type REG-AB123
    //         $data['user_id'] = auth()->id();                     // ID de l'utilisateur connecté

    //         // 💾 Création du règlement en base de données
    //         $reglement = Reglement::create($data);

    //         // 👤 Création ou récupération du client s'il y a un montant restant
    //         $client = null;

    //         if ($data['montant_restant'] > 0) {
    //             // 📌 Si le client existe déjà (ID transmis dans le formulaire)
    //             if ($request->filled('client_id')) {
    //                 $client = $request->client_id;
    //             } else {
    //                 // ➕ Sinon, on crée un nouveau client à partir des infos saisies
    //                 $newClient = User::firstOrCreate(
    //                     ['phone' => $request->phone], // Unicité via téléphone
    //                     [
    //                         'last_name' => $request->last_name,
    //                         'first_name' => $request->first_name,
    //                         'role' => 'client',
    //                     ]
    //                 );

    //                 // 🔐 On lui attribue le rôle "client"
    //                 $newClient->assignRole('client');

    //                 // 🆔 On récupère son ID
    //                 $client = $newClient->id;
    //             }
    //         }

    //         // 🛒 Mise à jour de la vente liée au règlement
    //         $vente = Vente::findOrFail($request->vente_id);
    //         $vente->update([
    //             'client_id' => $client,                                 // Lier le client à la vente si partiellement payée
    //             'montant_recu' => $data['montant_reglement'],           // Montant payé
    //             'montant_rendu' => $data['montant_rendu'],              // Monnaie rendue
    //             'montant_restant' => $data['montant_restant'],          // Reste à payer
    //             'mode_paiement' => $data['mode_paiement'],              // Type de paiement
    //             'statut_paiement' => $data['montant_restant'] == 0 ? 'paye' : 'impaye', // Statut selon solde restant
    //             'statut_reglement' => 1,                                // 1 : un règlement a été effectué
    //         ]);

    //         // ✅ Retour succès avec message
    //         return back()->with('success', 'Règlement effectué avec succès');
    //     } catch (\Throwable $th) {
    //         // ❌ En cas d’erreur : journalisation + message utilisateur
    //         Log::error('Erreur règlement : ' . $th->getMessage());

    //         return back()->with('error', 'Une erreur est survenue : ' . $th->getMessage());
    //     }
    // }


    public function store(Request $request)
    {


        try {
            DB::beginTransaction(); // ✅ Démarre la transaction

            // ✅ 1. Valider les données d'entrée
            $data = $request->validate([
                'vente_id' => 'required|exists:ventes,id',
                'montant_vente' => 'required|numeric',
                'montant_reglement' => 'required|numeric',
                'montant_restant' => 'required|numeric',
                'mode_paiement' => 'required|string',
                'montant_rendu' => 'required|numeric',
            ]);

            // 🔐 2. Générer le code et associer l'utilisateur
            $data['code'] = 'REG-' . strtoupper(Str::random(5));
            $data['user_id'] = auth()->id();
            $data['created_at'] = auth()->user()->caisse->session_date_vente; // Statut de la vente

            // 💾 3. Créer le règlement
            $reglement = Reglement::create($data);

            // 👤 4. Gérer le client si nécessaire
            $vente = Vente::find($request->vente_id);

            $client = $vente->client_id; // par défaut, on garde le client existant

            // Si la vente n'a pas encore de client ET qu'il reste un montant à payer
            if ($reglement->montant_restant > 0 && is_null($vente->client_id)) {

                if ($request->filled('client_id')) {
                    // Utiliser un client existant
                    $client = $request->client_id;
                } else {
                    // Créer un nouveau client s'il n'existe pas déjà par téléphone
                    $newClient = User::firstOrCreate(
                        ['phone' => $request->phone],
                        [
                            'last_name' => $request->last_name,
                            'first_name' => $request->first_name,
                            'role' => 'client',
                        ]
                    );

                    // Assigner le rôle client si ce n'est pas déjà fait
                    if (!$newClient->hasRole('client')) {
                        $newClient->assignRole('client');
                    }

                    $client = $newClient->id;
                }
            }


            // 🛒 5. Mise à jour de la vente
            $vente = Vente::findOrFail($request->vente_id);
            $montant_recu = $vente->montant_recu + $reglement->montant_reglement;
            $vente->update([
                'client_id' => $client,
                'montant_recu' => $montant_recu,
                'montant_rendu' => $data['montant_rendu'],
                'montant_restant' => $data['montant_restant'],
                'mode_paiement' => $data['montant_restant'] == 0 ? $data['mode_paiement'] : 'impaye',
                'statut_paiement' => $data['montant_restant'] == 0 ? 'paye' : 'impaye',
                'statut_reglement' => 1,
                'statut' =>'confirmée', // Statut de la vente,
            ]);

            DB::commit(); // ✅ Si tout va bien, on valide la transaction
            // ✅ Retour succès avec message
            Alert::success('Règlement effectué avec succès')->flash();
            return back()->with('success', 'Règlement effectué avec succès');
        } catch (\Throwable $th) {
            DB::rollBack(); // ❌ Une erreur → on annule tout
            Log::error('Erreur règlement : ' . $th->getMessage());

            

            return back()->with('error', 'Une erreur est survenue : ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
