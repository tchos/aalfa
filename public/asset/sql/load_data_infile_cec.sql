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
