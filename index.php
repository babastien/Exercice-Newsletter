<?php

use App\Model\OriginModel;
use App\Model\InterestModel;
use App\Model\SubscriberModel;

session_start();

// Inclusion des dépendances
require 'vendor/autoload.php';
require 'app/config.php';
require 'lib/functions.php';

$originModel = new OriginModel();
$interestModel = new InterestModel();
$subscriberModel = new SubscriberModel();

// Initialisation des variables
$errors = [];
$success = null;
$email = '';
$firstname = '';
$lastname = '';

// Message flash
if (array_key_exists('success', $_SESSION) && $_SESSION['success']) {
    $success = $_SESSION['success'];
    $_SESSION['success'] = null;
}

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
        $last_id = $subscriberModel->addSubscriber($email, $firstname, $lastname, $origin);
        // Puis ajout de ses intérêts dans la BDD
        $interestModel->addInterests($interest, $last_id);
        // On stocke un message de succès dans une session
        $_SESSION['success'] = "Inscription réussie";

        header('Location: index.php');
        exit();
    }
}

///////////////////////////////
/// AFFICHAGE DU FORMULAIRE ///
///////////////////////////////

// Affichage des listes d'origines et d'intérêts contenus dans la BDD
$originsFromBDD = $originModel->getAllOrigins();
$interestsFromBDD = $interestModel->getAllInterests();

// Inclusion du template
include 'index.phtml';
