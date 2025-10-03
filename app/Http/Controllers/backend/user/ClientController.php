<?php

namespace App\Http\Controllers\backend\user;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        try {

            // Récupération des utilisateurs ayant le rôle 'client'
            // $query = User::withCount('ventesClient as ventes_total')
            //     ->withCount([
            //         'ventesClient as ventes_paye'
            //         => function ($query) {
            //             $query->where('statut_paiement', '=', 'paye');
            //         },
            //         'ventesClient as ventes_impaye'
            //         => function ($query) {
            //             $query->where('statut_paiement', '=', 'impaye');
            //         }
            //     ])
            //     ->whereHas('roles', function ($query) {
            //         $query->where('name', 'client');
            //     })->with('ventesClient');

            $query = User::whereHas('roles', function ($query) {
                $query->where('name', 'client');
            })->with('ventesClient');

            // request des filtres
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            $statutPaiement = $request->input('statut_paiement'); // paye ou impaye



            // Formatage des dates
            $dateDebut = $request->filled('date_debut') ? Carbon::parse($dateDebut)->format('Y-m-d') : null;
            $dateFin = $request->filled('date_fin') ? Carbon::parse($dateFin)->format('Y-m-d') : null;

            // Application des filtres de date
            if ($dateDebut && $dateFin) {
                $query->whereHas('ventesClient', function ($query) use ($dateDebut, $dateFin) {
                    $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
                });
            } elseif ($dateDebut) {
                $query->whereHas('ventesClient', function ($query) use ($dateDebut) {
                    $query->where('date_vente', '>=', $dateDebut);
                });
            } elseif ($dateFin) {
                $query->whereHas('ventesClient', function ($query) use ($dateFin) {
                    $query->where('date_vente', '<=', $dateFin);
                });
            }
            // Application du filtre de statut de paiement
            if ($statutPaiement) {
                $query->whereHas('ventesClient', function ($query) use ($statutPaiement) {
                    $query->where('statut_paiement', $statutPaiement);
                })
                    ->withCount([
                        'ventesClient as ventes_total' => function ($query) use ($statutPaiement) {
                            $query->where('statut_paiement', $statutPaiement);
                        }
                    ])
                ;
            }


            $clients = $query->get();

            // dd($clients->toArray());

            return view('backend.pages.auth-client.index', compact('clients'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function clientVenteImpaye(Request $request)
    {
        try {

            // Récupération des utilisateurs ayant le rôle 'client'
            // $query = User::withCount('ventesClient as ventes_total')
            //     ->withCount([
            //         'ventesClient as ventes_paye'
            //         => function ($query) {
            //             $query->where('statut_paiement', '=', 'paye');
            //         },
            //         'ventesClient as ventes_impaye'
            //         => function ($query) {
            //             $query->where('statut_paiement', '=', 'impaye');
            //         }
            //     ])
            //     ->whereHas('roles', function ($query) {
            //         $query->where('name', 'client');
            //     })->with('ventesClient');

            $query = User::whereHas('roles', function ($query) {
                $query->where('name', 'client');
            })
                ->whereHas('ventesClient', function ($query) {
                    $query->where('statut_paiement', 'impaye');
                        // ->where('statut_cloture', true);
                })
                ->with(['ventesClient' => function ($query) {
                    $query->where('statut_paiement', 'impaye');
                }]);


            // request des filtres
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            $statutPaiement = $request->input('statut_paiement'); // paye ou impaye



            // Formatage des dates
            $dateDebut = $request->filled('date_debut') ? Carbon::parse($dateDebut)->format('Y-m-d') : null;
            $dateFin = $request->filled('date_fin') ? Carbon::parse($dateFin)->format('Y-m-d') : null;

            // Application des filtres de date
            if ($dateDebut && $dateFin) {
                $query->whereHas('ventesClient', function ($query) use ($dateDebut, $dateFin) {
                    $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
                });
            } elseif ($dateDebut) {
                $query->whereHas('ventesClient', function ($query) use ($dateDebut) {
                    $query->where('date_vente', '>=', $dateDebut);
                });
            } elseif ($dateFin) {
                $query->whereHas('ventesClient', function ($query) use ($dateFin) {
                    $query->where('date_vente', '<=', $dateFin);
                });
            }
            // Application du filtre de statut de paiement
            if ($statutPaiement) {
                $query->whereHas('ventesClient', function ($query) use ($statutPaiement) {
                    $query->where('statut_paiement', $statutPaiement);
                })
                    ->withCount([
                        'ventesClient as ventes_total' => function ($query) use ($statutPaiement) {
                            $query->where('statut_paiement', $statutPaiement);
                        }
                    ])
                ;
            }


            $clients = $query->get();

            // dd($clients->toArray());

            return view('backend.pages.auth-client.vente_impaye', compact('clients'));
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
