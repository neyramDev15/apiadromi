# 📡 API Endpoints - Adromi

## Base URL
```
http://127.0.0.1:8000/api
```

---

## 👥 **USERS** - Gestion des clients

### GET `/get_all_users`
**Description :** Récupérer tous les utilisateurs
```http
GET /api/get_all_users
```
**Réponse :**
```json
{
  "success": true,
  "data": [...]
}
```

### POST `/add_user`
**Description :** Créer un nouvel utilisateur
```http
POST /api/add_user
Content-Type: application/json
```
**Body :**
```json
{
  "nom": "Doe",
  "prenom": "John",
  "telephone": "70658846",
  "email": "john.doe@example.com",
  "mot_de_passe": "password123"
}
```

### GET `/get_user/{id}`
**Description :** Récupérer un utilisateur spécifique
```http
GET /api/get_user/1
```

### PUT `/edit_user/{id}`
**Description :** Modifier un utilisateur
```http
PUT /api/edit_user/1
Content-Type: application/json
```
**Body :**
```json
{
  "nom": "Smith",
  "prenom": "Jane",
  "telephone": "70123456"
}
```

### DELETE `/delete_user/{id}`
**Description :** Supprimer un utilisateur
```http
DELETE /api/delete_user/1
```

---

## 👨‍💼 **ADMINS** - Gestion des administrateurs

### GET `/get_all_admins`
**Description :** Récupérer tous les administrateurs
```http
GET /api/get_all_admins
```

### POST `/add_admin`
**Description :** Créer un nouvel administrateur
```http
POST /api/add_admin
Content-Type: application/json
```
**Body :**
```json
{
  "nom": "Admin",
  "prenom": "Super",
  "email": "admin@adromi.com",
  "mot_de_passe": "admin123",
  "role": "gestionnaire",
  "telephone": "70111222"
}
```

### GET `/get_admin/{id}`
**Description :** Récupérer un administrateur spécifique
```http
GET /api/get_admin/1
```

### PUT `/edit_admin/{id}`
**Description :** Modifier un administrateur
```http
PUT /api/edit_admin/1
Content-Type: application/json
```
**Body :**
```json
{
  "role": "super_admin",
  "telephone": "70999888"
}
```

### DELETE `/delete_admin/{id}`
**Description :** Supprimer un administrateur
```http
DELETE /api/delete_admin/1
```

---

## 📂 **CATEGORIES** - Gestion des catégories

### GET `/get_all_categories`
**Description :** Récupérer toutes les catégories
```http
GET /api/get_all_categories
```

### POST `/add_categorie`
**Description :** Créer une nouvelle catégorie
```http
POST /api/add_categorie
Content-Type: application/json
```
**Body :**
```json
{
  "nom": "Pizzas",
  "description": "Nos délicieuses pizzas artisanales"
}
```

### GET `/get_categorie/{id}`
**Description :** Récupérer une catégorie spécifique
```http
GET /api/get_categorie/1
```

### PUT `/edit_categorie/{id}`
**Description :** Modifier une catégorie
```http
PUT /api/edit_categorie/1
Content-Type: application/json
```
**Body :**
```json
{
  "nom": "Pizzas Premium",
  "description": "Nos pizzas haut de gamme"
}
```

### DELETE `/delete_categorie/{id}`
**Description :** Supprimer une catégorie
```http
DELETE /api/delete_categorie/1
```

---

## 🍕 **MENUS** - Gestion des plats

### GET `/get_all_menus`
**Description :** Récupérer tous les menus
```http
GET /api/get_all_menus
```

### POST `/add_menu`
**Description :** Créer un nouveau menu
```http
POST /api/add_menu
Content-Type: application/json
```
**Body :**
```json
{
  "categorie_id": 1,
  "nom": "Pizza Margherita",
  "description": "Tomate, mozzarella, basilic",
  "prix": 12.50,
  "image": "https://example.com/pizza.jpg"
}
```

### GET `/get_menu/{id}`
**Description :** Récupérer un menu spécifique
```http
GET /api/get_menu/1
```

### PUT `/edit_menu/{id}`
**Description :** Modifier un menu
```http
PUT /api/edit_menu/1
Content-Type: application/json
```
**Body :**
```json
{
  "nom": "Pizza Margherita Premium",
  "prix": 15.00
}
```

### DELETE `/delete_menu/{id}`
**Description :** Supprimer un menu
```http
DELETE /api/delete_menu/1
```

---

## 🛒 **PANIERS** - Gestion des paniers

### GET `/get_all_paniers`
**Description :** Récupérer tous les paniers
```http
GET /api/get_all_paniers
```

### POST `/add_panier`
**Description :** Ajouter un menu au panier (format simple)
```http
POST /api/add_panier
Content-Type: application/json
```
**Body (un seul menu) :**
```json
{
  "user_id": 1,
  "menu_id": 1,
  "quantite": 2
}
```
**Body (plusieurs menus) :**
```json
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

### GET `/get_panier/{id}`
**Description :** Récupérer un panier spécifique
```http
GET /api/get_panier/1
```

### PUT `/edit_panier/{id}`
**Description :** Modifier un panier
```http
PUT /api/edit_panier/1
Content-Type: application/json
```
**Body :**
```json
{
  "user_id": 2
}
```

### DELETE `/delete_panier/{id}`
**Description :** Supprimer un panier
```http
DELETE /api/delete_panier/1
```

---

## 🛒📋 **PANIER-MENU** - Gestion du contenu des paniers

### GET `/get_panier_menus/{panier_id}`
**Description :** Récupérer le contenu d'un panier
```http
GET /api/get_panier_menus/1
```

### POST `/add_menu_to_panier`
**Description :** Ajouter un menu à un panier
```http
POST /api/add_menu_to_panier
Content-Type: application/json
```
**Body :**
```json
{
  "panier_id": 1,
  "menu_id": 1,
  "quantite": 2
}
```

### PUT `/edit_panier_menu/{id}`
**Description :** Modifier la quantité d'un menu dans un panier
```http
PUT /api/edit_panier_menu/1
Content-Type: application/json
```
**Body :**
```json
{
  "quantite": 3
}
```

### DELETE `/remove_menu_from_panier/{id}`
**Description :** Supprimer un menu d'un panier (par ID pivot)
```http
DELETE /api/remove_menu_from_panier/1
```

### DELETE `/remove_menu_from_panier_by_ids`
**Description :** Supprimer un menu d'un panier (par panier_id et menu_id)
```http
DELETE /api/remove_menu_from_panier_by_ids
Content-Type: application/json
```
**Body :**
```json
{
  "panier_id": 1,
  "menu_id": 1
}
```

---

## 📦 **COMMANDES** - Gestion des commandes

### GET `/get_all_commandes`
**Description :** Récupérer toutes les commandes
```http
GET /api/get_all_commandes
```

### POST `/add_commande`
**Description :** Créer une nouvelle commande
```http
POST /api/add_commande
Content-Type: application/json
```
**Body :**
```json
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

### GET `/get_commande/{id}`
**Description :** Récupérer une commande spécifique
```http
GET /api/get_commande/1
```

### PUT `/edit_commande/{id}`
**Description :** Modifier une commande
```http
PUT /api/edit_commande/1
Content-Type: application/json
```
**Body :**
```json
{
  "statut": "en_cours"
}
```

### DELETE `/delete_commande/{id}`
**Description :** Supprimer une commande
```http
DELETE /api/delete_commande/1
```

---

## 📦📋 **COMMANDE-MENU** - Gestion du contenu des commandes

### GET `/get_commande_menus/{commande_id}`
**Description :** Récupérer le contenu d'une commande
```http
GET /api/get_commande_menus/1
```

### POST `/add_menu_to_commande`
**Description :** Ajouter un menu à une commande
```http
POST /api/add_menu_to_commande
Content-Type: application/json
```
**Body :**
```json
{
  "commande_id": 1,
  "menu_id": 1,
  "quantite": 2
}
```

### PUT `/edit_commande_menu/{id}`
**Description :** Modifier la quantité d'un menu dans une commande
```http
PUT /api/edit_commande_menu/1
Content-Type: application/json
```
**Body :**
```json
{
  "quantite": 3
}
```

### DELETE `/remove_menu_from_commande/{id}`
**Description :** Supprimer un menu d'une commande
```http
DELETE /api/remove_menu_from_commande/1
```

---

## 💳 **PAIEMENTS** - Gestion des paiements

### GET `/get_all_paiements`
**Description :** Récupérer tous les paiements
```http
GET /api/get_all_paiements
```

### POST `/add_paiement`
**Description :** Créer un nouveau paiement
```http
POST /api/add_paiement
Content-Type: application/json
```
**Body :**
```json
{
  "commande_id": 1,
  "montant": 30.00,
  "methode_paiement": "carte_bancaire",
  "status": "en_attente"
}
```

**Méthodes de paiement possibles :**
- `carte_bancaire`
- `especes`
- `mobile_money`
- `virement`

**Status possibles :**
- `en_attente`
- `valide`
- `echoue`
- `rembourse`

### GET `/get_paiement/{id}`
**Description :** Récupérer un paiement spécifique
```http
GET /api/get_paiement/1
```

### PUT `/edit_paiement/{id}`
**Description :** Modifier un paiement
```http
PUT /api/edit_paiement/1
Content-Type: application/json
```
**Body :**
```json
{
  "status": "valide"
}
```

### DELETE `/delete_paiement/{id}`
**Description :** Supprimer un paiement
```http
DELETE /api/delete_paiement/1
```

---

## 📋 **Codes de réponse HTTP**

- **200** : Succès
- **201** : Créé avec succès
- **404** : Ressource non trouvée
- **422** : Erreur de validation
- **500** : Erreur serveur

## 🔧 **Format des réponses**

### Succès
```json
{
  "success": true,
  "message": "Opération réussie",
  "data": {...}
}
```

### Erreur
```json
{
  "success": false,
  "message": "Description de l'erreur",
  "error": "Détails techniques"
}
```
