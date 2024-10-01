<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190923_082322_add_fields_sondaggi4
 */
class m190923_082322_add_fields_sondaggi4 extends Migration
{
    const TABLE         = '{{%sondaggi}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande}}';
    const TABLE_MAP     = '{{%sondaggi_map}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {       
        $this->addColumn(self::TABLE, 'link_landing_page', $this->text()->defaultValue(null)->after('url_chiudi_sondaggio'));
        $this->addColumn(self::TABLE, 'testo_sondaggio_non_compilabile_front', $this->text()->defaultValue(null)->after('link_landing_page'));
        $this->addColumn(self::TABLE, 'titolo_fine_sondaggio_front', $this->text()->defaultValue(null)->after('testo_sondaggio_non_compilabile_front'));
        $this->addColumn(self::TABLE, 'testo_fine_sondaggio_front', $this->text()->defaultValue(null)->after('titolo_fine_sondaggio_front'));
        $this->addColumn(self::TABLE, 'mail_mittente_nuovo_utente', $this->text()->defaultValue(null)->after('testo_fine_sondaggio_front'));
        $this->addColumn(self::TABLE, 'mail_soggetto_nuovo_utente', $this->text()->defaultValue(null)->after('mail_mittente_nuovo_utente'));
        $this->addColumn(self::TABLE, 'mail_contenuto_nuovo_utente', $this->text()->defaultValue(null)->after('mail_soggetto_nuovo_utente'));
        $this->addColumn(self::TABLE, 'mail_mittente_utente_presente', $this->text()->defaultValue(null)->after('mail_contenuto_nuovo_utente'));
        $this->addColumn(self::TABLE, 'mail_soggetto_utente_presente', $this->text()->defaultValue(null)->after('mail_mittente_utente_presente'));
        $this->addColumn(self::TABLE, 'mail_contenuto_utente_presente', $this->text()->defaultValue(null)->after('mail_soggetto_utente_presente'));
        $this->addColumn(self::TABLE, 'mail_registrazione_custom', $this->integer()->defaultValue(0)->after('mail_contenuto_utente_presente'));
        $this->addColumn(self::TABLE, 'mail_registrazione_mittente', $this->text()->defaultValue(null)->after('mail_registrazione_custom'));
        $this->addColumn(self::TABLE, 'mail_registrazione_soggetto', $this->text()->defaultValue(null)->after('mail_registrazione_mittente'));
        $this->addColumn(self::TABLE, 'mail_registrazione_corpo', $this->text()->defaultValue(null)->after('mail_registrazione_soggetto'));
        $this->addColumn(self::TABLE, 'mail_conf_community', $this->integer()->defaultValue(0)->after('mail_registrazione_corpo'));
        $this->addColumn(self::TABLE, 'mail_conf_community_id', $this->integer()->defaultValue(null)->after('mail_conf_community'));
        $this->addColumn(self::TABLE, 'mail_conf_community_mittente', $this->text()->defaultValue(null)->after('mail_conf_community_id'));
        $this->addColumn(self::TABLE, 'mail_conf_community_soggetto', $this->text()->defaultValue(null)->after('mail_conf_community_mittente'));
        $this->addColumn(self::TABLE, 'mail_conf_community_corpo', $this->text()->defaultValue(null)->after('mail_conf_community_soggetto'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {        
        $this->dropColumn(self::TABLE, 'link_landing_page');
        $this->dropColumn(self::TABLE, 'testo_sondaggio_non_compilabile_front');
        $this->dropColumn(self::TABLE, 'titolo_fine_sondaggio_front');
        $this->dropColumn(self::TABLE, 'testo_fine_sondaggio_front');
        $this->dropColumn(self::TABLE, 'mail_mittente_nuovo_utente');
        $this->dropColumn(self::TABLE, 'mail_soggetto_nuovo_utente');
        $this->dropColumn(self::TABLE, 'mail_contenuto_nuovo_utente');
        $this->dropColumn(self::TABLE, 'mail_mittente_utente_presente');
        $this->dropColumn(self::TABLE, 'mail_soggetto_utente_presente');
        $this->dropColumn(self::TABLE, 'mail_contenuto_utente_presente');
        $this->dropColumn(self::TABLE, 'mail_registrazione_custom');
        $this->dropColumn(self::TABLE, 'mail_registrazione_mittente');
        $this->dropColumn(self::TABLE, 'mail_registrazione_soggetto');
        $this->dropColumn(self::TABLE, 'mail_registrazione_corpo');
        $this->dropColumn(self::TABLE, 'mail_conf_community');
        $this->dropColumn(self::TABLE, 'mail_conf_community_id');
        $this->dropColumn(self::TABLE, 'mail_conf_community_mittente');
        $this->dropColumn(self::TABLE, 'mail_conf_community_soggetto');
        $this->dropColumn(self::TABLE, 'mail_conf_community_corpo');
    }
}