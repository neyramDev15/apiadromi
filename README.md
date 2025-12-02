# 🍕 API Adromi - Système de Gestion de Restaurant

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/MySQL-Database-orange?style=for-the-badge&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/API-REST-green?style=for-the-badge" alt="REST API">
</p>

## 📋 Description

**API Adromi** est une API REST complète pour la gestion d'un restaurant/service de livraison. Elle permet aux clients de parcourir un menu, gérer leur panier, passer des commandes et effectuer des paiements.

## ✨ Fonctionnalités

- 👥 **Gestion des utilisateurs** (clients et administrateurs)
- 📂 **Catégories de menus** organisées
- 🍕 **Catalogue de plats** avec prix et descriptions
- 🛒 **Système de panier** flexible (un ou plusieurs menus)
- 📦 **Gestion des commandes** complète
- 💳 **Système de paiement** intégré
- 🔗 **Relations complexes** entre entités

## 🏗️ Architecture

### Entités principales
- **Users** - Clients du restaurant
- **Admins** - Gestionnaires avec rôles
- **Categories** - Catégories de plats
- **Menus** - Plats disponibles
- **Paniers** - Paniers d'achat temporaires
- **Commandes** - Commandes finalisées
- **Paiements** - Transactions financières

### Tables de liaison
- **panier_menu** - Contenu des paniers avec quantités
- **commande_menu** - Contenu des commandes avec quantités

## 🚀 Installation

### Prérequis
- PHP 8.2+
- Composer
- MySQL
- Node.js (pour les assets frontend)

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone <repository-url>
cd apiadromi
```

2. **Installer les dépendances**
```bash
composer install
npm install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configuration de la base de données**
Modifier le fichier `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=adromidb
DB_USERNAME=root
DB_PASSWORD=
```

5. **Migrations**
```bash
php artisan migrate
```

6. **Lancer le serveur**
```bash
php artisan serve
```

L'API sera accessible sur `http://127.0.0.1:8000`

## 📡 Utilisation de l'API

### Base URL
```
http://127.0.0.1:8000/api
```

### Exemples d'utilisation

#### Créer un utilisateur
```http
POST /api/add_user
Content-Type: application/json

{
  "nom": "Doe",
  "prenom": "John",
  "telephone": "70658846",
  "email": "john.doe@example.com",
  "mot_de_passe": "password123"
}
```

#### Ajouter des menus au panier
```http
POST /api/add_panier
Content-Type: application/json

{
  "user_id": 1,
  "menus": [
    {
      "menu_id": 1,
      "quantite": 2
    },
    {
      "menu_id": 2,
      "quantite": 1
    }
  ]
}
```

#### Créer une commande
```http
POST /api/add_commande
Content-Type: application/json

{
  "user_id": 1,
  "menus": [
    {
      "menu_id": 1,
      "quantite": 2
    }
  ]
}
```

## 📚 Documentation

- **[Architecture détaillée](docs/ARCHITECTURE.md)** - Relations entre tables et flux de données
- **[Endpoints API](docs/API_ENDPOINTS.md)** - Documentation complète de tous les endpoints

## 🛠️ Technologies utilisées

- **Framework** : Laravel 12
- **PHP** : 8.2+
- **Base de données** : MySQL
- **ORM** : Eloquent
- **Architecture** : REST API
- **Frontend** : Vite + TailwindCSS (minimal)

## 📊 Structure de la base de données

```
users (1) ←→ (∞) paniers ←→ (∞) menus
users (1) ←→ (∞) commandes ←→ (∞) menus
categories (1) ←→ (∞) menus
commandes (1) ←→ (1) paiements
```

## 🔧 Développement

### Commandes utiles

```bash
# Vérifier la syntaxe PHP
php -l app/Http/Controllers/NomController.php

# Lancer les migrations
php artisan migrate

# Rollback des migrations
php artisan migrate:rollback

# Vérifier le statut des migrations
php artisan migrate:status

# Accéder à Tinker (REPL Laravel)
php artisan tinker
```

### Tests avec Tinker

```php
// Compter les utilisateurs
User::count()

// Créer un utilisateur de test
User::create([
    'nom' => 'Test',
    'prenom' => 'User',
    'telephone' => '70123456',
    'email' => 'test@test.com',
    'mot_de_passe' => bcrypt('password')
]);

// Vérifier les relations
$user = User::with(['paniers', 'commandes'])->first();
```

## 🎯 Fonctionnalités avancées

### Panier flexible
- Support pour ajouter un seul menu ou plusieurs menus en une fois
- Calcul automatique du total
- Gestion des quantités

### Gestion d'erreurs
- Validation complète des données
- Messages d'erreur détaillés
- Gestion des exceptions

### Relations optimisées
- Chargement eager des relations
- Tables pivot avec données supplémentaires (quantités)
- Intégrité référentielle

## 🤝 Contribution

1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commit vos changements
4. Push vers la branche
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 📞 Support

Pour toute question ou problème, n'hésitez pas à ouvrir une issue sur GitHub.

---

<p align="center">
  Développé avec ❤️ pour la gestion de restaurants
</p>
