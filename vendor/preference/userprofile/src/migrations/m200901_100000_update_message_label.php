<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\db\Migration;
use yii\rbac\Permission;
use open20\amos\admin\models\UserProfile;

/**
 * Class m200901_100000_update_message_label */
class m200901_100000_update_message_label extends Migration
{
    private $viewName = 'preference_target_chosen_for_user_view';

    public function safeUp()
    {
        $this->update('language_translate'
            , ['translation' => 'Il tuo profilo non è attivo. Per accedere chiedi all\'assistenza la attivazione del tuo profilo.']
            , ['translation' => 'Utente disattivato. Per accedere nuovamente, richiedi la riattivazione del profilo.']);
        
    }
    
    public function safeDown()
    {
        $this->update('language_translate'
            , ['translation' => 'Utente disattivato. Per accedere nuovamente, richiedi la riattivazione del profilo.']
            , ['translation' => 'Il tuo profilo non è attivo. Per accedere chiedi all\'assistenza la attivazione del tuo profilo.']);
    }

}
