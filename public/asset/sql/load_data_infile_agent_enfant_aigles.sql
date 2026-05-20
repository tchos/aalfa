SET GLOBAL local_infile = 1;

SET NAMES utf8mb4;

SHOW VARIABLES LIKE 'character_set%';

-- =========================================
-- Nettoyage des tables
-- =========================================

DELETE FROM enfant;
DELETE FROM agent;

ALTER TABLE enfant AUTO_INCREMENT = 1;
ALTER TABLE agent AUTO_INCREMENT = 1;

-- =========================================
-- Encodage UTF8MB4
-- =========================================

ALTER TABLE agent
CONVERT TO CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

ALTER TABLE enfant
CONVERT TO CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- =========================================
-- IMPORTATION TABLE AGENT
-- =========================================

SELECT '===== IMPORTATION TABLE AGENT =====' AS INFO;

LOAD DATA LOCAL INFILE 'table_agent.csv'
INTO TABLE agent
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(
@id,
@matricule,
@nom_agt,
@date_nais_agt,
@date_emb_agt,
@nb_enft_paye,
@telephone,
@create_at,
@date_collecte,
@recenseur_id
)
SET
id = NULLIF(TRIM(@id), ''),

matricule = NULLIF(TRIM(@matricule), ''),

nom_agt = NULLIF(TRIM(@nom_agt), ''),

date_nais_agt =
CASE
    WHEN TRIM(@date_nais_agt) = '' THEN NULL
    ELSE STR_TO_DATE(TRIM(@date_nais_agt), '%Y-%m-%d')
END,

date_emb_agt =
CASE
    WHEN TRIM(@date_emb_agt) = '' THEN NULL
    ELSE STR_TO_DATE(TRIM(@date_emb_agt), '%Y-%m-%d')
END,

nb_enft_paye =
CASE
    WHEN TRIM(@nb_enft_paye) = '' THEN 0
    ELSE @nb_enft_paye
END,

telephone = NULLIF(TRIM(@telephone), ''),

create_at =
CASE
    WHEN TRIM(@create_at) = '' THEN NULL
    ELSE STR_TO_DATE(TRIM(@create_at), '%Y-%m-%d %H:%i:%s')
END,

date_collecte =
CASE
    WHEN TRIM(@date_collecte) = '' THEN NULL
    ELSE STR_TO_DATE(TRIM(@date_collecte), '%Y-%m-%d')
END,

recenseur_id =
CASE
    WHEN TRIM(@recenseur_id) = '' THEN NULL
    ELSE @recenseur_id
END;

SHOW WARNINGS LIMIT 20;

-- =========================================
-- IMPORTATION TABLE ENFANT
-- =========================================

SELECT '===== IMPORTATION TABLE ENFANT =====' AS INFO;

LOAD DATA LOCAL INFILE 'table_enfant.csv'
INTO TABLE enfant
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(
@id,
@agent_id,
@agent_saisie_id,
@matricule,
@rang,
@ordre,
@nom_enfant,
@date_naissance,
@cec,
@numero_acte,
@date_acte_naissance,
@nom_conjoint,
@enfant_reconnu_y_n,
@region_cec,
@code_arrondissement,
@created_at,
@handicape_yn
)
SET

id = NULLIF(TRIM(@id), ''),

agent_id =
CASE
    WHEN TRIM(@agent_id) = '' THEN NULL
    ELSE @agent_id
END,

agent_saisie_id =
CASE
    WHEN TRIM(@agent_saisie_id) = '' THEN NULL
    ELSE @agent_saisie_id
END,

matricule = NULLIF(TRIM(@matricule), ''),

rang =
CASE
    WHEN TRIM(@rang) = '' THEN NULL
    ELSE @rang
END,

ordre =
CASE
    WHEN TRIM(@ordre) = '' THEN NULL
    ELSE @ordre
END,

nom_enfant = NULLIF(TRIM(@nom_enfant), ''),

date_naissance =
CASE
    WHEN TRIM(@date_naissance) = '' THEN NULL
    ELSE STR_TO_DATE(TRIM(@date_naissance), '%d/%m/%Y')
END,

cec = NULLIF(TRIM(@cec), ''),

numero_acte = NULLIF(TRIM(@numero_acte), ''),

date_acte_naissance =
CASE
    WHEN TRIM(@date_acte_naissance) = '' THEN NULL
    ELSE STR_TO_DATE(TRIM(@date_acte_naissance), '%d/%m/%Y')
END,

nom_conjoint = NULLIF(TRIM(@nom_conjoint), ''),

enfant_reconnu_y_n =
CASE
    WHEN TRIM(@enfant_reconnu_y_n) = '' THEN 0
    ELSE @enfant_reconnu_y_n
END,

region_cec =
CASE
    WHEN TRIM(@region_cec) = '' THEN NULL
    ELSE @region_cec
END,

code_arrondissement = NULLIF(TRIM(@code_arrondissement), ''),

created_at =
CASE
    WHEN TRIM(@created_at) = '' THEN NULL
    ELSE STR_TO_DATE(TRIM(@created_at), '%Y-%m-%d %H:%i:%s')
END,

handicape_yn =
CASE
    WHEN TRIM(@handicape_yn) = '' THEN 0
    ELSE @handicape_yn
END;

SHOW WARNINGS LIMIT 20;

-- =========================================
-- Vérification finale
-- =========================================

SELECT COUNT(*) AS NOMBRE_AGENTS FROM agent;

SELECT COUNT(*) AS NOMBRE_ENFANTS FROM enfant;