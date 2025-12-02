# 🏗️ Architecture du projet API Adromi

## Vue d'ensemble

**API Adromi** est un système de gestion de restaurant/livraison développé avec Laravel 12. Il permet aux clients de parcourir un menu, ajouter des plats à leur panier, passer des commandes et effectuer des paiements.

## 📊 Tables principales (Entités)

### 1. **`users`** - Les clients du restaurant
- Stocke les informations des clients
- Champs : `id`, `nom`, `prenom`, `telephone`, `email`, `mot_de_passe`

### 2. **`admins`** - Les gestionnaires/employés
- Gestion du back-office
- Champs : `id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `telephone`

### 3. **`categories`** - Catégories de plats
- Organisation des menus (Entrées, Plats, Desserts...)
- Champs : `id`, `nom`, `description`

### 4. **`menus`** - Les plats/produits disponibles
- Catalogue des produits
- Champs : `id`, `nom`, `description`, `prix`, `image`, `categorie_id`

### 5. **`paniers`** - Paniers d'achat des clients
- Paniers temporaires avant commande
- Champs : `id`, `user_id`, `statut`, `total`, `date_creation`, `date_validation`

### 6. **`commandes`** - Commandes finalisées
- Commandes confirmées par les clients
- Champs : `id`, `user_id`, `date_commande`, `statut`, `total`

### 7. **`paiements`** - Transactions financières
- Gestion des paiements
- Champs : `id`, `commande_id`, `montant`, `methode_paiement`, `status`

## 🔗 Tables de liaison (Pivot)

### 8. **`panier_menu`** - Relation Many-to-Many entre Paniers et Menus
- Contenu des paniers avec quantités
- Champs : `id`, `panier_id`, `menu_id`, `quantite`

### 9. **`commande_menu`** - Relation Many-to-Many entre Commandes et Menus
- Contenu des commandes avec quantités
- Champs : `id`, `commande_id`, `menu_id`, `quantite`

## 🔄 Relations détaillées

### **1. Users ↔ Paniers (One-to-Many)**
```
users (1) ←→ (∞) paniers
```
- **Un utilisateur** peut avoir **plusieurs paniers** (historique)
- **Un panier** appartient à **un seul utilisateur**
- **Clé étrangère :** `paniers.user_id` → `users.id`

### **2. Users ↔ Commandes (One-to-Many)**
```
users (1) ←→ (∞) commandes
```
- **Un utilisateur** peut passer **plusieurs commandes**
- **Une commande** appartient à **un seul utilisateur**
- **Clé étrangère :** `commandes.user_id` → `users.id`

### **3. Categories ↔ Menus (One-to-Many)**
```
categories (1) ←→ (∞) menus
```
- **Une catégorie** contient **plusieurs menus**
- **Un menu** appartient à **une seule catégorie**
- **Clé étrangère :** `menus.categorie_id` → `categories.id`

### **4. Paniers ↔ Menus (Many-to-Many)**
```
paniers (∞) ←→ (∞) menus
```
- **Un panier** peut contenir **plusieurs menus**
- **Un menu** peut être dans **plusieurs paniers**
- **Table pivot :** `panier_menu` avec `quantite`

### **5. Commandes ↔ Menus (Many-to-Many)**
```
commandes (∞) ←→ (∞) menus
```
- **Une commande** peut contenir **plusieurs menus**
- **Un menu** peut être dans **plusieurs commandes**
- **Table pivot :** `commande_menu` avec `quantite`

### **6. Commandes ↔ Paiements (One-to-One)**
```
commandes (1) ←→ (1) paiements
```
- **Une commande** a **un seul paiement**
- **Un paiement** correspond à **une seule commande**
- **Clé étrangère :** `paiements.commande_id` → `commandes.id`

## 🔄 Flux de fonctionnement

### Processus complet d'une commande :

1. **Inscription** : Client s'inscrit → `users` table
2. **Navigation** : Client parcourt les catégories → `categories` table  
3. **Sélection** : Client voit les menus → `menus` table
4. **Panier** : Client ajoute des plats → `paniers` + `panier_menu` tables
5. **Commande** : Client finalise → `commandes` + `commande_menu` tables
6. **Paiement** : Client paie → `paiements` table

## 📊 Exemple de données liées

```sql
-- User
users: id=1, nom="Doe", email="john@email.com"

-- Catégorie  
categories: id=1, nom="Pizzas"

-- Menu
menus: id=1, nom="Margherita", prix=15.00, categorie_id=1

-- Panier
paniers: id=1, user_id=1, total=30.00, statut="en_cours"

-- Relation Panier-Menu
panier_menu: panier_id=1, menu_id=1, quantite=2

-- Commande (après finalisation)
commandes: id=1, user_id=1, total=30.00, statut="en_attente"

-- Relation Commande-Menu  
commande_menu: commande_id=1, menu_id=1, quantite=2

-- Paiement
paiements: id=1, commande_id=1, montant=30.00, status="valide"
```

## 🎯 Points clés

### ✅ Avantages de cette architecture :
- **Flexibilité** : Un panier peut contenir plusieurs plats différents
- **Historique** : Toutes les commandes sont conservées
- **Évolutivité** : Facile d'ajouter de nouvelles fonctionnalités
- **Intégrité** : Les clés étrangères garantissent la cohérence

### 🔑 Tables pivot cruciales :
- **`panier_menu`** : Gère le contenu des paniers avec quantités
- **`commande_menu`** : Gère le contenu des commandes avec quantités

Ces tables permettent de stocker **la quantité** de chaque plat, ce qui est essentiel pour un système de restaurant !

## 🛠️ Technologies utilisées

- **Framework** : Laravel 12
- **PHP** : 8.2+
- **Base de données** : MySQL
- **ORM** : Eloquent
- **Architecture** : REST API
