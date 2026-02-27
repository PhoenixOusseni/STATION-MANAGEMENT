# 🔗 Relations entre les Modèles - Station Manager

## Vue d'ensemble des Relations

### 📊 Diagramme Relationnel Complet

```
                                    ┌──────────────┐
                                    │  Carburant   │
                                    │━━━━━━━━━━━━━━│
                                    │ id           │
                                    │ nom          │
                                    │ code         │
                                    │ prix_unitaire│
                                    └──────┬───────┘
                                           │
                         ┌─────────────────┴─────────────────┐
                         │                                   │
                         ▼                                   ▼
              ┌──────────────────┐              ┌──────────────────┐
              │      Cuve        │              │    Commande      │
              │━━━━━━━━━━━━━━━━━━│              │━━━━━━━━━━━━━━━━━━│
              │ id               │              │ id               │
              │ station_id  (FK) │              │ station_id  (FK) │
              │ carburant_id(FK) │              │ carburant_id(FK) │
              │ capacite_max     │              │ user_id     (FK) │
              │ stock_actuel     │              │ quantite         │
              │ stock_min        │              │ statut           │
              └────┬─────────────┘              └────┬─────────────┘
                   │                                 │
                   ├─────────────┐                   │
                   │             │                   │
                   ▼             ▼                   ▼
           ┌───────────┐  ┌──────────┐       ┌──────────────┐
           │   Pompe   │  │  Entree  │       │   Entree     │
           │━━━━━━━━━━━│  │━━━━━━━━━━│       │━━━━━━━━━━━━━━│
           │ id        │  │ id       │       │ commande_id  │
           │ cuve_id(FK)│  │ cuve_id(FK)│     │              │
           │ nom       │  │ user_id(FK)│     └──────────────┘
           │ etat      │  │ quantite │
           └────┬──────┘  └──────────┘
                │
                ▼
         ┌──────────────┐
         │  Pistolet    │
         │━━━━━━━━━━━━━━│
         │ id           │
         │ pompe_id  (FK)│
         │ pompiste_id(FK)│
         │ numero       │
         └──────┬───────┘
                │
                ▼
         ┌──────────────┐
         │    Vente     │
         │━━━━━━━━━━━━━━│
         │ id           │
         │ station_id(FK)│
         │ pistolet_id(FK)│
         │ pompiste_id(FK)│
         │ quantite     │
         │ montant_total│
         └──────────────┘


┌──────────────┐
│   Station    │
│━━━━━━━━━━━━━━│
│ id           │
│ nom          │
│ adresse      │
│ telephone    │
└──────┬───────┘
       │
       ├────────────┬─────────────┬──────────────┐
       ▼            ▼             ▼              ▼
   ┌──────┐    ┌──────┐     ┌─────────┐   ┌──────┐
   │ User │    │ Cuve │     │Commande │   │Vente │
   └──────┘    └──────┘     └─────────┘   └──────┘
```

## 🔗 Relations Détaillées

### 1. Station

**Relations hasMany (Une Station a plusieurs...)**
```php
- users()          → User          // Utilisateurs de la station
- cuves()          → Cuve          // Réservoirs de la station
- commandes()      → Commande      // Commandes passées par la station
- ventes()         → Vente         // Ventes effectuées dans la station
```

**Exemple d'utilisation:**
```php
$station = Station::find(1);
$station->users;        // Tous les utilisateurs
$station->cuves;        // Toutes les cuves
$station->commandes;    // Toutes les commandes
```

---

### 2. Carburant

**Relations hasMany (Un Carburant peut être dans plusieurs...)**
```php
- cuves()          → Cuve          // Cuves contenant ce carburant
- commandes()      → Commande      // Commandes de ce carburant
```

**Exemple d'utilisation:**
```php
$carburant = Carburant::where('code', 'S91')->first();
$carburant->cuves;      // Toutes les cuves de Super 91
$carburant->commandes;  // Toutes les commandes de Super 91
```

---

### 3. User (Utilisateur)

**Relations belongsTo (Un User appartient à...)**
```php
- station()        → Station       // La station de l'utilisateur
```

**Relations hasMany (Un User a plusieurs...)**
```php
- pistolets()      → Pistolet      // Pistolets gérés (pompistes)
- ventes()         → Vente         // Ventes effectuées (pompistes)
- commandes()      → Commande      // Commandes passées
- entrees()        → Entree        // Entrées enregistrées
```

**Méthodes utiles:**
```php
- isAdmin()            // Vérifie si admin
- isGestionnaire()     // Vérifie si gestionnaire
- isPompiste()         // Vérifie si pompiste
```

**Exemple d'utilisation:**
```php
$user = auth()->user();
if ($user->isPompiste()) {
    $ventes = $user->ventes;           // Ses ventes
    $pistolets = $user->pistolets;     // Ses pistolets
}
```

---

### 4. Cuve

**Relations belongsTo (Une Cuve appartient à...)**
```php
- station()        → Station       // Station propriétaire
- carburant()      → Carburant     // Type de carburant contenu
```

**Relations hasMany (Une Cuve a plusieurs...)**
```php
- pompes()         → Pompe         // Pompes reliées à la cuve
- entrees()        → Entree        // Entrées de carburant
```

**Méthodes utiles:**
```php
- isStockAlerte()         // true si stock <= stock_min
- pourcentageRemplissage() // % de remplissage
```

**Exemple d'utilisation:**
```php
$cuve = Cuve::find(1);
if ($cuve->isStockAlerte()) {
    // Déclencher une alerte
}
$niveau = $cuve->pourcentageRemplissage(); // 75%
```

---

### 5. Pompe

**Relations belongsTo (Une Pompe appartient à...)**
```php
- cuve()           → Cuve          // Cuve reliée
```

**Relations hasMany (Une Pompe a plusieurs...)**
```php
- pistolets()      → Pistolet      // Pistolets de la pompe
```

**Exemple d'utilisation:**
```php
$pompe = Pompe::find(1);
$carburant = $pompe->cuve->carburant->nom;  // "Super 91"
$pistolets = $pompe->pistolets;              // Tous les pistolets
```

---

### 6. Pistolet

**Relations belongsTo (Un Pistolet appartient à...)**
```php
- pompe()          → Pompe         // Pompe reliée
- pompiste()       → User          // Pompiste assigné
```

**Relations hasMany (Un Pistolet a plusieurs...)**
```php
- ventes()         → Vente         // Ventes effectuées via ce pistolet
```

**Exemple d'utilisation:**
```php
$pistolet = Pistolet::find(1);
$carburant = $pistolet->pompe->cuve->carburant;  // Navigation profonde
$pompiste = $pistolet->pompiste;                  // User pompiste
```

---

### 7. Commande

**Relations belongsTo (Une Commande appartient à...)**
```php
- station()        → Station       // Station commanditaire
- carburant()      → Carburant     // Carburant commandé
- user()           → User          // Utilisateur qui a passé la commande
```

**Relations hasOne (Une Commande a une...)**
```php
- entree()         → Entree        // Entrée/réception associée
```

**Exemple d'utilisation:**
```php
$commande = Commande::find(1);
if ($commande->statut == 'validee' && !$commande->entree) {
    // Commande prête à être réceptionnée
}
```

---

### 8. Entree

**Relations belongsTo (Une Entrée appartient à...)**
```php
- cuve()           → Cuve          // Cuve de destination
- commande()       → Commande      // Commande associée (optionnel)
- user()           → User          // Utilisateur qui a enregistré
```

**Comportement:**
- Incrémente automatiquement le stock de la cuve
- Met à jour le statut de la commande à "livree"

**Exemple d'utilisation:**
```php
$entree = Entree::create([...]);
// Le stock de la cuve est automatiquement mis à jour
```

---

### 9. Vente

**Relations belongsTo (Une Vente appartient à...)**
```php
- station()        → Station       // Station de vente
- pistolet()       → Pistolet      // Pistolet utilisé
- pompiste()       → User          // Pompiste ayant effectué la vente
```

**Comportement:**
- Décrémente automatiquement le stock de la cuve
- Vérifie la disponibilité du stock avant validation

**Exemple d'utilisation:**
```php
$vente = Vente::with(['pistolet.pompe.cuve.carburant', 'pompiste'])->find(1);
$carburant = $vente->pistolet->pompe->cuve->carburant->nom;
```

---

## 🎯 Requêtes Complexes Exemples

### Exemple 1: Trouver toutes les cuves en alerte d'une station
```php
$cuvesAlerte = Cuve::where('station_id', $stationId)
    ->whereRaw('stock_actuel <= stock_min')
    ->with('carburant')
    ->get();
```

### Exemple 2: Calculer le total des ventes d'un pompiste
```php
$totalVentes = Vente::where('pompiste_id', $pompisteId)
    ->whereDate('date_vente', today())
    ->sum('montant_total');
```

### Exemple 3: Trouver les commandes non livrées
```php
$commandesEnAttente = Commande::where('statut', 'validee')
    ->whereDoesntHave('entree')
    ->with(['carburant', 'station'])
    ->get();
```

### Exemple 4: Historique complet d'une cuve
```php
$cuve = Cuve::with([
    'carburant',
    'pompes.pistolets.ventes',
    'entrees'
])->find($cuveId);

$totalEntrees = $cuve->entrees->sum('quantite');
$totalSorties = $cuve->pompes->flatMap->pistolets
    ->flatMap->ventes->sum('quantite');
```

### Exemple 5: Statistiques d'une station
```php
$stats = [
    'total_cuves' => $station->cuves()->count(),
    'stock_total' => $station->cuves()->sum('stock_actuel'),
    'ventes_mois' => $station->ventes()
        ->whereMonth('date_vente', now()->month)
        ->sum('montant_total'),
    'commandes_attente' => $station->commandes()
        ->where('statut', 'en_attente')
        ->count()
];
```

---

## 📚 Chargement Eager Loading

Pour optimiser les performances, toujours utiliser `with()`:

```php
// ❌ Mauvais (N+1 problème)
$ventes = Vente::all();
foreach ($ventes as $vente) {
    echo $vente->pompiste->nom;  // Requête à chaque itération
}

// ✅ Bon (Une seule requête)
$ventes = Vente::with('pompiste')->get();
foreach ($ventes as $vente) {
    echo $vente->pompiste->nom;
}

// ✅ Excellent (Relations imbriquées)
$ventes = Vente::with([
    'pompiste',
    'pistolet.pompe.cuve.carburant',
    'station'
])->get();
```

---

## 🔄 Cascade et Contraintes

### Suppression en Cascade (onDelete('cascade'))
- Supprimer une Station → Supprime ses Users, Cuves, Commandes, Ventes
- Supprimer une Cuve → Supprime ses Pompes, Entrées
- Supprimer une Pompe → Supprime ses Pistolets

### Restriction (onDelete('restrict'))
- Ne peut pas supprimer un Carburant utilisé dans des Cuves
- Ne peut pas supprimer un User ayant des Commandes

### Null (onDelete('set null'))
- Supprimer une Commande → Met commande_id à null dans Entree
- Supprimer un Pompiste → Met pompiste_id à null dans Pistolet

---

Cette documentation vous permet de comprendre toutes les relations du système! 🎉
