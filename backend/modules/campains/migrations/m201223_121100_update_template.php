<?php
use yii\db\Migration;

/**
 * Class m201223_121100_update_template */
class m201223_121100_update_template extends Migration
{
    public function safeUp()
    {
        $this->execute("
        TRUNCATE preference_mailup_template;


        INSERT INTO `preference_mailup_template` (`id`, `mailup_template_id`, `mailup_template_preview_id`, `title`, `path`, `default_link_text`, `colors_choice`, `aspect_ratio`, `aspect_ratio_choices`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
        (1,	9,	1034,	'spazioREGIONE inform@',	'@backend/modules/campains/mailup_templates/1_spazioregione_informa',	'LEGGI',	'{\"#0068a5\":{\"label\":\"Blu\",\"txtcolor\":\"#ffffff\",\"bgcolor\":\"#0068a5\"},\"#297a38\":{\"label\":\"Verde\",\"txtcolor\":\"#ffffff\",\"bgcolor\":\"#297a38\"},\"#c93838\":{\"label\":\"Rosso\",\"txtcolor\":\"#ffffff\",\"bgcolor\":\"#c93838\"},\"#ffffff00\":{\"label\":\"Trasparente\",\"txtcolor\":\"#555555\",\"bgcolor\":\"#ffffff00\"}}',	'1.7777777777778',	'[{\"label\":\"X\",\"value\":\"x\",\"title\":\"Elimina il crop\"},{\"label\":\"16:9\",\"value\":1.7777777777778,\"title\":\"Fattore di crop 16:9\"}]',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
        (2,	1041,	1033,	'Agrifolium',	'@backend/modules/campains/mailup_templates/2_agrifolium',	'VUOI SAPERNE DI PIU\'?',	'{\"#BB6B00\":{\"label\":\"Arancione\",\"txtcolor\":\"#ffffff\",\"bgcolor\":\"#BB6B00\"},\"#64b6ac\":{\"label\":\"Azzurro\",\"txtcolor\":\"#ffffff\",\"bgcolor\":\"#64b6ac\"},\"#776274\":{\"label\":\"Viola \",\"txtcolor\":\"#ffffff\",\"bgcolor\":\"#776274\"},\"#ffffff00\":{\"label\":\"Trasparente\",\"txtcolor\":\"#555555\",\"bgcolor\":\"#ffffff00\"}}',	'1.7777777777778',	'[{\"label\":\"X\",\"value\":\"x\",\"title\":\"Elimina il crop\"},{\"label\":\"16:9\",\"value\":1.7777777777778,\"title\":\"Fattore di crop 16:9\"}]',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
        (3,	1052,	1050,	'Lombardia Informa',	'@backend/modules/campains/mailup_templates/3_lombardia_informa',	'Leggi',	'{\"#297a38\":{\"label\":\"Verde\",\"txtcolor\":\"#ffffff\",\"bgcolor\":\"#297a38\"},\"#962929\":{\"label\":\"Rosso\",\"txtcolor\":\"#ffffff\",\"bgcolor\":\"#962929\"},\"#002137\":{\"label\":\"Blu \",\"txtcolor\":\"#ffffff\",\"bgcolor\":\"#002137\"}}',	'1.7777777777778',	'[{\"label\":\"X\",\"value\":\"x\",\"title\":\"Elimina il crop\"},{\"label\":\"16:9\",\"value\":1.7777777777778,\"title\":\"Fattore di crop 16:9\"}]',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
        (4,	1100,	1095,	'Lombardia Informa con loghi',	'@backend/modules/campains/mailup_templates/4_lombardia_informa_con_loghi',	NULL,	'{\"#003354\":{\"label\":\"Blu\",\"txtcolor\":\"#003354\",\"bgcolor\":\"#003354\"},\"#a73541\":{\"label\":\"Rosso\",\"txtcolor\":\"#a73541\",\"bgcolor\":\"#a73541\"},\"#297a38\":{\"label\":\"Verde\",\"txtcolor\":\"#297a38\",\"bgcolor\":\"#297a38\"},\"#ffffff00\":{\"label\":\"Trasparente\",\"txtcolor\":\"#000000\",\"bgcolor\":\"#ffffff00\"}}',	'1.7777777777778',	'[{\"label\":\"X\",\"value\":\"x\",\"title\":\"Elimina il crop\"},{\"label\":\"16:9\",\"value\":1.7777777777778,\"title\":\"Fattore di crop 16:9\"},{\"label\":\"4:3\",\"value\":1.3333333,\"title\":\"Fattore di crop 4:3\"}, {\"label\":\"Free\",\"value\":\"NaN\",\"title\":\"Fattore di crop libero\"}]',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
        (5,	1102,	1094,	'Lombardia Informa senza loghi',	'@backend/modules/campains/mailup_templates/5_lombardia_informa_senza_loghi',	NULL,	'{\"#003354\":{\"label\":\"Blu\",\"txtcolor\":\"#003354\",\"bgcolor\":\"#003354\"},\"#a73541\":{\"label\":\"Rosso\",\"txtcolor\":\"#a73541\",\"bgcolor\":\"#a73541\"},\"#297a38\":{\"label\":\"Verde\",\"txtcolor\":\"#297a38\",\"bgcolor\":\"#297a38\"},\"#ffffff00\":{\"label\":\"Trasparente\",\"txtcolor\":\"#000000\",\"bgcolor\":\"#ffffff00\"}}',	'1.5',	'[{\"label\":\"X\",\"value\":\"x\",\"title\":\"Elimina il crop\"},{\"label\":\"3:2\",\"value\":1.5,\"title\":\"Fattore di crop 3:2\"},{\"label\":\"1:1\",\"value\":1,\"title\":\"Fattore di crop 1:1\"},{\"label\":\"Free\",\"value\":\"NaN\",\"title\":\"Fattore di crop libero\"}]',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL);
        
        
        ");
    }
    
    public function safeDown()
    {
        return true;
    }

}
