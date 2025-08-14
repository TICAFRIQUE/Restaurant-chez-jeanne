<?php

namespace App\Http\Controllers\backend\offert;

use App\Models\Vente;
use App\Models\Caisse;
use App\Models\Offert;
use Illuminate\Http\Request;
use App\Events\OffertApprouved;
use App\Models\OffertNotification;
use App\Http\Controllers\Controller;

class OffertController extends Controller
{
    //index
    public function index(Request $request)
    {
        try {

            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            $selectedStatut = $request->input('statut'); // Récupérer le statut sélectionné

            $data_offerts = Offert::with([
                'vente',
                'produit',
                'variante',
                'userApprouved',
                'vente.produits' => function ($query) {
                    $query->where('offert', true);
                }
            ])
                ->when($dateDebut && $dateFin, function ($query) use ($dateDebut, $dateFin) {
                    $query->whereBetween('created_at', [$dateDebut, $dateFin]);
                })
                // ici on enlève la condition "falsy"
                ->when($selectedStatut !== null, function ($query) use ($selectedStatut) {
                    if ($selectedStatut === 'null') {
                        $query->whereNull('offert_statut');
                    } elseif ($selectedStatut === '1') {
                        $query->where('offert_statut', 1);
                    } elseif ($selectedStatut === '0') {
                        $query->where('offert_statut', 0);
                    }
                })
                ->when($dateDebut, function ($query) use ($dateDebut) {
                    $query->where('date_created', $dateDebut);
                })
                ->when($dateFin, function ($query) use ($dateFin) {
                    $query->where('date_created', $dateFin);
                })
                ->orderBy('created_at', 'desc')
                ->get();



            // recuperer la liste des caisses
            $caisses = Caisse::all();

            return view('backend.pages.vente.offert.index', compact('data_offerts', 'caisses'));
        } catch (\Throwable $e) {
            # code...
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement de la page.' . $e->getMessage());
        }
    }


    // get offert no approuved
    public function getOffertNoApprouved()
    {
        try {
            $offerts = Offert::whereNull('offert_statut')
                ->with([
                    'vente',
                    'produit',
                    'variante',
                    'vente.produits' => function ($query) {
                        $query->where('offert', true);
                    },

                    'vente.caisse',
                    'vente.user',
                    'userApprouved'
                ])
                ->get();



            return response()->json([
                'offerts' => $offerts
            ]);
        } catch (\Throwable $e) {
            # code...
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement de la page.' . $e->getMessage());
        }
    }


    // approuved offert
    public function approuvedOffert(Request $request)
    {
        try {

            // recuperer les request parametres
            $id = $request->offert;
            $approuved = $request->approuved;


            // id offert
            $offert = Offert::find($id);
            // id vente offert
            $vente = Vente::find($offert->vente_id);
            // verifier si l'offert existe
            if (!$offert) {
                return redirect()->back()->with('error', 'L\'offert ou la vente n\'existe pas.');
            }

            // mettre a jour l'offert
            $offert->offert_statut = $approuved; // approuved ou non
            $offert->statut_view = true; // 
            $offert->user_approuved = auth()->user()->id; // l'utilisateur qui a approuvé ou rejeté l'offert
            $offert->date_approuved = now(); // l'utilisateur qui a approuvé ou rejeté l'offert
            $offert->save();


            // // mettre a jour le produit offert de la vente dans la table vente_produit
            $vente->produits()->where('produit_id', $offert->produit_id)
                ->where('offert', true)
                ->update([
                    'offert_statut' => $approuved
                ]);

            // si l'offert n'est pas approuvé, on va ajouter le total du produit dans la vente
            if ($approuved == 0) {
                // mettre a jour la vente
                $vente->update([
                    'montant_total' => $vente->montant_total += ($offert->prix * $offert->quantite),
                    'montant_avant_remise' => $vente->montant_avant_remise += ($offert->prix * $offert->quantite),
                    'montant_restant' => $vente->montant_restant += ($offert->prix * $offert->quantite),
                    'statut_reglement' => 0, // non réglée
                    'statut_paiement' => 'impaye', // non payée
                ]);

                // mettre a jour le produit offert de la vente dans la table vente_produit
                $vente->produits()->where('produit_id', $offert->produit_id)
                    // ->where('offert', true)
                    ->update([
                        'offert_statut' => 0, // non approuvé
                        'offert' => false, // non offert
                    ]);
            }


            // enregistrer la notification AVANT de faire le redirect
            OffertNotification::create([
                'offert_id' => $offert->id,
                'vente_id' => $vente->id,
                'message' => $approuved == 1 ? 'L\'offert du produit ' . $offert->produit->nom .  'de la vente ' . $vente->code . ' a été approuvé.' : 'L\'offert du produit ' . $offert->produit->nom .  'de la vente ' . $vente->code . ' a été rejeté.',
                'is_read' => false,
            ]);

            if (isset($approuved) && $approuved == 1) {
                return redirect()->back()->with('success', 'L\'offert du produit' . $offert->produit->nom .  'de la vente ' . $vente->code . ' a été approuvé avec succès.');
            } else {
                return redirect()->back()->with('success', 'L\'offert du produit ' . $offert->produit->nom .  'de la vente ' . $vente->code . ' a été rejeté avec succès.');
            }
        } catch (\Throwable $e) {
            # code...
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement de la page.' . $e->getMessage());
        }
    }

    public function checkNotifications()
    {
        try {
            $notifications = OffertNotification::with(['offert', 'vente'])
                ->where('is_read', false)
                ->get();
            return response()->json($notifications);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue lors du chargement des notifications.'
            ], 500);
        }
    }

    public function markAsRead(Request $request)
    {
        $notification = OffertNotification::find($request->id);

        if ($notification) {
            $notification->update(['is_read' => true]);
        }

        return response()->json(['status' => 'ok']);
    }
}
