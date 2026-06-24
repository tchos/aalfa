SET GLOBAL local_infile = 1;

-- ====================================================
-- Nettoyage des tables
-- ====================================================
ALTER TABLE recenseur DROP INDEX UNIQ_BFE7CB8512B2DC9C;

DELETE FROM centre_etat_civil;
DELETE FROM recenseur;
DELETE FROM equipe;

ALTER TABLE centre_etat_civil AUTO_INCREMENT = 1;
ALTER TABLE recenseur AUTO_INCREMENT = 1;
ALTER TABLE equipe AUTO_INCREMENT = 1;

-- =====================================================
-- Encodage UTF8
-- =====================================================

ALTER TABLE centre_etat_civil 
CONVERT TO CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

ALTER TABLE recenseur
CONVERT TO CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

ALTER TABLE equipe
CONVERT TO CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;


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

UPDATE agent
SET
    date_collecte = NULL,
    create_at = NULL,
    telephone = NULL,
    recenseur_id = NULL,
    nb_enft_collecte = NULL,
    saisie_terminee = 0,
    date_validation = NULL;

-- =========================================
-- IMPORTATION TABLE equipe
-- =========================================

LOAD DATA LOCAL INFILE 'equipe.csv'
INTO TABLE equipe
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(
    id,
    libelle,
    code,
    chef,
    coordonnateur
);

-- =========================================
-- Vérification finale
-- =========================================

SELECT COUNT(*) AS NOMBRE_CEC FROM equipe;

-- =========================================
-- IMPORTATION TABLE recenseur
-- =========================================

LOAD DATA LOCAL INFILE 'recenseur.csv'
INTO TABLE recenseur
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(
    @id,
    @equipe_id,
    @nom,
    @code,
    @matricule
)
SET
    id = @id,
    equipe_id = NULLIF(@equipe_id, ''),
    nom = TRIM(@nom),
    code = TRIM(@code),
    matricule = TRIM(@matricule);

SHOW WARNINGS LIMIT 20;

-- =========================================
-- Vérification finale
-- =========================================

SELECT COUNT(*) AS nb_recenseurs FROM recenseur;

UPDATE centre_etat_civil SET libelle_cec = LTRIM(libelle_cec);

UPDATE centre_etat_civil SET libelle_cec = REPLACE(libelle_cec, ' LLL', ' III');
UPDATE centre_etat_civil SET libelle_cec = REPLACE(libelle_cec, ' LL', ' II');
UPDATE centre_etat_civil SET libelle_cec = REPLACE(libelle_cec, ' L', ' I');
UPDATE centre_etat_civil SET libelle_cec = REPLACE(libelle_cec, ' VLLL', ' VIII');
UPDATE centre_etat_civil SET libelle_cec = REPLACE(libelle_cec, ' VLL', ' VII');
UPDATE centre_etat_civil SET libelle_cec = REPLACE(libelle_cec, ' VL', ' VI');
UPDATE centre_etat_civil SET libelle_cec = REPLACE(libelle_cec, ' Ill', ' III');
UPDATE centre_etat_civil SET libelle_cec = REPLACE(libelle_cec, ' Il', ' II');
UPDATE centre_etat_civil SET libelle_cec = REPLACE(libelle_cec, ' ll', ' II');
UPDATE centre_etat_civil SET libelle_cec = REPLACE(libelle_cec, ' l', ' I');
UPDATE centre_etat_civil SET libelle_cec = REPLACE(libelle_cec, ' Vlll', ' VIII');
UPDATE centre_etat_civil SET libelle_cec = REPLACE(libelle_cec, ' Vll', ' VII');
UPDATE centre_etat_civil SET libelle_cec = REPLACE(libelle_cec, ' Vl', ' VI');

UPDATE centre_etat_civil SET arrondissement = REPLACE(arrondissement, ' lll', ' III');
UPDATE centre_etat_civil SET arrondissement = REPLACE(arrondissement, ' ll', ' II');
UPDATE centre_etat_civil SET arrondissement = REPLACE(arrondissement, ' l', ' I');
UPDATE centre_etat_civil SET arrondissement = REPLACE(arrondissement, ' Vlll', ' VIII');
UPDATE centre_etat_civil SET arrondissement = REPLACE(arrondissement, ' Vll', ' VII');
UPDATE centre_etat_civil SET arrondissement = REPLACE(arrondissement, ' lX', ' IX');
UPDATE centre_etat_civil SET arrondissement = REPLACE(arrondissement, ' Vl', ' VI');
UPDATE centre_etat_civil SET arrondissement = REPLACE(arrondissement, ' lV', ' IV');
