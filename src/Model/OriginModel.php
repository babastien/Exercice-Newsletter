<?php

namespace App\Model;

use App\Core\AbstractModel;
use App\Entity\Origin;

class OriginModel extends AbstractModel {

    /// * Récupère tous les labels de la table origins * ///
    function getAllOrigins() {

        // Récupération des labels d'origine
        $sql = 'SELECT * FROM origin
                ORDER BY originLabel';

        $results = $this->db->getAllResults($sql);

        $origins = [];
        foreach ($results as $result) {
            $origins[] = new Origin($result);
        }

        return $origins;
    }
}