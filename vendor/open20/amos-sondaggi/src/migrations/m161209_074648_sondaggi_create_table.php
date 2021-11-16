<?php

use yii\db\Migration;

class m161209_074648_sondaggi_create_table extends Migration {

    private $tabella = null;

    public function safeUp() {
        $this->execute("SET FOREIGN_KEY_CHECKS=0;");
        $this->execute("
           

-- -----------------------------------------------------
-- Table `sondaggi_accessi_servizi`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sondaggi_accessi_servizi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `data_ora_accesso` DATETIME NULL DEFAULT NULL COMMENT 'Data e ora accesso',
  `tipo_accesso` ENUM('Individuale', 'In Gruppo') NULL DEFAULT 'Individuale' COMMENT 'Tipo accesso',
  `durata_accesso_minuti` INT(11) NULL DEFAULT NULL COMMENT 'Durata accesso in minuti',
  `user_id` INT(11) NULL COMMENT 'Utente',
  `sede_id` INT(11) NULL DEFAULT NULL COMMENT 'Sede',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Creato il',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Aggiornato il',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Cancellato il',
  `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Creato da',
  `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Aggiornato da',
  `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Cancellato da',
  `version` INT(11) NULL DEFAULT NULL COMMENT 'Versione',
  PRIMARY KEY (`id`),
  INDEX `fk_sondaggi_accessi_servizi_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_sondaggi_accessi_servizi_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sondaggi_stato`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sondaggi_stato` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `stato` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Stato',
  `descrizione` TEXT NULL DEFAULT NULL COMMENT 'Descrizione',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Creato il',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Aggiornato il',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Cancellato il',
  `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Creato da',
  `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Aggiornato da',
  `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Cancellato da',
  `version` INT(11) NULL DEFAULT NULL COMMENT 'Versione numero',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sondaggi_temi`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sondaggi_temi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `titolo` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Titolo',
  `descrizione` TEXT NULL DEFAULT NULL COMMENT 'Descrizione',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Creato il',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Aggiornato il',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Cancellato il',
  `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Creato da',
  `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Aggiornato da',
  `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Cancellato da',
  `version` INT(11) NULL DEFAULT NULL COMMENT 'Versione',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sondaggi`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sondaggi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `titolo` TEXT NOT NULL COMMENT 'Titolo',
  `descrizione` TEXT NULL DEFAULT NULL COMMENT 'Descrizione',
  `compilazioni_disponibili` INT(11) NULL DEFAULT '0' COMMENT 'Compilazioni possibili (0 = senza limiti)',
  `sondaggi_stato_id` INT(11) NOT NULL DEFAULT '1',
  `filemanager_mediafile_id` INT(11) NULL DEFAULT NULL COMMENT 'Immagine',
  `sondaggi_temi_id` INT(11) NULL DEFAULT NULL COMMENT 'Tema',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Creato il',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Aggiornato il',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Cancellato il',
  `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Creato da',
  `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Aggiornato da',
  `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Cancellato da',
  `version` INT(11) NULL DEFAULT NULL COMMENT 'Versione',
  PRIMARY KEY (`id`),
  INDEX `fk_sondaggi_filemanager_mediafile1_idx` (`filemanager_mediafile_id` ASC),
  INDEX `fk_sondaggi_sondaggi_stato1_idx` (`sondaggi_stato_id` ASC),
  INDEX `fk_sondaggi_sondaggi_temi1_idx` (`sondaggi_temi_id` ASC),
  CONSTRAINT `fk_sondaggi_filemanager_mediafile1`
    FOREIGN KEY (`filemanager_mediafile_id`)
    REFERENCES `filemanager_mediafile` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sondaggi_sondaggi_stato1`
    FOREIGN KEY (`sondaggi_stato_id`)
    REFERENCES `sondaggi_stato` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sondaggi_sondaggi_temi1`
    FOREIGN KEY (`sondaggi_temi_id`)
    REFERENCES `sondaggi_temi` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sondaggi_accessi_servizi_configurazione`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sondaggi_accessi_servizi_configurazione` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sondaggi_id` INT(11) NOT NULL COMMENT 'Sondaggio',
  `obbligatorio` INT(11) NULL DEFAULT '0' COMMENT 'Obbligatorio',
  `precompilato` INT(11) NULL DEFAULT '0' COMMENT 'Precompilato',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Creato il',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Aggiornato il',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Cancellato il',
  `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Creato da',
  `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Aggiornato da',
  `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Cancellato da',
  `version` INT(11) NULL DEFAULT NULL COMMENT 'Versione',
  PRIMARY KEY (`id`),
  INDEX `fk_sondaggi_accessi_servizi_configurazione_sond_idx` (`sondaggi_id` ASC),
  CONSTRAINT `fk_sondaggi_accessi_servizi_configurazione_sond1`
    FOREIGN KEY (`sondaggi_id`)
    REFERENCES `sondaggi` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sondaggi_domande_pagine`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sondaggi_domande_pagine` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sondaggi_id` INT(11) NOT NULL COMMENT 'Sondaggio',
  `titolo` VARCHAR(255) NULL DEFAULT NULL,
  `descrizione` TEXT NULL DEFAULT NULL,
  `ordinamento` INT(11) NULL DEFAULT NULL COMMENT 'Ordinamento',
  `filemanager_mediafile_id` INT(11) NULL DEFAULT NULL COMMENT 'Immagine',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Creato il',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Aggiornato il',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Cancellato il',
  `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Creato da',
  `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Aggiornato da',
  `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Cancellato da',
  `version` INT(11) NULL DEFAULT NULL COMMENT 'Versione',
  PRIMARY KEY (`id`),
  INDEX `fk_sondaggi_domande_pagine_sondaggi1_idx` (`sondaggi_id` ASC),
  INDEX `fk_sondaggi_domande_pagine_filemanager_mediafile1_idx` (`filemanager_mediafile_id` ASC),
  CONSTRAINT `fk_sondaggi_domande_pagine_filemanager_mediafile1`
    FOREIGN KEY (`filemanager_mediafile_id`)
    REFERENCES `filemanager_mediafile` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sondaggi_domande_tipologie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sondaggi_domande_tipologie` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tipologia` VARCHAR(255) NOT NULL COMMENT 'Tipologia',
  `descrizione` TEXT NULL DEFAULT NULL COMMENT 'Descrizione',
  `attivo` INT(11) NULL DEFAULT '0' COMMENT 'Attivo',
  `html_type` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Type HTML',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Creato il',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Aggiornato il',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Cancellato il',
  `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Creato da',
  `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Aggiornato da',
  `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Cancellato da',
  `version` INT(11) NULL DEFAULT NULL COMMENT 'Versione',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sondaggi_domande`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sondaggi_domande` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `domanda_condizionata` INT(11) NULL DEFAULT '0' COMMENT 'Domanda condizionata',
  `domanda` TEXT NOT NULL COMMENT 'Domanda',
  `obbligatoria` INT(11) NULL DEFAULT '0' COMMENT 'Obbligatoria',
  `inline` INT(11) NULL DEFAULT '0' COMMENT 'Visualizzazione risposte (funziona solo per checkbox, radio e immagini)',
  `sondaggi_id` INT(11) NOT NULL COMMENT 'Sondaggio',
  `ordinamento` INT(11) NULL DEFAULT NULL,
  `min_int_multipla` INT(11) NULL DEFAULT '0' COMMENT 'Selezioni minime',
  `max_int_multipla` INT(11) NULL DEFAULT '0' COMMENT 'Selezioni massime',
  `nome_classe_validazione` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Nome della classe Validatrice',
  `sondaggi_domande_pagine_id` INT(11) NOT NULL COMMENT 'Pagina',
  `sondaggi_domande_tipologie_id` INT(11) NOT NULL COMMENT 'Tipo risposta',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Creato il',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Aggiornato il',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Cancellato il',
  `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Creato da',
  `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Aggiornato da',
  `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Cancellato da',
  `version` INT(11) NULL DEFAULT NULL COMMENT 'Versione',
  PRIMARY KEY (`id`),
  INDEX `fk_sondaggi_domande_sondaggi1_idx` (`sondaggi_id` ASC),
  INDEX `fk_sondaggi_domande_sondaggi_domande_pagine1_idx` (`sondaggi_domande_pagine_id` ASC),
  INDEX `fk_sondaggi_domande_sondaggi_domande_tipologi_idx` (`sondaggi_domande_tipologie_id` ASC),
  CONSTRAINT `fk_sondaggi_domande_sondaggi_domande_pagine1`
    FOREIGN KEY (`sondaggi_domande_pagine_id`)
    REFERENCES `sondaggi_domande_pagine` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sondaggi_domande_sondaggi_domande_tipologie1`
    FOREIGN KEY (`sondaggi_domande_tipologie_id`)
    REFERENCES `sondaggi_domande_tipologie` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sondaggi_risposte_predefinite`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sondaggi_risposte_predefinite` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `risposta` TEXT NULL DEFAULT NULL COMMENT 'Risposta predefinita',
  `sondaggi_domande_id` INT(11) NOT NULL COMMENT 'Domanda',
  `ordinamento` INT(11) NULL DEFAULT '0',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Creato il',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Aggiornato il',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Cancellato il',
  `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Creato da',
  `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Aggiornato da',
  `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Cancellato da',
  `version` INT(11) NULL DEFAULT NULL COMMENT 'Versione',
  PRIMARY KEY (`id`),
  INDEX `fk_sondaggi_risposte_predefinite_sondaggi_dom_idx` (`sondaggi_domande_id` ASC),
  CONSTRAINT `fk_sondaggi_risposte_predefinite_sondaggi_doman1`
    FOREIGN KEY (`sondaggi_domande_id`)
    REFERENCES `sondaggi_domande` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sondaggi_domande_condizionate`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sondaggi_domande_condizionate` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sondaggi_risposte_predefinite_id` INT(11) NOT NULL COMMENT 'Risposta attesa',
  `sondaggi_domande_id` INT(11) NOT NULL COMMENT 'Domanda condizionata',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Creato il',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Aggiornato il',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Cancellato il',
  `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Creato da',
  `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Aggiornato da',
  `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Cancellato da',
  `version` INT(11) NULL DEFAULT NULL COMMENT 'Versione',
  PRIMARY KEY (`id`),
  INDEX `fk_sondaggi_domande_condizionate_sondaggi_dom_idx` (`sondaggi_domande_id` ASC),
  INDEX `fk_sondaggi_domande_condizionate_sondaggi_ris_idx` (`sondaggi_risposte_predefinite_id` ASC),
  CONSTRAINT `fk_sondaggi_domande_condizionate_sondaggi_doman1`
    FOREIGN KEY (`sondaggi_domande_id`)
    REFERENCES `sondaggi_domande` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sondaggi_domande_condizionate_sondaggi_rispo1`
    FOREIGN KEY (`sondaggi_risposte_predefinite_id`)
    REFERENCES `sondaggi_risposte_predefinite` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sondaggi_pubblicazione`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sondaggi_pubblicazione` (
  `ruolo` VARCHAR(255) NOT NULL COMMENT 'Ruolo',
  `sondaggi_id` INT(11) NOT NULL COMMENT 'Sondaggio',
  `tipologie_entita` INT(11) NOT NULL DEFAULT '0' COMMENT 'Tipologie entita',
  `entita_id` INT(11) NULL DEFAULT NULL COMMENT 'Entita',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Creato il',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Aggiornato il',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Cancellato il',
  `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Creato da',
  `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Aggiornato da',
  `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Cancellato da',
  `version` INT(11) NULL DEFAULT NULL COMMENT 'Versione',
  PRIMARY KEY (`ruolo`, `sondaggi_id`, `tipologie_entita`),
  INDEX `fk_sondaggi_pubblicazione_sondaggi1_idx` (`sondaggi_id` ASC),
  CONSTRAINT `fk_sondaggi_pubblicazione_sondaggi1`
    FOREIGN KEY (`sondaggi_id`)
    REFERENCES `sondaggi` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sondaggi_risposte_sessioni`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sondaggi_risposte_sessioni` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `session_id` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Id Sessione',
  `unique_id` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Id Unico',
  `begin_date` DATETIME NULL DEFAULT NULL COMMENT 'Inizio compilazione',
  `end_date` DATETIME NULL DEFAULT NULL COMMENT 'Fine compilazione',
  `session_tmp` TEXT NULL DEFAULT NULL COMMENT 'Tmp Sessione',
  `completato` INT(11) NULL DEFAULT '0' COMMENT 'Completato',
  `user_id` INT(11) NULL DEFAULT NULL COMMENT 'Utente',
  `sondaggi_id` INT(11) NOT NULL COMMENT 'Sondaggio',
  `sondaggi_accessi_servizi_id` INT(11) NULL DEFAULT NULL COMMENT 'Accesso',
  `entita_id` INT(11) NULL DEFAULT NULL COMMENT 'Entità collegata in base alla configurazione',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Creato il',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Aggiornato il',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Cancellato il',
  `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Creato da',
  `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Aggiornato da',
  `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Cancellato da',
  `version` INT(11) NULL DEFAULT NULL COMMENT 'Versione',
  PRIMARY KEY (`id`),
  INDEX `fk_sondaggi_risposte_sessioni_sondaggi1_idx` (`sondaggi_id` ASC),
  INDEX `fk_sondaggi_risposte_sessioni_sondaggi_accessi_servizi_idx1` (`sondaggi_accessi_servizi_id` ASC),
  INDEX `fk_sondaggi_risposte_sessioni_entita_idx` (`entita_id` ASC),
  INDEX `fk_sondaggi_risposte_sessioni_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_sondaggi_risposte_sessioni_sondaggi_accessi_servizi1`
    FOREIGN KEY (`sondaggi_accessi_servizi_id`)
    REFERENCES `sondaggi_accessi_servizi` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sondaggi_risposte_sessioni_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sondaggi_risposte`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sondaggi_risposte` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `risposta_libera` TEXT NULL DEFAULT NULL COMMENT 'Risposta libera',
  `sondaggi_domande_id` INT(11) NOT NULL COMMENT 'Domanda',
  `sondaggi_risposte_predefinite_id` INT(11) NULL DEFAULT NULL COMMENT 'Risposta predefinita',
  `sondaggi_accessi_servizi_id` INT(11) NULL DEFAULT NULL,
  `sondaggi_risposte_sessioni_id` INT(11) NOT NULL COMMENT 'Utente',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Creato il',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Aggiornato il',
  `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Cancellato il',
  `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Creato da',
  `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Aggiornato da',
  `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Cancellato da',
  `version` INT(11) NULL DEFAULT NULL COMMENT 'Versione',
  PRIMARY KEY (`id`),
  INDEX `fk_sondaggi_risposte_sondaggi_domande1_idx` (`sondaggi_domande_id` ASC),
  INDEX `fk_sondaggi_risposte_sondaggi_accessi_servizi_idx` (`sondaggi_accessi_servizi_id` ASC),
  INDEX `fk_sondaggi_risposte_sondaggi_risposte_sessio_idx` (`sondaggi_risposte_sessioni_id` ASC),
  INDEX `fk_sondaggi_risposte_sondaggi_risposte_predef_idx` (`sondaggi_risposte_predefinite_id` ASC),
  CONSTRAINT `fk_sondaggi_risposte_sondaggi_accessi_servizi1`
    FOREIGN KEY (`sondaggi_accessi_servizi_id`)
    REFERENCES `sondaggi_accessi_servizi` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sondaggi_risposte_sondaggi_domande1`
    FOREIGN KEY (`sondaggi_domande_id`)
    REFERENCES `sondaggi_domande` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sondaggi_risposte_sondaggi_risposte_predefin1`
    FOREIGN KEY (`sondaggi_risposte_predefinite_id`)
    REFERENCES `sondaggi_risposte_predefinite` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sondaggi_risposte_sondaggi_risposte_sessioni1`
    FOREIGN KEY (`sondaggi_risposte_sessioni_id`)
    REFERENCES `sondaggi_risposte_sessioni` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

               ");

        $this->execute("SET FOREIGN_KEY_CHECKS=1;");

        return true;
    }

    public function safeDown() {

        $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
        $this->dropTable("sondaggi*");
        $this->execute("SET FOREIGN_KEY_CHECKS = 1;");

        return true;
    }

}
