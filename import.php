<?php

require 'config.php';
require 'functions.php';

//  * On va récupérer les paramètres de la commande dans la variable prédéfinie $argv
//  * $argv contient un tableau dont le premier élément est le nom du fichier PHP
//  * Les autres éléments du tableau sont les paramètres suivants
//  * Si je lance la commande "php import.php subscribers.csv" à la raçine, je vais récupérer dans $argv le tableau : 
//  *  
//  * array (2) {
//  *    0 => "import.php",
//  *    1 => "subscribers.csv"
//  * }

//  * Ici le seul paramètre est le nom du fichier CSV que je souhaite importer
//  * On va donc le récupérer dans la 2ème case du tableau $argv
$filename = $argv[1];

//  * On vérifie que le fichier existe bien. S'il n'existe pas on affiche simplement un message d'erreur
if (!file_exists($filename)) {
    echo "Erreur : fichier '$filename' introuvable";
    exit; // On arrête l'exécution du script
}

//  * Si on arrive là c'est que le fichier existe bien, on va l'ouvrir en lecture grâce à la fonction fopen()
$file = fopen($filename, "r");

//  * Connexion à la BDD
$pdo = databaseConnexion();

$pdoStatement = $pdo->prepare(
    'INSERT INTO subscribers (firstname, lastname, email, created_on) VALUES (?,?,?, NOW())'
);

//  * Ensuite on va lire chaque ligne du fichier CSV avec la fonction fgetcsv() tant qu'il y a des lignes à lire
//  * S'il n'y a plus de nouvelle ligne, fgetcsv() retourne false.
while ($row = fgetcsv($file)) {

    //  * $row représente une ligne du fichier CSV, les données sont récupérées dans un tableau
    //  * [0] cible la donnée avant la première virgule, [1] après la 1ère virgule, [2] après la 2ème, etc
    //  * La première colonne qui est [0] est le prénom de l'abonné
    //  * La deuxième colonne qui est [1] est le nom de l'abonné
    //  * La troisième colonne qui est [2] est l'email de l'abonné
    $firstname = $row[0];
    $lastname = $row[1];
    $email = $row[2];

    //  * Traitement des données : 
    //  * - On formate les prénoms et noms en minuscule puis on applique des majuscules aux initiales
    //  * - On formate les emails en minuscule et on supprime les espaces si il y en a
    $firstname = strtolower($firstname);
    $firstname = ucwords($firstname, " -");
    $lastname = strtolower($lastname);
    $lastname = ucwords($lastname, " -");
    $email = strtolower($email);
    $email = str_replace(" ", "", $email);

    //  * On vérifie que l'email n'existe pas déjà dans la BDD à l'aide de la fonction éponyme
    if(verifyEmail($email) != true) {

        //  * Si non, on enregistre l'abonné dans la BDD en exécutant la requête préparée plus haut
        $pdoStatement->execute([$firstname, $lastname, $email]);

    } else {
        //  * Si l'email existe déjà, on le notifie
        echo "L'email $email est déjà dans la base de données\n";
    } 
}

echo "Import terminé!";
