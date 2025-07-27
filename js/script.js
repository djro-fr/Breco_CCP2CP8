// Declare variables
let fromInput;
let toInput;
let fromList;
let toList;

// Création de la liste à partir de la base de données NoSQL
function getSitesList(input, list){
    // On récupère la valeur, sans espaces    
    const inputValue = input.value.trim();

    // Si elle est vide, on quitte la fonction
    if (inputValue.length === 0) {
        list.innerHTML = '';
        return;
    }

    // On va chercher la liste avec AJAX et on injecte l'input entrée par l'utilisateur
    fetch(`src/get_sites.php?query=${inputValue}`)
        .then(response => response.json())
        //on 
        .then(data => {
            // On efface la liste UL en cours
            list.innerHTML = '';
            data.forEach(location => {
                const listItem = document.createElement('li');
                listItem.textContent = location.nom;
                list.appendChild(listItem);

                // Quand on clique sur le nom dans la liste, l'input change
                listItem.addEventListener('click', function () {
                    input.value = location.nom;
                    list.innerHTML = '';
                });
            });
        })
        .catch(error => {
            console.error('Erreur sur l\'autocomplétion :', error);
        });
        

}


// gestion de l'autocomplétion au chargement du DOM
function initLocationAutoComplete() {
    
    //on récupère les entrées du DOM des inputs
    fromInput = document.getElementById('villeDepart');
    toInput = document.getElementById('villeArrivee');
    //on récupère les entrées du DOM des listes associées
    fromList = document.getElementById('listeDepart');
    toList = document.getElementById('listeArrivee');

    // Si on a une entrée pour le Départ on va chercher la liste
    if (fromInput != null && fromList != null) {
        fromInput.addEventListener('input', () => {
            getSitesList(fromInput, fromList);
        });
    } // Sinon, il y a un problème

    // on fait la même chose pour l'arrivée
    if (toInput != null && toList != null) {
        toInput.addEventListener('input', () => {
            getSitesList(toInput, toList);
        });
    } 

}

document.addEventListener('DOMContentLoaded', initLocationAutoComplete);





