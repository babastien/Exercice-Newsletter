<?php

require 'config.php';

/**
 * On va récupérer les paramètres de la commande dans la variable prédéfinie $argv
 * $argv contient un tableau dont le premier élément est le nom du fichier PHP
 * Les autres éléments du tableau sont les paramètres suivants
 * Je lance la commande "php import.php subscribers.csv", je vais récupérer dans $argv le tableau : 
 *  
 * array (4) {
 *    0 => "import.php",
 *    1 => "subscribers.csv"
 * }
 */

/**
 * Ici le seul paramètre est le nom du fichier CSV que je souhaite importer
 * On va donc le récupérer dans la 2ème case du tableau $argv
 */

$filename = $argv[1];

/**
 * On vérifie que le fichier existe bien. S'il n'existe pas on affiche simplement un message d'erreur
 */
if (!file_exists($filename)) {
    echo "Erreur : fichier '$filename' introuvable";
    exit; // On arrête l'exécution du script
}

/**
 * Si on arrive là c'est que le fichier existe bien, on va l'ouvrir en lecture
 * grâce à la fonction fopen()
 */
$file = fopen($filename, "r");

/**
 * On se connecte à la base de données avec PDO et on prépare la requête d'insertion
 */
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdoStatement = $pdo->prepare(
    'INSERT INTO subscribers (firstname, lastname, email, created_on) VALUES (?,?,?,?)'
);

/**
 * Ensuite on va lire chaque ligne du fichier CSV avec la fonction fgetcsv()
 * tant qu'il y a des lignes à lire. S'il n'y a plus de nouvelle ligne, fgetcsv() retourne false.
 */
while ($row = fgetcsv($file)) {

    /**
     * $row représente une ligne du fichier CSV, les données sont récupérées dans un tableau
     * La première colone est le prénom de l'abonné
     * La deuxième colone est le nom de l'abonné
     * La troisième colone est l'email de l'abonné
     */
    $firstname = $row[0];
    $lastname = $row[1];
    $email = $row[2];
    $created_on = new DateTime();

    /**
     * Traitement des données : 
     * - On convertit les prénoms et noms pour que la première lettre soit en majuscule
     * - Les autres lettres sont en minuscule
     * - On supprime les espaces dans les emails
     */
    $firstname = strtolower($firstname);
    $firstname = ucwords($firstname, " -");
    $lastname = strtolower($lastname);
    $lastname = ucwords($lastname, " -");
    $email = strtolower($email);
    $email = str_replace(" ", "", $email);

    /**
     * On enregistre ensuite le produit dans la base de données en exécutant la requête préparée plus haut
     */
    $pdoStatement->execute([$firstname, $lastname, $email, $created_on->format("Y-m-d H:i:s")]);
}

echo 'Import terminé!';
