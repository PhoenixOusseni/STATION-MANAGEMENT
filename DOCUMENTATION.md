# 📋 STATION MANAGER - Documentation du Projet

## 🎯 Présentation

**Station Manager** est une plateforme complète de gestion et de suivi du carburant pour les stations-service. Elle permet de gérer les entrées et sorties de carburant, faciliter les commandes et suivre les ventes en temps réel.

## 🏗️ Architecture du Projet

### Modèles de Données

#### 1. **Station**
Représente une station-service.
- Champs: nom, adresse, téléphone, email, ville, responsable
- Relations: 
  - Plusieurs utilisateurs (users)
  - Plusieurs cuves
  - Plusieurs commandes
  - Plusieurs ventes

#### 2. **Carburant**
Types de carburant disponibles (Super 91, Gazoil).
- Champs: nom, code, prix_unitaire, description
- Relations:
  - Plusieurs cuves
  - Plusieurs commandes

#### 3. **User (Utilisateur)**
Gestion des utilisateurs avec 3 rôles:
- **Admin**: Accès complet au système
- **Gestionnaire**: Gestion de sa station
- **Pompiste**: Enregistrement des ventes

- Champs: nom, prenom, telephone, login, email, password, role, statut, station_id
- Relations:
  - Appartient à une station
  - Plusieurs pistolets (pour pompistes)
  - Plusieurs ventes effectuées
  - Plusieurs commandes passées
  - Plusieurs entrées enregistrées

#### 4. **Cuve**
Réservoirs de stockage de carburant.
- Champs: nom, capacite_max, stock_actuel, stock_min, numero_serie
- Relations:
  - Appartient à une station
  - Contient un type de carburant
  - Plusieurs pompes
  - Plusieurs entrées
- Méthodes:
  - `isStockAlerte()`: Vérifie si le stock est sous le seuil minimum
  - `pourcentageRemplissage()`: Calcule le % de remplissage

#### 5. **Pompe**
Pompes reliées aux cuves.
- Champs: nom, numero_serie, etat, date_maintenance
- Relations:
  - Reliée à une cuve
  - Plusieurs pistolets

#### 6. **Pistolet**
Pistolets de distribution gérés par les pompistes.
- Champs: nom, numero, etat
- Relations:
  - Relié à une pompe
  - Géré par un pompiste (User)
  - Plusieurs ventes

#### 7. **Commande**
Commandes de carburant auprès des fournisseurs.
- Champs: numero_commande, quantite, prix_unitaire, montant_total, fournisseur, statut, date_commande, date_livraison_prevue
- Statuts: en_attente, validee, livree, annulee
- Relations:
  - Appartient à une station
  - Concerne un carburant
  - Passée par un utilisateur
  - Peut avoir une entrée (réception)

#### 8. **Entree**
Réceptions de carburant (livraisons).
- Champs: numero_entree, quantite, prix_unitaire, montant_total, date_entree, numero_bon_livraison, observation
- Relations:
  - Liée à une cuve (met à jour le stock)
  - Peut être liée à une commande
  - Enregistrée par un utilisateur

#### 9. **Vente**
Ventes de carburant aux clients.
- Champs: numero_vente, quantite, prix_unitaire, montant_total, mode_paiement, date_vente, client, observation
- Modes de paiement: especes, carte, mobile_money, credit
- Relations:
  - Appartient à une station
  - Effectuée via un pistolet
  - Effectuée par un pompiste

### Contrôleurs

#### 1. **DashboardController**
- `index()`: Affiche le tableau de bord avec statistiques, alertes de stock, dernières ventes/entrées

#### 2. **StationController** (Admin uniquement)
- CRUD complet pour la gestion des stations

#### 3. **CarburantController**
- CRUD complet pour la gestion des types de carburant

#### 4. **CuveController**
- CRUD complet pour la gestion des cuves
- Filtre par station selon le rôle de l'utilisateur

#### 5. **CommandeController**
- CRUD complet pour la gestion des commandes
- `updateStatut()`: Mise à jour du statut d'une commande

#### 6. **EntreeController**
- Création et suppression d'entrées (pas de modification)
- Met à jour automatiquement le stock de la cuve
- Met à jour le statut de la commande associée

#### 7. **VenteController**
- Création et suppression de ventes (pas de modification)
- Vérifie le stock disponible avant vente
- Met à jour automatiquement le stock de la cuve
- Filtre par pompiste selon le rôle

#### 8. **UserController** (Admin et Gestionnaire)
- CRUD complet pour la gestion des utilisateurs

### Vues (Blade Templates)

#### Layouts
- `layouts/app.blade.php`: Layout principal avec navigation

#### Pages principales
- `dashboard.blade.php`: Tableau de bord
- `cuves/index.blade.php`: Liste des cuves avec niveaux de stock
- `ventes/index.blade.php`: Liste des ventes avec filtres
- `commandes/index.blade.php`: Liste des commandes avec statuts
- `entrees/index.blade.php`: Liste des entrées/réceptions

### Routes

```php
// Routes publiques
GET  / → Page de connexion
POST /connexion → Authentification

// Routes protégées (authentification requise)
GET  /dashboard → Tableau de bord

// Routes Admin uniquement
RESOURCE /stations → Gestion des stations

// Routes accessibles selon les rôles
RESOURCE /carburants → Gestion des carburants
RESOURCE /cuves → Gestion des cuves
RESOURCE /commandes → Gestion des commandes
RESOURCE /entrees → Gestion des entrées
RESOURCE /ventes → Gestion des ventes

// Routes Admin et Gestionnaire
RESOURCE /users → Gestion des utilisateurs
```

### Middlewares

1. **AdminMiddleware**: Accès réservé aux administrateurs
2. **AdminGestionnaireMiddleware**: Accès aux admins et gestionnaires

## 🚀 Installation

### Prérequis
- PHP 8.1+
- Composer
- MySQL/MariaDB
- XAMPP (ou équivalent)

### Étapes d'installation

1. **Cloner/Copier le projet**
```bash
cd c:\xampp\htdocs\STATION-MANAGER
```

2. **Installer les dépendances**
```bash
composer install
npm install
```

3. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurer la base de données** dans `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=station_manager
DB_USERNAME=root
DB_PASSWORD=
```

5. **Créer la base de données**
```sql
CREATE DATABASE station_manager;
```

6. **Exécuter les migrations**
```bash
php artisan migrate
```

7. **Remplir la base de données avec des données de test**
```bash
php artisan db:seed
```

8. **Compiler les assets**
```bash
npm run dev
```

9. **Lancer le serveur**
```bash
php artisan serve
```

Accédez à l'application: http://localhost:8000

## 👥 Comptes de Test

Après l'exécution du seeder, vous disposez de ces comptes:

| Rôle | Login | Mot de passe |
|------|-------|--------------|
| Admin | admin | password |
| Gestionnaire | gestionnaire | password |
| Pompiste 1 | pompiste1 | password |
| Pompiste 2 | pompiste2 | password |

## 📊 Schéma de la Base de Données

```
stations (id, nom, adresse, telephone, email, ville, responsable)
    ↓
users (id, station_id, nom, prenom, role, statut, login, email, password)
    ↓
cuves (id, station_id, carburant_id, capacite_max, stock_actuel, stock_min)
    ↓
pompes (id, cuve_id, nom, numero_serie, etat)
    ↓
pistolets (id, pompe_id, pompiste_id, nom, numero, etat)
    ↓
ventes (id, station_id, pistolet_id, pompiste_id, quantite, montant_total)

carburants (id, nom, code, prix_unitaire)

commandes (id, station_id, carburant_id, user_id, quantite, montant_total, statut)
    ↓
entrees (id, cuve_id, commande_id, user_id, quantite, montant_total)
```

## 🔒 Gestion des Permissions

### Admin
- Accès complet à toutes les fonctionnalités
- Gestion de toutes les stations
- Création/modification/suppression de tous les éléments

### Gestionnaire
- Gestion de sa propre station
- Gestion des utilisateurs de sa station
- Gestion des cuves, commandes, entrées, ventes
- Consultation des statistiques

### Pompiste
- Enregistrement des ventes
- Consultation de ses propres ventes
- Gestion de ses pistolets assignés

## 📈 Fonctionnalités Principales

### 1. Dashboard
- Statistiques en temps réel
- Alertes de stock minimum
- Dernières ventes et entrées
- Commandes en attente

### 2. Gestion des Stocks
- Suivi en temps réel du niveau des cuves
- Alertes automatiques de stock minimum
- Historique des entrées et sorties

### 3. Gestion des Commandes
- Création de commandes fournisseurs
- Suivi des statuts (en attente, validée, livrée, annulée)
- Association automatique avec les entrées

### 4. Gestion des Ventes
- Enregistrement rapide des ventes
- Différents modes de paiement
- Mise à jour automatique des stocks
- Filtres et statistiques

### 5. Gestion des Utilisateurs
- Création de comptes avec rôles
- Attribution des pompistes aux pistolets
- Gestion des permissions

## 🛠️ Technologies Utilisées

- **Framework**: Laravel 11
- **Base de données**: MySQL
- **Frontend**: Blade Templates, Bootstrap 5
- **Authentification**: Laravel Auth
- **Icons**: Font Awesome

## 📝 Notes Importantes

1. **Gestion des Stocks**: Les stocks sont mis à jour automatiquement lors des entrées et ventes
2. **Numérotation Automatique**: Les numéros de commande, entrée et vente sont générés automatiquement
3. **Validation**: Toutes les entrées sont validées côté serveur
4. **Transactions**: Les opérations critiques utilisent des transactions DB pour garantir l'intégrité

## 🔄 Prochaines Améliorations

- Génération de rapports PDF
- Graphiques de statistiques avancées
- Module de facturation
- API REST pour application mobile
- Système de notifications en temps réel
- Export Excel des données

## 📞 Support

Pour toute question ou assistance, consultez la documentation Laravel: https://laravel.com/docs

---
**Version**: 1.0.0  
**Date**: 2026  
**Développé avec**: Laravel 11
