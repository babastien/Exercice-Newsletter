<?php

namespace App\Core;

use PDO;

class Database {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = $this->databaseConnexion();
    }

    /// * Connecte à la base de données * ///
    function databaseConnexion() {

        $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;
        $options = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
        $pdo->exec('SET NAMES UTF8');
        return $pdo;
    }

    /* Prépare et exécute une requête SQL */
    function prepareAndExecute(string $sql, array $values = []) {

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute($values);

        return $pdoStatement;
    }

    /* Exécute une requête de sélection et retourne un résultat */
    function getOneResult(string $sql, array $values = []) {

        $pdoStatement = $this->prepareAndExecute($sql, $values);
        return $pdoStatement->fetch();
    }

    /* Exécute une requête de sélection et retourne tous les résultat */
    function getAllResults(string $sql, array $values = []) {

        $pdoStatement = $this->prepareAndExecute($sql, $values);
        return $pdoStatement->fetchAll();
    }

    /* Vérifie si une donnée existe dans la BDD */
    function verifyData(string $sql, array $values = []) {
        
        $pdoStatement = $this->prepareAndExecute($sql, $values);
        if($pdoStatement->rowCount() == 1) {
            return true;
        }
    }

    /* Récupère l'id du dernier abonné */
    function getLastInsertId() {

        $last_id = $this->pdo->lastInsertId();
        return $last_id;
    }
}