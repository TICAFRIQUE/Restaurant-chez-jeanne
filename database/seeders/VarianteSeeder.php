<?php

namespace Database\Seeders;

use App\Models\Variante;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VarianteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  public function run()
        {
            // Ajout de variantes avec des prix par défaut si souhaité
            // Ajouter les variantes si elles n'existent pas déjà
            // Variante::firstOrCreate(['libelle' => 'Tournée'], ['slug' => 'tournée']);
            Variante::firstOrCreate(['libelle' => 'Ballon'], ['slug' => 'ballon']);
            Variante::firstOrCreate(['libelle' => 'Verre'], ['slug' => 'verre']);
            Variante::firstOrCreate(['libelle' => 'Bouteille'], ['slug' => 'bouteille']);
            Variante::firstOrCreate(['libelle' => 'Demi'], ['slug' => 'demi']);
        }
    }
}
