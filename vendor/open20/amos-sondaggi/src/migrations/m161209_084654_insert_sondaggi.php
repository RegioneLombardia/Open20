<?php

class m161209_084654_insert_sondaggi extends \yii\db\Migration {

    const TABLE_PERMISSION = '{{%auth_item_child}}';

    public function safeUp() {
       $this->execute('SET FOREIGN_KEY_CHECKS=0;');
       $this->execute("
           
            INSERT INTO `sondaggi_stato` (`id`, `stato`, `descrizione`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `version`) VALUES
            (1, 'BOZZA', 'Modifica in corso', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
            (2, 'DA_VALIDARE', 'Da validare', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
            (3, 'VALIDATO', 'Validato', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
            (4, 'NON_VALIDATO', 'Non validato', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
            
            INSERT INTO `sondaggi_domande_tipologie` (`id`, `tipologia`, `descrizione`, `attivo`, `html_type`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `version`) VALUES
            (1, 'Checkbox (scelta multipla)', 'Saranno visualizzate delle checkbox collegate alle risposte e la scelta potrà essere multipla', 1, 'checkbox', NULL, '2015-09-25 10:27:41', NULL, NULL, 1, NULL, NULL),
            (2, 'Radio button (scelta singola)', 'Saranno visualizzati dei bottoni Radio collegati alle risposte e sarà permesso effettuare una sola selezione', 1, 'radio', '2015-09-25 10:04:59', '2015-09-25 10:06:00', NULL, 1, 1, NULL, NULL),
            (3, 'Tendina (scelta singola)', 'Sarà visualizzato un menu a tendina contenenti le risposte possibili di cui se ne potrà selezionare solo una', 1, 'select', '2015-09-25 10:06:55', '2015-09-25 10:06:55', NULL, 1, 1, NULL, NULL),
            (4, 'Tendina (scelta multipla)', 'Sarà visualizzato un menu a tendina contenenti le risposte possibili di cui si potrà effettuare una selezione multipla', 1, 'select-multiple', '2015-09-25 10:08:25', '2015-09-25 10:08:25', NULL, 1, 1, NULL, NULL),
            (5, 'Libera (breve)', 'Si potrà inserire del testo libero di max 255 caratteri', 1, 'string', '2015-09-25 10:09:08', '2015-09-25 10:28:18', NULL, 1, 1, NULL, NULL),
            (6, 'Libera (lunga)', 'Si potrà inserire del testo libero senza limite di lunghezza', 1, 'text', '2015-09-25 10:09:36', '2015-09-25 10:09:49', NULL, 1, 1, NULL, NULL),
            (7, 'Immagini (scelta singola)', 'Si potranno inserire delle immagini delle quali ne potrà essere scelta solo una.', 0, 'img', '2015-09-25 10:09:36', '2015-09-25 10:09:49', NULL, 1, 1, NULL, NULL),
            (8, 'Immagini (scelta multipla)', 'Si potranno inserire delle immagini delle quali si potrà effettuare una selezione multipla.', 0, 'img-multiple', '2015-09-25 10:09:36', '2015-09-25 10:09:49', NULL, 1, 1, NULL, NULL),
            (9, 'Personalizzato', 'Si potrà personalizzare la domanda.', 0, 'custom', '2015-09-25 10:09:36', '2015-09-25 10:09:49', NULL, 1, 1, NULL, NULL);

               ");
       $this->execute('SET FOREIGN_KEY_CHECKS=0;');
    }

    public function safeDown() {
        echo "Down() non previsto per il file m161209_084654_insert_sondaggi";
        return false;
    }

}
