<?php

namespace frontend\modules\FLcommands\commands;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\commands
 * @category   CategoryName
 */

use open20\amos\admin\controllers\UserDropController;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use frontend\models\PrivacyHistory;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\Html;

class OperationsController extends Controller {

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    public function actionRetentionUsers() {
        $levels = PrivacyHistory::getRetentionLevels();

        // Utenti su cui lavorare
        $records = PrivacyHistory::getRetentionUsers();

        Console::stdout('Starting data retention evaluation.' . PHP_EOL);
        foreach($records as $record):
            Console::stdout('Working on User: ' . $record->id_user . PHP_EOL);
            $future = strtotime("+{$levels[$record->notification_level][0]} years", $record->timestamp);
            $future = strtotime("+{$levels[$record->notification_level][1]} months", $future);
            $future = strtotime("+{$levels[$record->notification_level][2]} weeks", $future);
            //per test: $future = strtotime("-20 years", $future);
            if($future <= time()) {
                // Commentato per bug 23307
                // Reset precedente token
                //PrivacyHistory::destroyUserToken($record->id_user);
                // Se non è stato raggiunto il livello di oblio

                // Nuovo record privacy history non accettato
                PrivacyHistory::newRejectedPrivacy($record->id_user, $record->notification_level + 1);
                
                if($record->notification_level != count($levels) - 1) {
                    Console::stdout('--> ' . 'Notifing User: ' . $record->id_user . PHP_EOL);

                    // Invio email
                    $email = new Email();
                    $from = '';
                    $platformName = '';

                    // Ottengo nome applicazione/piattaforma per l'inserimento nel titolo della mail
                    if (isset(\Yii::$app->name)) {
                        $platformName = \Yii::$app->name;
                    }

                    $subject = "Richiesta conferma di continuità servizio in $platformName";
                    if (isset(\Yii::$app->params['email-assistenza'])) {
                        //use default platform email assistance
                        $from = \Yii::$app->params['email-assistenza'];
                    }

                    $link = Html::a('<strong>QUESTO LINK</strong>', Yii::$app->params['platform']['frontendUrl'] . '/preferences/change');
                    $message = "<p>Gentile utente,</p>";
                    $message .= "<p>Ci teniamo a comunicarle che possiede un account attivo sulla piattaforma Open 2.0. La nostra piattaforma si impegna a garantire la massima sicurezza e affidabilità del servizio che offriamo.</p>";
                    $message .= "<p>Ai sensi della nostra politica sulla privacy, ogni cinque anni richiediamo ai nostri utenti una conferma di continuità di servizio per assicurarsi che i dati degli utenti siano aggiornati e corretti.</p>";
                    $message .= "<p>La preghiamo pertanto di confermare la sua presenza su Open 2.0 entro e non oltre sette giorni dalla ricezione di questa email. Se non riceveremo una sua conferma entro tale termine, il suo account verrà automaticamente cancellato e non sarà più in grado di accedere alla piattaforma.</p>";
                    $message .= "<p>Per confermare la sua presenza, è sufficiente cliccare $link e accedere alla piattaforma Open 2.0; le sarà richiesto di esprimere nuovamente l’accettazione al trattamento dei suoi dati personali.</p>";
                    $message .= "<p>Le assicuriamo che i suoi dati personali sono al sicuro e saranno trattati in conformità con le leggi sulla privacy applicabili.</p>";
                    $message .= "<p>La ringraziamo per la collaborazione e restiamo a disposizione per qualsiasi ulteriore informazione.</p>";
                    $message .= "<p>Cordiali saluti,</p>";
                    $message .= "<p>Il team di Open 2.0</p>";

                    // Invio Email
                    $email->sendMail($from, $record->user->email, $subject, $message);
                    Console::stdout('--> ' . 'Email sent to User: ' . $record->id_user . PHP_EOL);
                }
                else{
                    Console::stdout('--> ' . 'Oblivion for User: ' . $record->id_user . PHP_EOL);
                    $userProfile = User::findOne($record->id_user)->userProfile;
                    $module = \Yii::$app->getModule('amosadmin');
                    $this->dropUser($userProfile->user_id);
                    Console::stdout('--> ' . 'Account deleted.' . PHP_EOL);
                }
            }
            else {
                Console::stdout('--> ' . 'Nothing to do.' . PHP_EOL);
            }
            Console::stdout('End working on User: ' . $record->id_user . PHP_EOL);
        endforeach;
        Console::stdout('End data retention.' . PHP_EOL);
    }

    public function dropUser($userId) {
        $module = \Yii::$app->getModule('amosadmin');
        $dropController = new UserDropController('user_drop', $module);
        $user = User::findOne($userId);
        //Mask the user data
        $user = $dropController->maskUserData($user);

        // Drop social links
        $dropController->dropSocialLinks($user->id);

        //And finally drop profile, this is the end of the user on the platform
        $dropController->dropUser($user);
    }
}