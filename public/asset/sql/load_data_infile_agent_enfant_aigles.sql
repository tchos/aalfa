-- =====================================================
-- Activation du chargement local
-- =====================================================

SET GLOBAL local_infile = 1;

SET NAMES utf8mb4;

SHOW VARIABLES LIKE 'character_set%';

-- =====================================================
-- Vidage des tables
-- =====================================================

DELETE FROM enfant;
DELETE FROM agent;

ALTER TABLE enfant AUTO_INCREMENT = 1;
ALTER TABLE agent AUTO_INCREMENT = 1;

-- =====================================================
-- Encodage UTF8
-- =====================================================

ALTER TABLE agent
CONVERT TO CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

ALTER TABLE enfant
CONVERT TO CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- =====================================================
-- IMPORTATION AGENT
-- =====================================================

SELECT '===== IMPORTATION TABLE AGENT =====' AS INFO;

LOAD DATA LOCAL INFILE 'table_agent.csv'
INTO TABLE agent
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
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
@recenseur_id,
@nb_enft_collecte,
@saisie_terminee,
@date_validation,
@fiche_collecte
)
SET
id = @id,
matricule = @matricule,
nom_agt = @nom_agt,
date_nais_agt = NULLIF(@date_nais_agt,''),
date_emb_agt = NULLIF(@date_emb_agt,''),
nb_enft_paye = CAST(IFNULL(NULLIF(@nb_enft_paye,''),0) AS UNSIGNED),
telephone = NULLIF(@telephone,''),
create_at = NULLIF(@create_at,''),
date_collecte = NULLIF(@date_collecte,''),
recenseur_id = NULLIF(@recenseur_id,''),
nb_enft_collecte = NULLIF(@nb_enft_collecte,''),
saisie_terminee = CAST(IFNULL(NULLIF(@saisie_terminee,''),0) AS UNSIGNED),
date_validation = NULLIF(@date_validation,''),
fiche_collecte = NULLIF(@fiche_collecte,'');

SHOW WARNINGS LIMIT 20;

-- =====================================================
-- IMPORTATION ENFANT
-- =====================================================

SELECT '===== IMPORTATION TABLE ENFANT =====' AS INFO;

LOAD DATA LOCAL INFILE 'table_enfant.csv'
INTO TABLE enfant
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(
@id,
@agent_id,
@agent_saisie_id,
@matricule,
@rang,
@nom_enfant,
@date_naissance,
@numero_acte,
@date_acte_naissance,
@nom_conjoint,
@enfant_reconnu_y_n,
@created_at,
@handicape_yn,
@centre_etat_civil_id
)
SET
id = @id,
agent_id = NULLIF(@agent_id,''),
agent_saisie_id = NULLIF(@agent_saisie_id,''),
matricule = @matricule,
rang = CAST(@rang AS UNSIGNED),
nom_enfant = @nom_enfant,
date_naissance = STR_TO_DATE(NULLIF(@date_naissance,''),'%d/%m/%Y'),
numero_acte = NULLIF(@numero_acte,''),
date_acte_naissance = STR_TO_DATE(NULLIF(@date_acte_naissance,''),'%d/%m/%Y'),
nom_conjoint = NULLIF(@nom_conjoint,''),
enfant_reconnu_y_n = CAST(IFNULL(NULLIF(@enfant_reconnu_y_n,''),0) AS UNSIGNED),
created_at = NULLIF(@created_at,''),
handicape_yn = CAST(IFNULL(NULLIF(@handicape_yn,''),0) AS UNSIGNED),
centre_etat_civil_id =
CASE
WHEN TRIM(@centre_etat_civil_id) = '' THEN NULL
ELSE CAST(@centre_etat_civil_id AS UNSIGNED)
END


SHOW WARNINGS LIMIT 20;

-- =====================================================
-- CONTROLES
-- =====================================================

SELECT COUNT(*) AS NB_AGENTS
FROM agent;

SELECT COUNT(*) AS NB_ENFANTS
FROM enfant;

SELECT
    COUNT(*) AS ENFANTS_SANS_AGENT
FROM enfant e
LEFT JOIN agent a
    ON e.agent_id = a.id
WHERE a.id IS NULL;


SHOW WARNINGS LIMIT 20;


-- =========================================
-- Vérification finale
-- =========================================

SELECT COUNT(*) AS NOMBRE_AGENTS FROM agent;

SELECT COUNT(*) AS NOMBRE_ENFANTS FROM enfant;

SELECT 
    a.matricule,
    a.nom_agt,
    e.nom_enfant,
    e.date_naissance,
    e.rang,
    e.handicape_yn
FROM agent a
INNER JOIN enfant e
    ON a.id = e.agent_id
WHERE a.matricule = '1077334D';