<?php

use yii\db\Migration;
use preference\userprofile\models\Tag;

/**
 * Class m210415_165500_tag_salute_prevenzione
 */
class m210415_165500_tag_salute_prevenzione extends Migration
{

    public function safeUp()
    {

        // update sul tag codice pctopic_enteeoperatore_17
        $this->update('tag', [
            'nome' => 'Salute e prevenzione',
            'codice' => 'pctopic_enteeoperatore_9',
            'descrizione' => 'Notizie utili per la tutela della salute personale e la cura delle malattie, informazioni sulla prevenzione e la tutela del benessere.',
        ], ['codice' => 'pctopic_enteeoperatore_17']);

        $this->update('tag', ['pc_topic_order' => 85], ['codice' => 'pctopic_cittadino_9']);

        // adeguo tutti i dati inseriti dagli utenti
        $this->update('preference_topic_channel_mm', ['topic_code' => 'pctopic_enteeoperatore_9'], ['topic_code' => 'pctopic_enteeoperatore_17']);
        // insert del nuovo codice

        $tagRoot = Tag::findOne(['codice' => 'pctarget_impresa']);
        if (!empty($tagRoot)) {
            $tag = new \preference\userprofile\models\Tag([
                'nome' => 'Salute e prevenzione',
                'descrizione' => 'Notizie utili per la tutela della salute personale e la cura delle malattie, informazioni sulla prevenzione e la tutela del benessere.',
                'codice' => 'pctopic_impresa_9',
                'pc_active' => 1,
                'pc_topic_order' => 85,
                'icon' => 'salute-e-prevenzione.svg',
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => 1,
                'removable' => false,
            ]);

            $tag->appendTo($tagRoot);
            $tag->save(false);
        }


        return true;
    }

    public function safeDown()
    {

        $this->update('tag', [
            'nome' => 'Sanità',
            'codice' => 'pctopic_enteeoperatore_17',
            'descrizione' => 'Notizie, bandi e informazioni per gli enti e gli operatori del settore sanitario.',
        ], ['codice' => 'pctopic_enteeoperatore_9']);

        $this->update('tag', ['pc_topic_order' => 100], ['codice' => 'pctopic_cittadino_9']);

        // adeguo tutti i dati inseriti dagli utenti
        $this->update('preference_topic_channel_mm', ['topic_code' => 'pctopic_enteeoperatore_17'], ['topic_code' => 'pctopic_enteeoperatore_9']);

        $this->delete('tag', ['codice' => 'pctopic_impresa_9']);

        return true;
    }

}
