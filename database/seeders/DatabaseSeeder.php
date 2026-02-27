<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Station;
use App\Models\Carburant;
use App\Models\User;
use App\Models\Cuve;
use App\Models\Pompe;
use App\Models\Pistolet;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer les carburants
        $super91 = Carburant::create([
            'nom' => 'Super 91',
            'code' => 'S91',
            'prix_unitaire' => 625,
            'description' => 'Essence Super 91'
        ]);

        $gazoil = Carburant::create([
            'nom' => 'Gazoil',
            'code' => 'GAZ',
            'prix_unitaire' => 590,
            'description' => 'Gazoil/Diesel'
        ]);

        // Créer une station
        $station1 = Station::create([
            'nom' => 'Station Principale',
            'adresse' => '123 Avenue de la République',
            'telephone' => '+229 96 00 00 00',
            'email' => 'contact@stationprincipale.bj',
            'ville' => 'Cotonou',
            'responsable' => 'Jean KOUASSI'
        ]);

        $station2 = Station::create([
            'nom' => 'Station Nord',
            'adresse' => '456 Route de Bohicon',
            'telephone' => '+229 97 00 00 00',
            'email' => 'contact@stationnord.bj',
            'ville' => 'Abomey-Calavi',
            'responsable' => 'Marie DOSSOU'
        ]);

        // Créer un administrateur
        $admin = User::create([
            'station_id' => $station1->id,
            'nom' => 'ADMIN',
            'prenom' => 'Super',
            'telephone' => '+229 96 11 11 11',
            'login' => 'admin',
            'email' => 'admin@stationmanager.bj',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'statut' => 'actif'
        ]);

        // Créer un gestionnaire
        $gestionnaire = User::create([
            'station_id' => $station1->id,
            'nom' => 'GESTION',
            'prenom' => 'Manager',
            'telephone' => '+229 96 22 22 22',
            'login' => 'gestionnaire',
            'email' => 'gestionnaire@stationmanager.bj',
            'password' => Hash::make('password'),
            'role' => 'gestionnaire',
            'statut' => 'actif'
        ]);

        // Créer des pompistes
        $pompiste1 = User::create([
            'station_id' => $station1->id,
            'nom' => 'BOKO',
            'prenom' => 'Pierre',
            'telephone' => '+229 96 33 33 33',
            'login' => 'pompiste1',
            'email' => 'pompiste1@stationmanager.bj',
            'password' => Hash::make('password'),
            'role' => 'pompiste',
            'statut' => 'actif'
        ]);

        $pompiste2 = User::create([
            'station_id' => $station1->id,
            'nom' => 'AGBODJI',
            'prenom' => 'Sophie',
            'telephone' => '+229 96 44 44 44',
            'login' => 'pompiste2',
            'email' => 'pompiste2@stationmanager.bj',
            'password' => Hash::make('password'),
            'role' => 'pompiste',
            'statut' => 'actif'
        ]);

        // Créer des cuves pour la station 1
        $cuve1 = Cuve::create([
            'station_id' => $station1->id,
            'carburant_id' => $super91->id,
            'nom' => 'Cuve Super 91 - A',
            'capacite_max' => 50000,
            'stock_actuel' => 35000,
            'stock_min' => 5000,
            'numero_serie' => 'CUV-S91-001'
        ]);

        $cuve2 = Cuve::create([
            'station_id' => $station1->id,
            'carburant_id' => $gazoil->id,
            'nom' => 'Cuve Gazoil - B',
            'capacite_max' => 60000,
            'stock_actuel' => 42000,
            'stock_min' => 8000,
            'numero_serie' => 'CUV-GAZ-001'
        ]);

        $cuve3 = Cuve::create([
            'station_id' => $station1->id,
            'carburant_id' => $super91->id,
            'nom' => 'Cuve Super 91 - C',
            'capacite_max' => 50000,
            'stock_actuel' => 4500, // En alerte
            'stock_min' => 5000,
            'numero_serie' => 'CUV-S91-002'
        ]);

        // Créer des pompes pour les cuves
        $pompe1 = Pompe::create([
            'cuve_id' => $cuve1->id,
            'nom' => 'Pompe 1',
            'numero_serie' => 'PMP-001',
            'etat' => 'actif'
        ]);

        $pompe2 = Pompe::create([
            'cuve_id' => $cuve2->id,
            'nom' => 'Pompe 2',
            'numero_serie' => 'PMP-002',
            'etat' => 'actif'
        ]);

        $pompe3 = Pompe::create([
            'cuve_id' => $cuve1->id,
            'nom' => 'Pompe 3',
            'numero_serie' => 'PMP-003',
            'etat' => 'actif'
        ]);

        // Créer des pistolets
        Pistolet::create([
            'pompe_id' => $pompe1->id,
            'pompiste_id' => $pompiste1->id,
            'nom' => 'Pistolet 1A',
            'numero' => 'PST-001',
            'etat' => 'actif'
        ]);

        Pistolet::create([
            'pompe_id' => $pompe1->id,
            'pompiste_id' => $pompiste2->id,
            'nom' => 'Pistolet 1B',
            'numero' => 'PST-002',
            'etat' => 'actif'
        ]);

        Pistolet::create([
            'pompe_id' => $pompe2->id,
            'pompiste_id' => $pompiste1->id,
            'nom' => 'Pistolet 2A',
            'numero' => 'PST-003',
            'etat' => 'actif'
        ]);

        Pistolet::create([
            'pompe_id' => $pompe3->id,
            'pompiste_id' => $pompiste2->id,
            'nom' => 'Pistolet 3A',
            'numero' => 'PST-004',
            'etat' => 'actif'
        ]);

        $this->command->info('✓ Base de données remplie avec succès!');
        $this->command->info('');
        $this->command->info('Comptes créés:');
        $this->command->info('Admin: admin / password');
        $this->command->info('Gestionnaire: gestionnaire / password');
        $this->command->info('Pompiste 1: pompiste1 / password');
        $this->command->info('Pompiste 2: pompiste2 / password');
    }
}
