Voici comment installer et configurer Browser Sync avec PHP Server :

## 1. **Installation de Browser Sync**

### Via npm (global) :
```bash
# Dans le terminal VS Code (Bash)
npm install -g browser-sync
```

### Ou via npm (local au projet) :
```bash
# Dans votre dossier projet
npm init -y
npm install --save-dev browser-sync concurrently
```

## 2. **Vérification de l'installation**

```bash
# Vérifier que Browser Sync est installé
browser-sync --version

# Si erreur "command not found", installer globalement :
npm install -g browser-sync
```

## 3. **Configuration Browser Sync**

Créez `bs-config.js` dans votre dossier projet :

```javascript
module.exports = {
    proxy: "localhost:3000",  // Port du serveur PHP
    files: [
        "**/*.js",
        "**/*.css", 
        "**/*.html",
        "src/*.php",
        "*.php"
    ],
    watchOptions: {
        ignoreInitial: true,
        ignored: [
            "node_modules/**/*",
            "vendor/**/*",
            ".git/**/*"
        ]
    },
    port: 3001,        // Port Browser Sync
    notify: false,     // Pas de notifications popup
    open: true,        // Ouvre automatiquement le navigateur
    logLevel: "info"
};
```

## 4. **Méthode 1 : Lancement manuel (2 terminaux)**

### Terminal 1 - PHP Server :
```bash
# Dans VS Code terminal
php -S localhost:3000 -t .
```

### Terminal 2 - Browser Sync :
```bash
# Nouveau terminal (Ctrl+Shift+`)
browser-sync start --config bs-config.js
```

## 5. **Méthode 2 : Script package.json (Recommandé)**

Créez ou modifiez `package.json` :

```json
{
    "name": "mon-projet-php",
    "version": "1.0.0",
    "description": "Projet PHP avec autocomplétion MongoDB",
    "scripts": {
        "dev": "concurrently \"npm run php\" \"npm run bs\"",
        "php": "php -S localhost:3000 -t .",
        "bs": "browser-sync start --config bs-config.js",
        "start": "npm run dev"
    },
    "devDependencies": {
        "browser-sync": "^2.27.10",
        "concurrently": "^7.6.0"
    }
}
```

### Installation des dépendances :
```bash
npm install
```

### Lancement :
```bash
npm run dev
```

## 6. **Méthode 3 : Script Bash personnalisé**

Créez `dev-server.sh` :

```bash
#!/bin/bash
echo "🚀 Démarrage du serveur de développement..."

# Vérifier si Browser Sync est installé
if ! command -v browser-sync &> /dev/null; then
    echo "❌ Browser Sync non trouvé. Installation..."
    npm install -g browser-sync
fi

# Démarrer PHP Server en arrière-plan
echo "📡 Démarrage PHP Server sur localhost:3000..."
php -S localhost:3000 -t . &
PHP_PID=$!

# Attendre que PHP démarre
sleep 2

# Démarrer Browser Sync
echo "🔄 Démarrage Browser Sync sur localhost:3001..."
echo "🌐 Votre site: http://localhost:3001"
echo "❌ Ctrl+C pour arrêter"

browser-sync start --proxy localhost:3000 --files "**/*.js,**/*.css,**/*.html,src/*.php" --port 3001 --no-notify

# Nettoyer à la sortie
kill $PHP_PID 2>/dev/null
```

### Rendre exécutable et lancer :
```bash
chmod +x dev-server.sh
./dev-server.sh
```

## 7. **Méthode 4 : Tâche VS Code**

Créez `.vscode/tasks.json` :

```json
{
    "version": "2.0.0",
    "tasks": [
        {
            "label": "Start PHP Server",
            "type": "shell",
            "command": "php",
            "args": ["-S", "localhost:3000", "-t", "."],
            "group": "build",
            "presentation": {
                "echo": true,
                "reveal": "always",
                "focus": false,
                "panel": "new"
            },
            "isBackground": true
        },
        {
            "label": "Start Browser Sync", 
            "type": "shell",
            "command": "browser-sync",
            "args": [
                "start",
                "--proxy", "localhost:3000",
                "--files", "**/*.js,**/*.css,**/*.html,src/*.php",
                "--port", "3001",
                "--no-notify"
            ],
            "group": "build",
            "dependsOn": "Start PHP Server"
        },
        {
            "label": "Dev Server",
            "dependsOrder": "parallel",
            "dependsOn": ["Start PHP Server", "Start Browser Sync"]
        }
    ]
}
```

### Lancement :
`Ctrl+Shift+P` → **"Tasks: Run Task"** → **"Dev Server"**

## 8. **Test de fonctionnement**

### Vérification Browser Sync :
1. Accédez à `http://localhost:3001`
2. Modifiez `script.js`
3. La page devrait se recharger automatiquement

### Vérification PHP :
1. Testez `http://localhost:3001/src/get_sites.php?query=test`
2. Devrait retourner du JSON, pas du code PHP

## 9. **Configuration avancée**

### Avec rechargement spécifique CSS :
```javascript
// bs-config.js avancé
module.exports = {
    proxy: "localhost:3000",
    files: [
        {
            match: ["**/*.css"],
            fn: function (event, file) {
                // Rechargement CSS sans refresh complet
                this.reload("*.css");
            }
        },
        {
            match: ["**/*.js", "**/*.php", "**/*.html"],
            fn: function (event, file) {
                // Refresh complet pour JS/PHP
                this.reload();
            }
        }
    ],
    port: 3001,
    notify: false,
    open: true,
    logPrefix: "PHP-DEV"
};
```

## 10. **Commandes finales**

```bash
# Installation complète
npm install -g browser-sync
npm init -y
npm install --save-dev browser-sync concurrently

# Lancement (choisir une méthode)
npm run dev                    # Via package.json
./dev-server.sh               # Via script bash
# Ou via tâches VS Code
```

## ✅ **Résultat attendu :**

- **PHP Server** : `http://localhost:3000`
- **Browser Sync** : `http://localhost:3001` (avec auto-refresh)
- **Surveillance** : JS, CSS, HTML, PHP
- **Rechargement automatique** dès modification

Votre environnement de développement sera maintenant professionnel avec rechargement automatique ! 🚀