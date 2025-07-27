/** **************************************************************
 * Configuration BrowserSync
 * 
 *****************************************************************/ 

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
    browser: "chrome",
    logLevel: "info"
};