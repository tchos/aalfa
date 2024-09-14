LOAD DATA LOCAL INFILE 'table_agents.csv' INTO TABLE agent CHARACTER SET LATIN1 FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n' IGNORE 1 LINES (id,matricule,nom_agt,date_nais_agt,date_emb_agt,nb_enft_paye);
LOAD DATA LOCAL INFILE 'table_enfants.csv' INTO TABLE enfant CHARACTER SET LATIN1 FIELDS TERMINATED BY ';' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 LINES (agent_id,matricule,rang,ordre,nom_enfant,date_naissance,cec,numero_acte,date_acte_naissance,nom_conjoint,enfant_reconnu_y_n,region_cec);





