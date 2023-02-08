<?php

// Inclusion des dépendances
require 'config.php';
require 'functions.php';

// Initialisation des variables
$errors = [];
$success = null;
$email = '';
$firstname = '';
$lastname = '';

// Si le formulaire a été soumis...
if (!empty($_POST)) {

    // On récupère les données
    $email = trim($_POST['email']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);

    // On récupère l'origine
    $origin = $_POST['origin'];

    $interest = '';
    // On vérifie si des intérêts ont été sélectionnés pour éviter de générer une erreur
    if(isset($_POST['interest'])) {
        // Si c'est le cas, on les récupère
        $interest = $_POST['interest'];
    }

    $errors = validForm($email, $firstname, $lastname, $origin, $interest);

    // Si le tableau d'erreur est vide...
    if (empty($errors)) {
                
        // Ajout du nouvel abonné dans la BDD...
        $last_id = addSubscriber($email, $firstname, $lastname, $origin);
        // Puis ajout de ses intérêts dans la BDD
        addInterests($interest, $last_id);
        // Message de succès
        $success  = 'Inscription réussie';
        
        header("Location: index.php");
        exit();
    }
}

///////////////////////////////
/// AFFICHAGE DU FORMULAIRE ///
///////////////////////////////

// Affichage des listes d'origines et d'intérêts
$origins = getAllOrigins();
$interests = getAllInterests();

// Inclusion du template
include 'index.phtml';
