<?php
namespace console\controllers;

use common\models\LogAzioniCron;
use open20\amos\core\user\User;
use yii\console\Controller;
use yii\helpers\Console;
use Yii;

class PreferenceCronController extends Controller
{
    private $usernameEnabled = 'CRON';
    /** @param $logAzione LogAzioniCron **/
    private $logAzione;
    private $timeStart;
    private $timeStop;

    /**
     * @return string
     */
    public function getUsernameEnabled()
    {
        return $this->usernameEnabled;
    }

    public function init()
    {
        parent::init();
        $this->purgeOldMessages();
        $this->autoLoginUtenteCron();
        $this->on(self::EVENT_BEFORE_ACTION, [$this, 'beforeAction']);
        $this->on(self::EVENT_AFTER_ACTION, [$this, 'afterAction']);
    }

    public function autoLoginUtenteCron()
    {
        $user = User::findByUsername($this->getUsernameEnabled());
        Yii::$app->user->setIdentity($user);
        Yii::$app->session->get('session');
        return true;
    }

    /**
     * @return false|string
     */
    public function getNow()
    {
        return date('Y-m-d H:i:s');
    }

    public function actionTest()
    {
        Console::stdout("User loggato: " . Yii::$app->user->getIdentity()->username . "\n");
        Console::stdout("User email loggato: " . Yii::$app->user->getIdentity()->email . "\n");
        Console::stdout("ID di sessione: " . Yii::$app->getSession()->id . "\n");
        $logReturn['code'] = 0;
        $logReturn['message'] = "User loggato: " . Yii::$app->user->getIdentity()->username . "\n" .
            "User email loggato: " . Yii::$app->user->getIdentity()->email . "\n" .
            "ID di sessione: " . Yii::$app->getSession()->id . "\n";
        $corpo []= "Contenuto della mail - Email di test";
            $this->inviaMail(Yii::$app->user->getIdentity()->email, [], 'Email di test da CRON', $corpo);
        return $logReturn;
    }

    /**
     * @param string $name
     */
    public function trace($scope, $message)
    {
        $message .= "\n";
        $message = '['. date_format(new \DateTime( 'now'), 'Y-m-d H:i:s') . ']: ' . $message;
        Console::stdout($message);
        Yii::debug($message, 'cron');
        $this->logAzione->messaggio .= $message;
    }

    /**
     * @param yii\base\InlineAction $event
     * @return bool
     * @internal param \yii\base\Action $action
     */
    public function beforeAction($event)
    {
        Console::stdout("START: " . $this->uniqueId . ' ' . $event->id . "\n");
        $this->logAzione = new LogAzioniCron;
        $this->logAzione->controller_id = $this->uniqueId;
        $this->logAzione->action_id = $event->id;
        $this->logAzione->start = true;
        $start = new \DateTime();
        $this->logAzione->created_at = $start->format('Y-m-d H:i:s');
        $this->logAzione->created_by = Yii::$app->user->id;
        $this->logAzione->updated_at = $start->format('Y-m-d H:i:s');
        $this->logAzione->updated_by = Yii::$app->user->id;
        // $this->logAzione->save(false);
        $this->timeStart = microtime(true) * 1000;


        return true;
    }

    public function afterAction($action, $result)
    {
        Console::stdout("END: " . $action->id . "\n");
        $this->logAzione->end = ($result['code'] == 0);
        $stop = new \DateTime();
        $this->logAzione->updated_at = $stop->format('Y-m-d H:i:s');
        $this->logAzione->updated_by = Yii::$app->user->id;
        $this->timeStop = microtime(true) * 1000 ;
        $this->logAzione->durata = bcsub($this->timeStop, $this->timeStart, 0);
        $this->logAzione->messaggio .= 'Codice restituito: ['. $result['code']. "]\n". $result['message'];

        Yii::debug( $this->logAzione->messaggio, 'END ACTION: ' . $this->logAzione->messaggio);
        // try {
        //     $this->logAzione->save(false);
        // } catch (\Exception $exception){
        //     $this->logAzione->messaggio .= 'Codice restituito: [2'. "]: Non è stato possibile salvare il log!\n".$exception->getMessage();
        //     $this->logAzione->end = ($result['code'] == 0);
        //     $this->logAzione->save(false);
        // }
        return true;
    }

    private function purgeOldMessages()
    {
        $query = LogAzioniCron::find()->where('created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)');
        $list = $query->all();
        foreach ($list as $log) {
            $log->delete();
        }
    }

}