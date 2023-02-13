<?php

// Démarre et restaure une session
session_start();
// Cela permet avec les 2 prochains if d'éviter le renvoi de formulaire lorsqu'on rafraîchit la...
// ...page après soumission du formulaire contrairement à "header('Location : index.php');" qui...
// ...fonctionne également mais ne permet pas d'afficher le message de succès après la soumission

if (!empty($_POST)) {
	$_SESSION["formulaire_envoye"] = $_POST;
	header("Location: ".$_SERVER["PHP_SELF"]);
	exit;
}

if (isset($_SESSION["formulaire_envoye"])) {
	$_POST = $_SESSION["formulaire_envoye"];
	unset($_SESSION["formulaire_envoye"]);
}

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

    // On traite les données pour les stocker au bon format
    $firstname = strtolower($firstname);
    $firstname = ucwords($firstname, " -");
    $lastname = strtolower($lastname);
    $lastname = ucwords($lastname, " -");
    $email = strtolower($email);
    $email = str_replace(" ", "", $email);

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
                
        // Ajout du nouvel abonné dans la BDD
        $last_id = addSubscriber($email, $firstname, $lastname, $origin);
        // Puis ajout de ses intérêts dans la BDD
        addInterests($interest, $last_id);
        // Message de succès
        $success  = "Inscription réussie";

        // header('Location: index.php');
        // exit();
    }
}

///////////////////////////////
/// AFFICHAGE DU FORMULAIRE ///
///////////////////////////////

// Affichage des listes d'origines et d'intérêts contenus dans la BDD
$originsFromBDD = getAllOrigins();
$interestsFromBDD = getAllInterests();

// Inclusion du template
include 'index.phtml';
