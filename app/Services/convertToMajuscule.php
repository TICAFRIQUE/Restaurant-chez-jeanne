<?php

namespace App\Services;

class convertToMajuscule
{
    public static function toUpperNoAccent($string)
    {
        // Mettre en majuscule UTF-8
        $string = mb_strtoupper($string, 'UTF-8');

        // Tableau de correspondance des accents → lettres sans accent
        $accents = [
            'À' => 'A',
            'Â' => 'A',
            'Ä' => 'A',
            'Á' => 'A',
            'Ã' => 'A',
            'Å' => 'A',
            'Ç' => 'C',
            'É' => 'E',
            'È' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Í' => 'I',
            'Ì' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ñ' => 'N',
            'Ó' => 'O',
            'Ò' => 'O',
            'Ô' => 'O',
            'Ö' => 'O',
            'Õ' => 'O',
            'Ú' => 'U',
            'Ù' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'Ý' => 'Y'
        ];

        // Remplacer les lettres accentuées
        $string = strtr($string, $accents);

        return $string;
    }
}
