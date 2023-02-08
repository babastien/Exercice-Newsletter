<?php

// * Connecte à la BDD * //
function databaseConnexion() {

    // Construction du Data Source Name
    $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;

    // Tableau d'options pour la connexion PDO
    $options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    // Création de la connexion PDO (création d'un objet PDO)
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
    $pdo->exec('SET NAMES UTF8');
    
    return $pdo;
}


// * Vérifie que le formulaire est correctement rempli * //
function validForm(string $email, string $firstname, string $lastname, $origin, $interest) {

    $pdo = databaseConnexion();

    $errors = [];

    if(!$email) {
        $errors['email'] = "Merci d'indiquer une adresse email";
    } elseif(verifyEmail($email, $pdo) == true) {
        $errors["email"] = "Cette adresse email est déjà utilisée";
    }

    if(!$firstname) {
        $errors['firstname'] = "Merci d'indiquer un prénom";
    }

    if(!$lastname) {
        $errors['lastname'] = "Merci d'indiquer un nom";
    }

    if(!$origin) {
        $errors['origin'] = "Merci de nous dire comment vous avez connu notre site";
    }

    if(!$interest) {
        $errors['interest'] = "Merci de choisir au moins un centre d'intérêt";
    }

    return $errors;
}


// * Vérifie si l'email existe déjà dans la BDD * //
function verifyEmail(string $email) {

    // Connexion à la BDD
    $pdo = databaseConnexion();

    // Récupération de l'email dans la table subscribers
    $sql = 'SELECT * FROM subscribers WHERE email = ?';

    $query = $pdo->prepare($sql);
    $query->execute(array($email));
    $emailExist = $query->rowCount();

    if($emailExist != 0) {
        return true;
    }
}


// * Récupère tous les labels de la table origins * //
function getAllOrigins() {

    // Connexion à la BDD
    $pdo = databaseConnexion();

    // Récupération des labels d'origine
    $sql = 'SELECT * FROM origin ORDER BY origin_label';

    $query = $pdo->prepare($sql);
    $query->execute();

    return $query->fetchAll();
}


// * Récupère tous les labels de la table interests * //
function getAllInterests() {

    // Connexion à la BDD
    $pdo = databaseConnexion();

    // Récupération des labels d'intérêts
    $sql = 'SELECT * FROM interests ORDER BY interest_label';

    $query = $pdo->prepare($sql);
    $query->execute();

    return $query->fetchAll();
}


// * Ajoute un abonné dans la BDD * //
function addSubscriber(string $email, string $firstname, string $lastname, int $origin) {

    // Connexion à la BDD
    $pdo = databaseConnexion();

    // Insertion des données du nouvel abonné
    $sql = 'INSERT INTO subscribers
            (email, firstname, lastname, origin_id, created_on) 
            VALUES (?,?,?,?, NOW())';

    $query = $pdo->prepare($sql);
    $query->execute([$email, $firstname, $lastname, $origin]);

    // On récupère l'id du nouvel abonné pour la fonction addInterests()
    $last_id = $pdo->lastInsertId();

    return $last_id;

}


//  * Ajoute les intérêts sélectionnés dans la table de liaison de la BDD * //
function addInterests(array $interest, int $last_id) {

    // Connexion à la BDD
    $pdo = databaseConnexion();

    foreach($interest as $interest_checked) {

        // Insertion des intérêts à l'aide de la variable $last_id
        $sql = 'INSERT INTO subscribers_interests (subscribers_id, interests_id)
                VALUES (?, ?)';

        $query = $pdo->prepare($sql);
        $query->execute([$last_id, $interest_checked]);
    }
}