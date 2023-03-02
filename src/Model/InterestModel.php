<?php

namespace App\Model;

use App\Core\AbstractModel;
use App\Entity\Interest;

class InterestModel extends AbstractModel {

    /// * Récupère tous les labels de la table interests * ///
    function getAllInterests() {

        // Récupération des labels d'intérêts
        $sql = 'SELECT * FROM interests
                ORDER BY interestLabel';

        $results = $this->db->getAllResults($sql);

        $interests = [];
        foreach ($results as $result) {
            $interests[] = new Interest($result);
        }

        return $interests;
    }

    /// * Ajoute les intérêts sélectionnés dans la table de liaison de la BDD * ///
    function addInterests(array $interest, int $last_id) {

        foreach($interest as $interest_checked) {

            // Ajout des intérêts à l'aide de la variable $last_id
            $sql = 'INSERT INTO subscribers_interests (subscribers_id, interests_id)
                    VALUES (?, ?)';

            $this->db->prepareAndExecute($sql, [$last_id, $interest_checked]);
        }
    }
}