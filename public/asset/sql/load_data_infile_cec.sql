SET GLOBAL local_infile = 1;

-- =========================================
-- Nettoyage des tables
-- =========================================

DELETE FROM centre_etat_civil;

ALTER TABLE centre_etat_civil AUTO_INCREMENT = 1;


-- =========================================
-- IMPORTATION TABLE centre_etat_civil
-- =========================================

LOAD DATA LOCAL INFILE 'table_cec.csv'
INTO TABLE centre_etat_civil
CHARACTER SET latin1
FIELDS TERMINATED BY ';'
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(
    @id,
    code_cec,
    libelle_cec,
    arrondissement,
    departement,
    region
);

-- =========================================
-- Vérification finale
-- =========================================

SELECT COUNT(*) AS NOMBRE_CEC FROM centre_etat_civil;

-- =========================================
-- Annulation des saisies effectuées
-- =========================================

UPDATE agent
SET
    date_collecte = NULL,
    create_at = NULL,
    telephone = NULL,
    recenseur_id = NULL,
    nb_enft_collecte = NULL,
    saisie_terminee = 0,
    date_validation = NULL;

UPDATE enfant
SET
    agent_saisie_id = NULL,
    numero_acte = NULL,
    date_acte_naissance = NULL,
    nom_conjoint = NULL,
    enfant_reconnu_y_n = 0,
    created_at = NULL,
    handicape_yn = 0,
    centre_etat_civil_id = NULL
WHERE enfant_reconnu_y_n = 1;
