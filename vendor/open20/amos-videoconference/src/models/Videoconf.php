<?php

namespace open20\amos\videoconference\models;

use open20\amos\admin\models\UserProfile;
use open20\amos\notificationmanager\models\Notification;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\videoconference\widgets\icons\WidgetIconVideoconference;
use open20\amos\videoconference\i18n\grammar\VideoconferenceGrammar;

/**
 * This is the model class for table "videoconf".
 */
class Videoconf extends \open20\amos\videoconference\models\base\Videoconf implements ContentModelInterface ,ViewModelInterface{

    const STATUS_FUTURE = 1;
    const STATUS_RUNNING = 2;
    const STATUS_END = 3;

    //public $ids = [];
    public $sheduledVideoconfCheckBox;

    public function representingColumn() {
        return [
            'title',
                //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints() {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute) {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules() {
        return ArrayHelper::merge(parent::rules(), [
            ['sheduledVideoconfCheckBox','safe']
        ]);
    }

    public function attributeLabels() {
        return
            ArrayHelper::merge(
                parent::attributeLabels(), [
                // 'pippo' => AmosVideoconference::t('amosvideoconference', 'Pippo'),
            ]);
    }


    /**
     * 
     * @return type
     */
    public function getLinkToVideoconf() 
    {
        $url = '';
   
        if($this->status == Videoconf::STATUS_FUTURE)
        {
            $url = Url::to(['/videoconference/videoconf/register-logged-user?idVideoconf='.$this->id], 'https');
        }
        else
        {
            $url = Url::to(['/videoconference/videoconf/meet?id_room='.$this->id_room_videoconference], 'https');
        }
        return $url;
    }


    /**
     * Create room id and beginDate
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function beforeSave($insert)
    {
        // if you schedule a videoconference in the future, the id_room_videoconference is setted by a cron
        if(!$this->isVideoConferenceProgrammed() && !$this->isVideoConferenceRoomActive() && $this->status != Videoconf::STATUS_END){
            if (!$this->id_room_videoconference) {
                $this->setIdRoomVideoconference();
            }
        }

        if (!$this->status){
            $this->status = ($this->begin_date_hour) ? Videoconf::STATUS_FUTURE: Videoconf::STATUS_RUNNING;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @param $user_id
     * @return $this|bool
     * crea e ritorna la query base per recuperare le notifiche di un User (NON UserProfile) associato alla videoconferenza in questione
     */
    protected function createBaseQueryNotificationByUser($user_id){
        if(!empty($user_id && !empty($this->id))) {
            $q = Notification::find()
                ->andWhere(['channels' => 1])
                ->andWhere(['content_id' => $this->id]) //id videoconferenza
                ->andWhere(['class_name' => $this::className()]) //namaspace videoconferenza
                ->andWhere(['notification.user_id' => $user_id]); //id record User (NON UserProfile)
            return $q;
        }
        return false;
    }

    /**
     * @param $videoconf_id
     * @param $user_id
     * @return $this|bool
     * crea e ritorna la query base per recuperare le notifiche di un User (NON UserProfile) associato alla videoconferenza in questione
     */
    protected static function createBaseQueryNotificationByVideoconfAndUser($videoconf_id ,$user_id){
        if(!empty($user_id && !empty($videoconf_id))) {
            $q = Notification::find()
                ->andWhere(['channels' => 1])
                ->andWhere(['content_id' => $videoconf_id]) //id videoconferenza
                ->andWhere(['class_name' => self::className()]) //namaspace videoconferenza
                ->andWhere(['notification.user_id' => $user_id]); //id record User (NON UserProfile)
            return $q;
        }
        return false;
    }

    /**
     * @param $user_id User record id
     * @return bool|null
     * Controlla l'esistenza di un record Notification per le email (channels = 1)
     * associate all'utente della videoconferenza in questione
     */
    protected function checkHasNotification($user_id){
        //recupero la query per sapere se ci sono notifiche per lo user in input
        $q = $this->createBaseQueryNotificationByUser($user_id);

        /*pr($q->createCommand()->rawSql ," query!");*/
        return ($q->count() > 0) ? true : false;
    }

    /**
     * @param $videoconf_id Videoconf record id
     * @param $user_id User record id
     * @return bool|null
     * Controlla l'esistenza di un record Notification per le email (channels = 1)
     * associate all'utente in input e alla videoconferenza in input
     */
    public static function checkHasNotificationByVideoconfAndUser($videoconf_id, $user_id){
        //recupero la query per sapere se ci sono notifiche per lo user in input
        $q = self::createBaseQueryNotificationByVideoconfAndUser($videoconf_id, $user_id);
        return ($q->count() > 0) ? true : false;
    }

    /**
     * @param $user_id
     * @return mixed
     * ritorna le Notification per le email (channels = 1)
     * associate all'utente della videoconferenza in questione
     */
    protected function getNotifications($user_id){
        //recupero la query per sapere se ci sono notifiche per lo user in input
        $q = $this->createBaseQueryNotificationByUser($user_id);

        return $q->all();
    }

    /**
     * @param $user_id
     * @return bool
     * Crea un record Notification per le email (channels = 1)
     * associato all'utente in input e alla videoconferenza in questione
     */
    public function createNotification($user_id ){

        if(!empty($user_id && !empty($this->id))) {
            $rec = new Notification();
            $rec->channels = 1;
            $rec->content_id = $this->id;
            $rec->class_name = $this::className();
            $rec->user_id = $user_id;

            /*pr($rec->toArray() ," record notification da SALVARE");*/
            $rec->save(false);
            return true;
        }
        return false;
    }

    /**
     * @param $videoconf_id
     * @param $user_id
     * @return bool
     * Crea un record Notification per le email (channels = 1)
     * associato all'utente in input e alla videoconferenza in input
     */
    public static function createNotificationByVideoconfAnduser($videoconf_id, $user_id ){
        if(!empty($user_id && !empty($videoconf_id))) {
            $rec = new Notification();
            $rec->channels = 1;
            $rec->content_id = $videoconf_id;
            $rec->class_name = self::className();
            $rec->user_id = $user_id;

            /*pr($rec->toArray() ," record notification da SALVARE");*/
            $rec->save(false);
            return true;
        }
        return false;
    }

    /**
     * @return array
     * Scorre i partecipanti della videoconferenza, recupera le notifiche (tab notification) per ognuno di essi
     * e ritorna solo le notifiche ancora da notificare: quelle che non hanno corrispondenti record in notification_read
     */
    public function getNotification2Notify(){
        $notifiche_return = [];

        //per ogni utente partecipante alla videoconferenza
        foreach($this->getVideoconfUsersMms()->all() as $k => $user_videoconf){
            //notifiche email per l'utente
            $notifiche = $this->getNotifications($user_videoconf->userProfile->id);

            foreach($notifiche as $k_n => $notificaRecord){
                //controllo se ha notifiche lette
                if( count($notificaRecord->notificationsRead) > 0 ){
                }else{
                    $notifiche_return[] = $notificaRecord;
                }
            }
        }
        return $notifiche_return;
    }


    /**
     *  Return true if video conference is programmed for a future date (begin_date_hour > today)
     * @return bool
     */
    public function isVideoConferenceProgrammed(){
        $today = new \DateTime();
        if(!empty($this->begin_date_hour)) {
            $beginVideoConference = new \DateTime($this->begin_date_hour);
            return ($beginVideoConference > $today);
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isVideoConferenceActivable(){
        $today = new \DateTime();
        $beginVideoConference = new \DateTime($this->begin_date_hour);
        if($this->isVideoConferenceProgrammed()) {
            return $today >= $beginVideoConference;
        }
        else {
            if(!empty($this->end_date_hour)) {
                $endVideoConference = new \DateTime($this->end_date_hour);
                return $today < $endVideoConference;
            }
            return true;
        }
    }

    /**
     * @return bool
     */
    public function isVideoConferenceRoomActive(){
       if(!empty($this->id_room_videoconference)) {
           $now = new \DateTime();
           // if is a scheduled videoconference, the room is closed the day after the end_date_hour
           if(!empty($this->end_date_hour)) {
               $endVideoConference = new \DateTime($this->end_date_hour);
               return ($now->format('Y-m-d') <=  $endVideoConference->format('Y-m-d'));
            //if is a scheduled videoconference and v is not valorized, the room is closed the day after the begin_date_hour
           } elseif(!empty($this->begin_date_hour)) {
               $beginVideoConference = new \DateTime($this->begin_date_hour);
               return ($now->format('Y-m-d') <=  $beginVideoConference->format('Y-m-d'));
           } else {
               // if is not a scheduled videoconference, close the room the day after it was created the videoconference
               $beginVideoConference = new \DateTime($this->created_at);
               return ($now->format('Y-m-d') <=  $beginVideoConference->format('Y-m-d'));
           }
       }
       else return false;
    }

    /**
     *
     */
    public function setIdRoomVideoconference(){
        $this->id_room_videoconference =  'room'.\time().$this->id;
    }
    
    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->__shortText($this->description, 100);
    }

    /**
     * @inheritdoc
     */
    public function getDescription($truncate)
    {
        $ret = $this->description;

        if ($truncate) {
            $ret = $this->__shortText($this->description, 200);
        }
        return $ret;
    }
    
    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
       /* return [
            'titolo' => [
                'attribute' => 'title',
                'headerOptions' => [
                    'id' => 'title'
                ],
                'contentOptions' => [
                    'headers' => 'title'
                ]
            ],
            'description' => [
                'attribute' => 'description',
                'format' => 'html',
                'headerOptions' => [
                    'id' => 'description'
                ],
                'contentOptions' => [
                    'headers' => 'description'
                ]
            ],
        ];*/
        return null;
    }

     /**
     * @inheritdoc
     */
    public function getPublicatedFrom()
    {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function getPublicatedAt()
    {
        return null;
    }
    
     /**
     * @return \yii\db\ActiveQuery category of content
     */
    public function getCategory()
    {
        return null;
    }
    
     public function getPluginWidgetClassname()
    {
        return WidgetIconVideoconference::className();
    }
    
     public function getToValidateStatus()
    {
         return null;
    }
    
      /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
         return null;
    }

    /**
     * @inheritdoc
     */
    public function getDraftStatus()
    {
         return null;
    }
    
     /**
     * @inheritdoc
     */
    public function getValidatorRole()
    {
          return null;
    }
    
      /**
     * @return array list of statuses that for cwh is validated
     */
    public function getCwhValidationStatuses()
    {
        return [$this->getValidatedStatus()];
    }
    
     /**
     * @return mixed
     */
    public function getGrammar()
    {
        return new VideoconferenceGrammar();
    }
    
     /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return "videoconference/videoconf/view";
    }
    /**
     * @return string The url to view of this model
     */
    public function getFullViewUrl()
    {
        return Url::toRoute(["/" . $this->getViewUrl(), "id" => $this->id]);
    }
}
