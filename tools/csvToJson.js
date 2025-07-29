// OUTIL NODE.JS POUR CONVERTIR LES CSV EN JSON


// Pour lire et de parser des fichiers CSV
// csv-parser : npm install csv-parser
const csv = require('csv-parser');
// File System Node.JS
const fs = require('fs');
// gestion prompt : npm install prompt-sync
const prompt = require('prompt-sync')({ sigint: true });


function main() {
    // Demander à l'utilisateur d'entrer le nom du fichier CSV
    const csvFileName = prompt('Veuillez entrer le nom du fichier CSV : ');

    // Vérifier si le fichier existe
    if (!fs.existsSync(csvFileName)) {
        console.error("Le fichier spécifié n'existe pas.");
        process.exit(1);
    }

    const results = [];

    fs.createReadStream(csvFileName)
        .pipe(csv())
        .on('data', (data) => results.push(data))
        .on('end', () => {
            // Convertir les résultats en JSON
            const jsonData = JSON.stringify(results, null, 4);

            // Demander à l'utilisateur d'entrer le nom du fichier JSON de sortie
            const jsonFileName = prompt('Veuillez entrer le nom du fichier JSON de sortie : ');

            // Écrire les données JSON dans un fichier
            fs.writeFile(jsonFileName, jsonData, (err) => {
                if (err) {
                    console.error('Erreur lors de l\'écriture du fichier JSON :', err);
                } else {
                    console.log('Fichier JSON créé avec succès.');
                }
            });
        })
        .on('error', (error) => {
            console.error('Erreur lors de la lecture du fichier CSV :', error);
        });
}

main();