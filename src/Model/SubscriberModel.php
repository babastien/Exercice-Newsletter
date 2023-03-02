<?php

namespace App\Model;

use App\Core\AbstractModel;

class SubscriberModel extends AbstractModel {

    /// * Ajoute un abonné dans la BDD * ///
    function addSubscriber(string $email, string $firstname, string $lastname, $origin = null) {

        $sql = 'INSERT INTO subscribers (email, firstname, lastname, originId, createdOn) 
                VALUES (?,?,?,?, NOW())';

        // Ajout des données du nouvel abonné
        $this->db->prepareAndExecute($sql, [$email, $firstname, $lastname, $origin]);

        // On récupère l'id du nouvel abonné pour la fonction addInterests()
        $last_id = $this->db->getLastInsertId();
        
        return $last_id;
    }

    /// * Vérifie si l'email existe déjà dans la BDD * ///
    function verifyEmailExist(string $email) {

        // Récupération de l'email dans la table subscribers
        $sql = 'SELECT * FROM subscribers
                WHERE email = ?';

        return $this->db->verifyData($sql, [$email]);
    }
}