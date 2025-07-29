// Gestion du menu
const buttonNAV = document.querySelector("nav i.iconBurger button");
const NAV = document.querySelector("nav");

buttonNAV.addEventListener("click", (event) => {    
    NAV.classList.toggle("open");
});

// Données exemples STUBBING **********************************************************************

document.querySelector("#Duree").textContent="+ 17 min";

// document.querySelector("input#villeDepart").value="Vignoc";
// document.querySelector("input#heureDepart").value="07:43";
// document.querySelector("input#villeArrivee").value="Rennes";
// document.querySelector("input#heureArrivee").value="08:00";


// ********************************************************************


// Declare variables
let fromInput;
let toInput;
let HfromInput;
let HtoInput;
let fromList;
let toList;

//on récupère les entrées du DOM des inputs
fromInput = document.getElementById('villeDepart');
toInput = document.getElementById('villeArrivee');
//on récupère les entrées du DOM des listes associées
fromList = document.getElementById('listeDepart');
toList = document.getElementById('listeArrivee');

// Création de la liste à partir de la base de données NoSQL
function getSitesList(input, list){
    // On récupère la valeur, sans espaces    
    const inputValue = input.value.trim();    

    // Si elle est vide, on quitte la fonction
    if (inputValue.length === 0) {
        list.innerHTML = '';
        list.style.display = "none";
        return;
    }    

    // On va chercher la liste avec AJAX et on injecte l'input entrée par l'utilisateur
    fetch(`src/get_sites.php?query=${inputValue}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })      
        .then(data => {
            // On efface la liste UL en cours
            list.innerHTML = '';
            data.forEach(location => {
                const listItem = document.createElement('li');
                listItem.textContent = location;
                list.appendChild(listItem);
                list.style.display = "block";

                // Quand on clique sur le nom dans la liste, l'input change
                listItem.addEventListener('click', function () {
                    input.value = location;
                    list.innerHTML = '';
                    list.style.display = "none";
                });
            });
        })
        .catch(error => {
            console.error('Erreur sur l\'autocomplétion :', error);
        });       

}


// gestion de l'autocomplétion au chargement du DOM
function initLocationAutoComplete() {

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


// ******************************************************************
// * GESTION INPUT HEURE
// ******************************************************************


HfromInput = document.getElementById('heureDepart');
HtoInput = document.getElementById('heureArrivee');

// Fonction pour formater la valeur de l'input HEURE
function formatTimeInput(e) {
    let value = e.target.value;
    // Supprimer tous les caractères non numériques sauf le premier deux-points
    const colonIndex = value.indexOf(':');
    if (colonIndex !== -1) {
        // Conserver uniquement le premier deux-points
        const beforeColon = value.substring(0, colonIndex).replace(/[^\d]/g, '');
        const afterColon = value.substring(colonIndex + 1).replace(/[^\d]/g, '').substring(0, 2);
        value = beforeColon + ':' + afterColon;
    } else {
        // Si aucun deux-points, supprimer tous les caractères non numériques
        value = value.replace(/[^\d]/g, '');
    }
    // Ajouter automatiquement les deux-points après deux chiffres si ce n'est pas déjà fait
    if (colonIndex === -1 && value.length > 2) {
        value = value.substring(0, 2) + ':' + value.substring(2);
    }
    // Valider l'heure
    if (value.includes(':') && value.length > 4) {
        const parts = value.split(':');
        const hours = parseInt(parts[0], 10);
        const minutes = parseInt(parts[1], 10);
        if (isNaN(hours) || isNaN(minutes) || hours > 23 || minutes > 59) {
            // Si l'heure n'est pas valide, supprimer l'entrée
            value = '';
        }
    }
    // Mettre à jour la valeur du champ
    e.target.value = value;
}

HfromInput.addEventListener('input', formatTimeInput);
HtoInput.addEventListener('input', formatTimeInput);