<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m160912_092602_create_user_profile
 */
class m160912_092602_create_user_profile extends Migration
{
    private $tabella = null;
    
    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->tabella = UserProfile::tableName();
        parent::__construct();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tabella, [
            'id' => $this->primaryKey(11),
            'nome' => $this->string(255)->notNull()->comment('Nome'),
            'cognome' => $this->string(255)->notNull()->comment('Cognome'),
            'codice_fiscale' => $this->string(16)->defaultValue(null)->comment('Codice fiscale'),
            'sesso' => "ENUM('Maschio','Femmina') DEFAULT NULL COMMENT 'Sesso'",
            'presentazione_personale' => $this->text()->comment('Presentazione personale'),
            'nascita_data' => $this->date()->defaultValue(null)->comment('Data di nascita'),
            //SAREBBE un tinyint
            'privacy' => $this->integer(1)->defaultValue(0)->comment('Accettazione privacy'),
            'indirizzo_residenza' => $this->string(255)->defaultValue(null)->comment('Indirizzo di residenza'),
            'cap_residenza' => $this->string(45)->defaultValue(null)->comment('CAP'),
            'numero_civico_residenza' => $this->string(255)->defaultValue(null)->comment('N. Civico di residenza'),
            'domicilio_indirizzo' => $this->string(255)->defaultValue(null)->comment('Indirizzo'),
            'domicilio_civico' => $this->string(10)->defaultValue(null)->comment('Civico'),
            'domicilio_cap' => $this->string(5)->defaultValue(null)->comment('Cap'),
            'domicilio_localita' => $this->string(255)->defaultValue(null)->comment('Frazione'),
//          'domicilio_coordinate' => $this->string(45)->defaultValue(null)->comment('Coordinate'),
            'domicilio_lat' => $this->string(45)->defaultValue(null)->comment('Domicilio latitudine'),
            'domicilio_lon' => $this->string(45)->defaultValue(null)->comment('Domicilio longitudine'),
            'widgets_selected' => $this->text()->comment('Widgets selezionati'),
            'nazionalita' => "ENUM('Extra UE','Italiana','UE') DEFAULT 'Italiana' COMMENT 'Nazionalità'",
            'email_pec' => $this->string(255)->defaultValue(null)->comment('Email (PEC)'),
            'altri_dati_contatto' => $this->text()->comment('Altri dati di contatto'),
            'telefono' => $this->string(255)->defaultValue(null)->comment('Telefono'),
            'cellulare' => $this->string(255)->defaultValue(null)->comment('Cellulare'),
            'fax' => $this->string(255)->defaultValue(null)->comment('FAX'),
            'attivo' => $this->integer(11)->defaultValue(1)->comment('Utente attivo'),
            'status' => $this->string(255)->defaultValue(null)->comment('Stato'),
            'note' => $this->text()->comment('Note'),
            'partita_iva' => $this->string(20)->defaultValue(null)->comment('Partita IVA'),
            'iban' => $this->string(20)->defaultValue(null)->comment('IBAN'),
            'facebook' => $this->string(255)->defaultValue(null)->comment('Profilo Facebook'),
            'twitter' => $this->string(255)->defaultValue(null)->comment('Profilo Twitter'),
            'linkedin' => $this->string(255)->defaultValue(null)->comment('Profilo Linkedin'),
            'ultimo_accesso' => $this->dateTime()->defaultValue(null)->comment('Ultimo accesso'),
            'ultimo_logout' => $this->dateTime()->defaultValue(null)->comment('Ultimo logout'),
            'validato_almeno_una_volta' => $this->integer(1)->defaultValue(0)->comment('L utente è stato validato almeno una volta'),
            'avatar_id' => $this->integer(11)->defaultValue(null)->comment('Immagine profilo'),
            'nascita_nazioni_id' => $this->integer(11)->defaultValue(null)->comment('Nazione di nascita'),
            'nascita_province_id' => $this->integer(11)->defaultValue(null)->comment('Provincia di nascita'),
            'nascita_comuni_id' => $this->integer(11)->defaultValue(null)->comment('Comune di nascita'),
//          'user_profile_dati_amministrativi_id' => $this->integer(11)->defaultValue(null)->comment('Dati amministrativi'),
            'user_profile_titoli_studio_id' => $this->integer(11)->defaultValue(null)->comment('Titolo di studio'),
            'user_profile_stati_civili_id' => $this->integer(11)->defaultValue(null)->comment('Stato civile'),
//          'user_profile_privacy_id' => $this->integer(11)->defaultValue(null)->comment('Livello di riservatezza'),
            'nazionalita_residenza_id' => $this->integer(4)->defaultValue(null)->comment('Nazionalità di residenza'),
            'comune_residenza_id' => $this->integer(5)->defaultValue(null)->comment('Comune di residenza'),
            'provincia_residenza_id' => $this->integer(4)->defaultValue(null)->comment('Provincia di residenza'),
            'domicilio_provincia_id' => $this->integer(11)->defaultValue(null)->comment('Provincia di domicilio'),
            'domicilio_comune_id' => $this->integer(11)->defaultValue(null)->comment('Comune di domicilio'),
            'residenza_nazione_id' => $this->integer(11)->defaultValue(null)->comment('Stato di residenza'),
            'facilitatore_id' => $this->integer(11)->defaultValue(null)->comment('Facilitatore'),
            'user_id' => $this->integer(11)->defaultValue(null)->comment('Utente'),
            'created_at' => $this->dateTime()->defaultValue(null)->comment('Creato il'),
            'updated_at' => $this->dateTime()->defaultValue(null)->comment('Aggiornato il'),
            'deleted_at' => $this->dateTime()->defaultValue(null)->comment('Cancellato il'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Creato da'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Aggiornato da'),
            'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Cancellato da'),
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        
        $this->addForeignKey('fk_user_profile_italia_comuni1', $this->tabella, 'domicilio_comune_id', 'istat_comuni', 'id');
        $this->addForeignKey('fk_user_profile_italia_comuni2', $this->tabella, 'nascita_comuni_id', 'istat_comuni', 'id');
        $this->addForeignKey('fk_user_profile_italia_comuni3', $this->tabella, 'comune_residenza_id', 'istat_comuni', 'id');
        $this->addForeignKey('fk_user_profile_italia_province1', $this->tabella, 'domicilio_provincia_id', 'istat_province', 'id');
        $this->addForeignKey('fk_user_profile_italia_province2', $this->tabella, 'nascita_province_id', 'istat_province', 'id');
        $this->addForeignKey('fk_user_profile_italia_province3', $this->tabella, 'provincia_residenza_id', 'istat_province', 'id');
        $this->addForeignKey('fk_user_profile_residenza_nazioni1', $this->tabella, 'residenza_nazione_id', 'istat_nazioni', 'id');
        $this->addForeignKey('fk_user_profile_user1', $this->tabella, 'user_id', 'user', 'id');
        $this->addForeignKey('fk_user_profile_user_profile1', $this->tabella, 'facilitatore_id', 'user_profile', 'id');
        $this->addForeignKey('fk_user_profile_user_profile_stati_civili1', $this->tabella, 'user_profile_stati_civili_id', 'user_profile_stati_civili', 'id');
        $this->addForeignKey('fk_user_profile_user_profile_titoli_studio1', $this->tabella, 'user_profile_titoli_studio_id', 'user_profile_titoli_studio', 'id');
        
        // Aggiungo l'amministratore
        $this->batchInsert($this->tabella, ['nome', 'cognome', 'sesso', 'user_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], [
            ['Amministratore', 'Sistema', 'Maschio', 1, 'UserProfileWorkflow/ATTIVOEVALIDATO', new Expression('NOW()'), new Expression('NOW()'), 1, 1]
        ]);
        
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_profile_italia_comuni1', $this->tabella);
        $this->dropForeignKey('fk_user_profile_italia_comuni2', $this->tabella);
        $this->dropForeignKey('fk_user_profile_italia_comuni3', $this->tabella);
        $this->dropForeignKey('fk_user_profile_italia_province1', $this->tabella);
        $this->dropForeignKey('fk_user_profile_italia_province2', $this->tabella);
        $this->dropForeignKey('fk_user_profile_italia_province3', $this->tabella);
        $this->dropForeignKey('fk_user_profile_residenza_nazioni1', $this->tabella);
        $this->dropForeignKey('fk_user_profile_user1', $this->tabella);
        $this->dropForeignKey('fk_user_profile_user_profile1', $this->tabella);
        $this->dropForeignKey('fk_user_profile_user_profile_stati_civili1', $this->tabella);
        $this->dropForeignKey('fk_user_profile_user_profile_titoli_studio1', $this->tabella);
        $this->dropTable($this->tabella);
        return true;
    }
}
