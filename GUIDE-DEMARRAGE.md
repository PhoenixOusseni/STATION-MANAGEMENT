# 🚀 Guide de Démarrage Rapide - Station Manager

## Installation Express

### 1. Configuration de la Base de Données

```bash
# Dans MySQL
CREATE DATABASE station_manager;
```

### 2. Configuration Laravel

```bash
# Dans le terminal à la racine du projet
composer install
cp .env.example .env
php artisan key:generate
```

### 3. Configurer .env

```env
DB_DATABASE=station_manager
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrer et Remplir la Base

```bash
php artisan migrate
php artisan db:seed
```

### 5. Lancer l'Application

```bash
php artisan serve
```

Ouvrir: http://localhost:8000

## 🔑 Comptes par Défaut

```
Admin:
  Login: admin
  Mot de passe: password

Gestionnaire:
  Login: gestionnaire
  Mot de passe: password

Pompiste:
  Login: pompiste1
  Mot de passe: password
```

## 📊 Diagramme des Relations

```
┌─────────────┐
│  STATIONS   │
└──────┬──────┘
       │
       ├──────────┐
       │          │
       ▼          ▼
┌──────────┐  ┌───────────┐
│  USERS   │  │   CUVES   │
└────┬─────┘  └─────┬─────┘
     │              │
     │         ┌────┴────┐
     │         ▼         │
     │    ┌────────┐     │
     │    │ POMPES │     │
     │    └────┬───┘     │
     │         │         │
     │    ┌────▼────┐    │
     │    │PISTOLETS│    │
     │    └────┬────┘    │
     │         │         │
     └────┐    │    ┌────┘
          │    │    │
          ▼    ▼    ▼
       ┌─────────────┐
       │   VENTES    │
       └─────────────┘

┌──────────────┐
│ CARBURANTS   │
└──────┬───────┘
       │
   ┌───┴────┐
   ▼        ▼
┌─────┐  ┌──────┐
│CUVES│  │COMMANDES│
└─────┘  └────┬─────┘
              │
              ▼
         ┌─────────┐
         │ ENTREES │
         └─────────┘
```

## 🎯 Flux de Travail Type

### 1. Configuration Initiale (Admin)
1. Créer les stations
2. Créer les types de carburant (Super 91, Gazoil)
3. Créer les cuves pour chaque station
4. Créer les pompes reliées aux cuves
5. Créer les pistolets sur les pompes
6. Créer les utilisateurs (gestionnaires, pompistes)
7. Assigner les pompistes aux pistolets

### 2. Commande de Carburant (Gestionnaire)
1. Aller dans "Commandes" → "Nouvelle Commande"
2. Sélectionner le carburant et la quantité
3. Remplir les informations fournisseur
4. Valider la commande

### 3. Réception de Carburant (Gestionnaire)
1. Aller dans "Entrées" → "Nouvelle Entrée"
2. Sélectionner la cuve de destination
3. Sélectionner la commande (optionnel)
4. Saisir la quantité reçue
5. Le stock de la cuve est automatiquement mis à jour

### 4. Vente de Carburant (Pompiste)
1. Aller dans "Ventes" → "Nouvelle Vente"
2. Sélectionner son pistolet
3. Saisir la quantité vendue
4. Choisir le mode de paiement
5. Le stock de la cuve est automatiquement décrémenté

### 5. Suivi (Tous)
- Consulter le dashboard pour les statistiques
- Vérifier les alertes de stock
- Consulter l'historique des ventes/entrées

## 📋 Checklist de Mise en Production

- [ ] Configurer les variables d'environnement de production
- [ ] Changer `APP_ENV=production` dans .env
- [ ] Changer `APP_DEBUG=false` dans .env
- [ ] Générer une nouvelle clé d'application
- [ ] Configurer la base de données de production
- [ ] Exécuter les migrations
- [ ] Créer le compte administrateur
- [ ] Configurer les sauvegardes automatiques
- [ ] Configurer le serveur web (Apache/Nginx)
- [ ] Activer HTTPS
- [ ] Tester toutes les fonctionnalités

## 🔧 Commandes Utiles

```bash
# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Réinitialiser la base de données
php artisan migrate:fresh --seed

# Créer un nouveau contrôleur
php artisan make:controller NomController

# Créer un nouveau modèle avec migration
php artisan make:model NomModele -m

# Lister toutes les routes
php artisan route:list

# Créer un seeder
php artisan make:seeder NomSeeder
```

## 🐛 Dépannage

### Erreur "Class not found"
```bash
composer dump-autoload
```

### Erreur de permissions (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

### Erreur de migration
```bash
php artisan migrate:fresh
php artisan db:seed
```

## 📱 Structure des Dossiers Importantes

```
STATION-MANAGER/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Logique métier
│   │   └── Middleware/       # Middlewares
│   └── Models/               # Modèles Eloquent
├── database/
│   ├── migrations/           # Migrations DB
│   └── seeders/              # Données de test
├── resources/
│   └── views/                # Templates Blade
└── routes/
    └── web.php               # Routes web
```

## 💡 Conseils

1. **Toujours tester sur des données de test** avant de passer en production
2. **Créer des sauvegardes régulières** de la base de données
3. **Documenter les modifications** personnalisées
4. **Utiliser le seeder** pour créer rapidement des données de test
5. **Vérifier les logs** en cas d'erreur: `storage/logs/laravel.log`

---
Bon développement ! 🎉
