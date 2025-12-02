# 📱 Intégration Flutter avec API Adromi

## 🚀 Configuration initiale

### 1. **Dépendances Flutter**

Ajoutez dans `pubspec.yaml` :
```yaml
dependencies:
  flutter:
    sdk: flutter
  http: ^1.1.0
  provider: ^6.1.1
  shared_preferences: ^2.2.2
  dio: ^5.3.2  # Alternative à http
```

### 2. **Configuration de base**

```dart
// lib/config/api_config.dart
class ApiConfig {
  static const String baseUrl = 'http://127.0.0.1:8000/api';
  
  // Endpoints
  static const String users = '/get_all_users';
  static const String addUser = '/add_user';
  static const String categories = '/get_all_categories';
  static const String menus = '/get_all_menus';
  static const String addPanier = '/add_panier';
  static const String addCommande = '/add_commande';
}
```

## 🔧 Service API

### **Service HTTP principal**

```dart
// lib/services/api_service.dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import '../config/api_config.dart';

class ApiService {
  static Future<Map<String, dynamic>> get(String endpoint) async {
    try {
      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}$endpoint'),
        headers: {'Content-Type': 'application/json'},
      );
      
      return _handleResponse(response);
    } catch (e) {
      throw Exception('Erreur réseau: $e');
    }
  }

  static Future<Map<String, dynamic>> post(
    String endpoint, 
    Map<String, dynamic> data
  ) async {
    try {
      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}$endpoint'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode(data),
      );
      
      return _handleResponse(response);
    } catch (e) {
      throw Exception('Erreur réseau: $e');
    }
  }

  static Map<String, dynamic> _handleResponse(http.Response response) {
    final data = json.decode(response.body);
    
    if (response.statusCode >= 200 && response.statusCode < 300) {
      return data;
    } else {
      throw Exception(data['message'] ?? 'Erreur API');
    }
  }
}
```

## 📊 Modèles de données

### **Modèles principaux**

```dart
// lib/models/user.dart
class User {
  final int id;
  final String nom;
  final String prenom;
  final String email;
  final String telephone;

  User({
    required this.id,
    required this.nom,
    required this.prenom,
    required this.email,
    required this.telephone,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      nom: json['nom'],
      prenom: json['prenom'],
      email: json['email'],
      telephone: json['telephone'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'nom': nom,
      'prenom': prenom,
      'email': email,
      'telephone': telephone,
    };
  }
}

// lib/models/menu.dart
class Menu {
  final int id;
  final String nom;
  final String description;
  final double prix;
  final String? image;
  final int categorieId;

  Menu({
    required this.id,
    required this.nom,
    required this.description,
    required this.prix,
    this.image,
    required this.categorieId,
  });

  factory Menu.fromJson(Map<String, dynamic> json) {
    return Menu(
      id: json['id'],
      nom: json['nom'],
      description: json['description'],
      prix: double.parse(json['prix'].toString()),
      image: json['image'],
      categorieId: json['categorie_id'],
    );
  }
}

// lib/models/panier_item.dart
class PanierItem {
  final int menuId;
  final int quantite;

  PanierItem({required this.menuId, required this.quantite});

  Map<String, dynamic> toJson() {
    return {
      'menu_id': menuId,
      'quantite': quantite,
    };
  }
}
```

## 🛠️ Providers (State Management)

### **Provider pour les utilisateurs**

```dart
// lib/providers/user_provider.dart
import 'package:flutter/material.dart';
import '../models/user.dart';
import '../services/api_service.dart';

class UserProvider with ChangeNotifier {
  List<User> _users = [];
  User? _currentUser;
  bool _isLoading = false;

  List<User> get users => _users;
  User? get currentUser => _currentUser;
  bool get isLoading => _isLoading;

  Future<void> fetchUsers() async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await ApiService.get('/get_all_users');
      if (response['success']) {
        _users = (response['data'] as List)
            .map((json) => User.fromJson(json))
            .toList();
      }
    } catch (e) {
      print('Erreur: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> createUser(User user) async {
    try {
      final response = await ApiService.post('/add_user', {
        ...user.toJson(),
        'mot_de_passe': 'password123', // À gérer proprement
      });
      
      if (response['success']) {
        await fetchUsers(); // Recharger la liste
        return true;
      }
      return false;
    } catch (e) {
      print('Erreur création: $e');
      return false;
    }
  }
}
```

### **Provider pour le panier**

```dart
// lib/providers/panier_provider.dart
import 'package:flutter/material.dart';
import '../models/panier_item.dart';
import '../services/api_service.dart';

class PanierProvider with ChangeNotifier {
  List<PanierItem> _items = [];
  bool _isLoading = false;

  List<PanierItem> get items => _items;
  bool get isLoading => _isLoading;

  void addItem(int menuId, int quantite) {
    final existingIndex = _items.indexWhere((item) => item.menuId == menuId);
    
    if (existingIndex >= 0) {
      _items[existingIndex] = PanierItem(
        menuId: menuId,
        quantite: _items[existingIndex].quantite + quantite,
      );
    } else {
      _items.add(PanierItem(menuId: menuId, quantite: quantite));
    }
    notifyListeners();
  }

  Future<bool> syncWithServer(int userId) async {
    if (_items.isEmpty) return false;

    _isLoading = true;
    notifyListeners();

    try {
      final response = await ApiService.post('/add_panier', {
        'user_id': userId,
        'menus': _items.map((item) => item.toJson()).toList(),
      });

      if (response['success']) {
        _items.clear();
        notifyListeners();
        return true;
      }
      return false;
    } catch (e) {
      print('Erreur sync panier: $e');
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
```

## 🎨 Interfaces utilisateur

### **Page de connexion**

```dart
// lib/screens/login_screen.dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/user_provider.dart';

class LoginScreen extends StatefulWidget {
  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Connexion - Adromi')),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              controller: _emailController,
              decoration: InputDecoration(
                labelText: 'Email',
                border: OutlineInputBorder(),
              ),
            ),
            SizedBox(height: 16),
            TextField(
              controller: _passwordController,
              obscureText: true,
              decoration: InputDecoration(
                labelText: 'Mot de passe',
                border: OutlineInputBorder(),
              ),
            ),
            SizedBox(height: 24),
            ElevatedButton(
              onPressed: _login,
              child: Text('Se connecter'),
            ),
          ],
        ),
      ),
    );
  }

  void _login() {
    // Logique de connexion
    Navigator.pushReplacementNamed(context, '/menu');
  }
}
```

### **Page des menus**

```dart
// lib/screens/menu_screen.dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/panier_provider.dart';
import '../models/menu.dart';
import '../services/api_service.dart';

class MenuScreen extends StatefulWidget {
  @override
  _MenuScreenState createState() => _MenuScreenState();
}

class _MenuScreenState extends State<MenuScreen> {
  List<Menu> menus = [];
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadMenus();
  }

  Future<void> _loadMenus() async {
    try {
      final response = await ApiService.get('/get_all_menus');
      if (response['success']) {
        setState(() {
          menus = (response['data'] as List)
              .map((json) => Menu.fromJson(json))
              .toList();
          isLoading = false;
        });
      }
    } catch (e) {
      setState(() => isLoading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Erreur: $e')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Menu - Adromi'),
        actions: [
          IconButton(
            icon: Icon(Icons.shopping_cart),
            onPressed: () => Navigator.pushNamed(context, '/panier'),
          ),
        ],
      ),
      body: isLoading
          ? Center(child: CircularProgressIndicator())
          : ListView.builder(
              itemCount: menus.length,
              itemBuilder: (context, index) {
                final menu = menus[index];
                return Card(
                  margin: EdgeInsets.all(8),
                  child: ListTile(
                    leading: menu.image != null
                        ? Image.network(menu.image!, width: 50, height: 50)
                        : Icon(Icons.restaurant),
                    title: Text(menu.nom),
                    subtitle: Text(menu.description),
                    trailing: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text('${menu.prix}€', 
                             style: TextStyle(fontWeight: FontWeight.bold)),
                        ElevatedButton(
                          onPressed: () => _addToCart(menu),
                          child: Text('Ajouter'),
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
    );
  }

  void _addToCart(Menu menu) {
    Provider.of<PanierProvider>(context, listen: false)
        .addItem(menu.id, 1);
    
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text('${menu.nom} ajouté au panier')),
    );
  }
}
```

## 🛒 Gestion du panier

### **Page panier**

```dart
// lib/screens/panier_screen.dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/panier_provider.dart';
import '../services/api_service.dart';

class PanierScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Mon Panier')),
      body: Consumer<PanierProvider>(
        builder: (context, panier, child) {
          if (panier.items.isEmpty) {
            return Center(child: Text('Panier vide'));
          }

          return Column(
            children: [
              Expanded(
                child: ListView.builder(
                  itemCount: panier.items.length,
                  itemBuilder: (context, index) {
                    final item = panier.items[index];
                    return ListTile(
                      title: Text('Menu ${item.menuId}'),
                      subtitle: Text('Quantité: ${item.quantite}'),
                    );
                  },
                ),
              ),
              Padding(
                padding: EdgeInsets.all(16),
                child: ElevatedButton(
                  onPressed: panier.isLoading ? null : () => _commander(context),
                  child: panier.isLoading
                      ? CircularProgressIndicator()
                      : Text('Commander'),
                ),
              ),
            ],
          );
        },
      ),
    );
  }

  Future<void> _commander(BuildContext context) async {
    try {
      final response = await ApiService.post('/add_commande', {
        'user_id': 1, // ID utilisateur connecté
        'menus': Provider.of<PanierProvider>(context, listen: false)
            .items
            .map((item) => item.toJson())
            .toList(),
      });

      if (response['success']) {
        Provider.of<PanierProvider>(context, listen: false).items.clear();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Commande passée avec succès!')),
        );
        Navigator.pop(context);
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Erreur: $e')),
      );
    }
  }
}
```

## 🚀 Configuration principale

### **Main.dart**

```dart
// lib/main.dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'providers/user_provider.dart';
import 'providers/panier_provider.dart';
import 'screens/login_screen.dart';
import 'screens/menu_screen.dart';
import 'screens/panier_screen.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => UserProvider()),
        ChangeNotifierProvider(create: (_) => PanierProvider()),
      ],
      child: MaterialApp(
        title: 'Adromi App',
        theme: ThemeData(primarySwatch: Colors.orange),
        initialRoute: '/login',
        routes: {
          '/login': (context) => LoginScreen(),
          '/menu': (context) => MenuScreen(),
          '/panier': (context) => PanierScreen(),
        },
      ),
    );
  }
}
```

## 🔧 Conseils d'implémentation

### **Gestion d'erreurs**
- Utilisez try-catch pour toutes les requêtes API
- Affichez des messages d'erreur utilisateur-friendly
- Implémentez un système de retry pour les erreurs réseau

### **Performance**
- Utilisez FutureBuilder pour les chargements asynchrones
- Implémentez la pagination pour les grandes listes
- Cachez les images avec CachedNetworkImage

### **Sécurité**
- Stockez les tokens d'authentification de manière sécurisée
- Validez toutes les entrées utilisateur
- Utilisez HTTPS en production

Cette intégration vous permet de créer une app Flutter complète qui communique avec votre API Adromi ! 🚀
