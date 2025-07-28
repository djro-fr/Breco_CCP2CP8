# Voici comment installer et configurer Browser Sync avec PHP Server

## 1. **Installation de Browser Sync**

### Via npm (global)

```bash
# Dans le terminal VS Code (Bash)
npm install -g browser-sync
```

### Ou via npm (local au projet)

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

### Terminal 1 - PHP Server

```bash
# Dans VS Code terminal
php -S localhost:3000 -t .
```

### Terminal 2 - Browser Sync

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

### Installation des dépendances

```bash
npm install
```

### Lancement des dépendances

```bash
npm run dev
```
