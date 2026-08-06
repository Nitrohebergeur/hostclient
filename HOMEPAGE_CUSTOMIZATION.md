# 🎨 Personnalisation de la Page d'Accueil Admin

## Vue d'ensemble

Cette fonctionnalité permet aux administrateurs de personnaliser complètement la page d'accueil du panel admin avec leur propre HTML et CSS.

## 🚀 Mise en Route

### 1. Installation

Exécutez le seeder pour créer les paramètres par défaut :

```bash
php artisan db:seed --class=HomePageSettingsSeeder
```

### 2. Accès à l'éditeur

1. Connectez-vous au panel admin
2. Allez dans **Paramètres** > **Page d'Accueil** 
3. Ou accédez directement à `/admin/homepage/edit`

## 📝 Utilisation

### Éditeur HTML/CSS

L'interface d'édition vous permet de :
- ✏️ Modifier le code HTML de la page
- 🎨 Personnaliser les styles CSS
- 👁️ Prévisualiser en temps réel
- 💾 Sauvegarder vos modifications

### Variables Blade Disponibles

Vous pouvez utiliser ces variables dans votre HTML :

```php
{{ $stats['total_clients'] }}      // Nombre total de clients
{{ $stats['active_services'] }}    // Services actifs
{{ $stats['pending_orders'] }}     // Commandes en attente
{{ $stats['open_tickets'] }}       // Tickets ouverts
{{ $stats['unpaid_invoices'] }}    // Factures impayées
{{ $stats['monthly_revenue'] }}    // Revenu mensuel
```

### Exemple d'utilisation

```html
<div class="stat-card">
    <h3>Total Clients</h3>
    <p class="stat-number">{{ $stats['total_clients'] ?? 0 }}</p>
</div>
```

## 🎨 Design par Défaut

Le design par défaut est un thème minimaliste noir et blanc avec :
- Header avec titre et sous-titre
- 4 cartes de statistiques
- Section d'actions rapides
- Section d'informations système
- Footer personnalisable
- Design responsive

## 💡 Conseils de Personnalisation

### HTML
- Utilisez des classes sémantiques pour faciliter le styling
- Gardez la structure simple et organisée
- Utilisez `{{ $stats['key'] ?? 0 }}` pour éviter les erreurs si une variable est manquante

### CSS
- Le design par défaut est en noir et blanc pour une personnalisation facile
- Utilisez des variables CSS pour une maintenance plus simple
- Pensez au responsive design avec `@media queries`
- Les transitions et animations améliorent l'expérience utilisateur

### Exemples de Customisation

#### Thème Sombre
```css
.homepage-container {
    background: #1a1a1a;
    color: #ffffff;
}

.stat-card {
    background: #2d2d2d;
    border: 2px solid #3d3d3d;
}
```

#### Thème Coloré
```css
.stat-card:nth-child(1) { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card:nth-child(2) { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-card:nth-child(3) { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-card:nth-child(4) { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
```

## 🔧 Fonctionnalités Techniques

### Routes
- `GET /admin/homepage/edit` - Afficher l'éditeur
- `PUT /admin/homepage/update` - Sauvegarder les modifications
- `GET /admin/homepage/preview` - Prévisualiser avec données de test

### Stockage
Les paramètres sont stockés dans la table `settings` :
- `homepage_html` - Code HTML personnalisé
- `homepage_css` - Styles CSS personnalisés

### Cache
Les paramètres sont mis en cache. Le cache est automatiquement vidé lors de la sauvegarde.

## 🔒 Sécurité

⚠️ **Important** : Cette fonctionnalité permet l'injection de HTML/CSS brut. Assurez-vous que seuls les administrateurs de confiance ont accès à cette page.

## 🐛 Dépannage

### La page personnalisée ne s'affiche pas
1. Vérifiez que les paramètres sont bien enregistrés dans la base de données
2. Videz le cache : `php artisan cache:clear`
3. Vérifiez qu'il n'y a pas d'erreurs de syntaxe dans votre HTML/CSS

### Les variables Blade ne fonctionnent pas
- Utilisez la syntaxe exacte : `{{ $stats['key'] }}`
- Ajoutez toujours une valeur par défaut : `{{ $stats['key'] ?? 0 }}`

### Styles CSS non appliqués
- Vérifiez qu'il n'y a pas d'erreurs de syntaxe CSS
- Utilisez des sélecteurs spécifiques pour éviter les conflits
- Inspectez l'élément dans le navigateur pour voir les styles appliqués

## 📚 Ressources

- [Laravel Blade Documentation](https://laravel.com/docs/blade)
- [CSS Grid Guide](https://css-tricks.com/snippets/css/complete-guide-grid/)
- [Responsive Design](https://developer.mozilla.org/en-US/docs/Learn/CSS/CSS_layout/Responsive_Design)

## 🎉 Exemples de Pages

Consultez les exemples dans le dossier `resources/views/admin/homepage/examples/` pour plus d'inspiration.

---

**Version:** 1.0.0  
**Créé le:** 2026-08-06
