<?php
use yii\db\Migration;

/**
 * Class m210121_175200_update_template */
class m210121_175200_update_template extends Migration
{
    public function safeUp()
    {
        $this->update('preference_mailup_template', [
            'colors_choice' => '{"bef_rosso":{"label":"Bandi e fondi (Rosso)","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/icon-bandi.png","default_text":"Bandi e fondi","txtcolor":"#a73541","bgcolor":"#a73541"},"bef_verde":{"label":"Bandi e fondi (verde)","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/icon-bandi.png","default_text":"Bandi e fondi","txtcolor":"#297a38","bgcolor":"#297a38"},"territorio_blu":{"label":"Territorio (blu)","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/icon-territorio.png","default_text":"Territorio","txtcolor":"#003354","bgcolor":"#003354"},"#003354":{"label":"Generico (Blu)","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/arrow-white.png","default_text":"","txtcolor":"#003354","bgcolor":"#003354"},"#a73541":{"label":"Generico (Rosso)","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/arrow-white.png","default_text":"","txtcolor":"#a73541","bgcolor":"#a73541"},"#297a38":{"label":"Generico (Verde)","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/arrow-white.png","default_text":"","txtcolor":"#297a38","bgcolor":"#297a38"},"#ffffff00":{"label":"Trasparente","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/arrow-white.png","default_text":"","txtcolor":"#1C2833","bgcolor":"#ffffff00"}}',
        ], ['id' => [4,5]]);
    }
    
    public function safeDown()
    {
        // $this->update('preference_mailup_template', [
        //     'colors_choice' => '{"bef_rosso":{"label":"Bandi e fondi (Rosso)","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/icon-bandi.png","default_text":"Bandi e fondi","txtcolor":"#a73541","bgcolor":"#a73541"},"bef_verde":{"label":"Bandi e fondi (verde)","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/icon-bandi.png","default_text":"Bandi e fondi","txtcolor":"#297a38","bgcolor":"#297a38"},"territorio_blu":{"label":"Territorio (blu)","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/icon-territorio.png","default_text":"Territorio","txtcolor":"#003354","bgcolor":"#003354"},"#003354":{"label":"Generico (Blu)","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/arrow-white.png","default_text":"","txtcolor":"#003354","bgcolor":"#003354"},"#a73541":{"label":"Generico (Rosso)","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/arrow-white.png","default_text":"","txtcolor":"#a73541","bgcolor":"#a73541"},"#297a38":{"label":"Generico (Verde)","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/arrow-white.png","default_text":"","txtcolor":"#297a38","bgcolor":"#297a38"},"#ffffff00":{"label":"Trasparente","section_icon":"https:\/\/a4f6d9.emailsp.com\/assets\/1\/arrow-white.png","default_text":"","txtcolor":"#1C2833","bgcolor":"#ffffff00"}}',
        // ], ['id' => [4,5]]);

        // do nothing
    }

}
