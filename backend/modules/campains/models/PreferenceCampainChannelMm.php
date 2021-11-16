<?php

namespace backend\modules\campains\models;

use backend\modules\campains\events\workflow\PreferenceWorkflowEvent;
use backend\modules\campains\utility\CampainsQueryUtility;
use backend\modules\campains\utility\TableSentUtility;
use open20\amos\notificationmanager\behaviors\NotifyBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;

/**
 * This is the model class for table "preference_campain_channel_mm".
 */
class PreferenceCampainChannelMm extends \backend\modules\campains\models\base\PreferenceCampainChannelMm {


    // Preference Campain Channel Mm Workflow
    const PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW = "PreferenceCampainChannelMmWorkflow";
    const PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_TOBEAPPROVED = "PreferenceCampainChannelMmWorkflow/TOBEAPPROVED";
    const PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_APPROVED = "PreferenceCampainChannelMmWorkflow/APPROVED";
    const PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENDINGINPROGRESS = "PreferenceCampainChannelMmWorkflow/SENDINGINPROGRESS";
    const PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT = "PreferenceCampainChannelMmWorkflow/SENT";


    /**
     * Init 
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW)->getInitialStatusId();
        }

        $this->on('afterEnterStatus{' . self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENDINGINPROGRESS . '}', [new PreferenceWorkflowEvent(), 'whenCamapinIsApproved'], $this);
        $this->on('afterEnterStatus{' . self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT . '}', [new PreferenceWorkflowEvent(), 'whenChannelIsSent'], $this);
        
    }


    /**
     * Behaviors
    *
    * @return void
    */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
            'workflow' => [
                'class' => SimpleWorkflowBehavior::className(),
                'defaultWorkflowId' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'propagateErrorsToModel' => true,
            ],
            'workflowLog' => [
                'class' => WorkflowLogFunctionsBehavior::className(),
            ],
            'NotifyBehavior' => [
                'class' => NotifyBehavior::className(),
                'conditions' => [],
            ],
        ]);
    }




    public $start_date_from;
    public $start_date_to;
    public $end_date_from;
    public $end_date_to;

    public function representingColumn() {
        return [
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
        ]);
    }

    public function attributeLabels() {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                    [
                        'start_date_from' => 'Da start_date',
                        'start_date_to' => 'A  start_date',
                        'end_date_from' => 'Da end_date',
                        'end_date_to' => 'A  end_date',
            ]);
    }

    public static function getEditFields() {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'preference_campain_id',
                'label' => $labels['preference_campain_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'preference_channel_id',
                'label' => $labels['preference_channel_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'start_date',
                'label' => $labels['start_date'],
                'type' => 'date'
            ],
            [
                'slug' => 'end_date',
                'label' => $labels['end_date'],
                'type' => 'date'
            ],
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'subtitle',
                'label' => $labels['subtitle'],
                'type' => 'string'
            ],
            [
                'slug' => 'subject',
                'label' => $labels['subject'],
                'type' => 'string'
            ],
            [
                'slug' => 'status',
                'label' => $labels['status'],
                'type' => 'string'
            ],
            [
                'slug' => 'template_id',
                'label' => $labels['template_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'date_newsletter',
                'label' => $labels['date_newsletter'],
                'type' => 'integer'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker() {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents() {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent() {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event 
     */
    public function getColorEvent() {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent() {
        return NULL; //TODO
    }

    /**
     *
     * @return int
     */
    public function getNumberOfRecipients() {
        $query = CampainsQueryUtility::getQueryRecipientsByChannelModel($this);
        return $query->count();
    }

    public function getStatsUserWithMessageViews()
    {

        $tableComunicationSent = TableSentUtility::getTableName($this->id);

        $query = Yii::$app->dbstats->createCommand()
            ->select($tableComunicationSent)
            ->from('tbl_user u')
            ->join('tbl_profile p', 'u.id=p.user_id')
            ->where('id=:id', array(':id'=>$id))
            ->queryRow();
    }

    public function getNameConcatCampainChannel()
    {
        return $this->preferenceCampain->titolo . ' - ' . $this->title;
    }

}
