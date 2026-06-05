<?php

namespace App\Service;

class FicheCollecteService {
    private string $scanDir;

    public function __construct() {
        $this->scanDir = __DIR__.'/../../public/asset/fiches_collecte/';
    }

    /*
     * Recherche de la fiche de collecte scannée.
     * */
    public function findScanByMatricule(string $matricule): ?string {
        $extensions = ['pdf', 'jpg', 'jpeg', 'png'];
        foreach ($extensions as $ext) {
            $filename =
                $matricule.'_FC.'.$ext;

            if (file_exists($this->scanDir.$filename)) {
                return $filename;
            }
        }
        return null;
    }
}