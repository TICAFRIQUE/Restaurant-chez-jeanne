<?php


if (!function_exists('afficherStockConvertirAvecVariantes')) {
    function afficherStockConvertirAvecVariantes(
        $valeur,
        $unite = '',
        $variantes = null,
    ) {
        $signe = $valeur < 0 ? '-' : '';
        $valeur = abs($valeur);

        $entier = floor($valeur); // bouteilles pleines
        $decimal = $valeur - $entier; // partie décimale
        $texte = '';

        if ($decimal == 0) {
            if ($entier > 0) {
                $texte .= $entier . ' ' . $unite;
            }
        } else {
            if ($entier > 0) {
                $texte .= $entier . ' ' . $unite;
            }

            if ($variantes) {
                $verre = $variantes->where('libelle', 'Verre')->first();
                $ballon = $variantes->where('libelle', 'Ballon')->first();

                if ($verre && ($verre->pivot->quantite ?? 0) > 0) {
                    $verres_par_bouteille = $verre->pivot->quantite;
                    $verres = round($decimal * $verres_par_bouteille, 2);
                    if (floor($verres) > 0) {
                        $texte .=
                            ($texte ? ' et ' : '') . floor($verres) . ' verre(s)';
                    }
                } elseif ($ballon && ($ballon->pivot->quantite ?? 0) > 0) {
                    $ballons_par_bouteille = $ballon->pivot->quantite;
                    $ballons = round($decimal * $ballons_par_bouteille, 2);
                    if (floor($ballons) > 0) {
                        $texte .=
                            ($texte ? ' et ' : '') . floor($ballons) . ' ballon(s)';
                    }
                }
            }
        }

        return $texte ? $signe . $texte : '0';
    }
}




 //  version simplifiée
// if (!function_exists('afficherStockConvertirAvecVariantes')) {
//     function afficherStockConvertirAvecVariantes($valeur, $unite = '', $variantes = null) {
//         $signe = $valeur < 0 ? '-' : '';
//         $valeur = abs($valeur);
//         $entier = floor($valeur);
//         $decimal = $valeur - $entier;
//         $texte = '';

//         if ($entier > 0) {
//             $texte .= $entier . ' ' . $unite;
//         }

//         if ($decimal > 0 && $variantes) {
//             $verre = $variantes->where('libelle', 'Verre')->first();
//             $ballon = $variantes->where('libelle', 'Ballon')->first();
//             $quantite = $verre ? round($decimal * $verre->pivot->quantite, 2) : round($decimal * $ballon->pivot->quantite, 2);
//             $type = $verre ? 'verre(s)' : 'ballon(s)';

//             if (floor($quantite) > 0) {
//                 $texte .= ($texte ? ' et ' : '') . floor($quantite) . ' ' . $type;
//             }
//         }

//         return $signe . $texte ?: '0';
//     }
// }
