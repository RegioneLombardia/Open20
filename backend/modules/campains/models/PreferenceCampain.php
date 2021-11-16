<?php

namespace backend\modules\campains\models;

use backend\modules\campains\events\workflow\PreferenceWorkflowEvent;
use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;


/**
 * This is the model class for table "preference_campain".
 */
class PreferenceCampain extends \backend\modules\campains\models\base\PreferenceCampain implements ContentModelInterface{

    // Preference Campain Workflow
    const PREFERENCE_CAMPAIN_WORKFLOW = "PreferenceCampainWorkflow";
    const PREFERENCE_CAMPAIN_WORKFLOW_STATUS_TOBEAPPROVED = "PreferenceCampainWorkflow/TOBEAPPROVED";
    const PREFERENCE_CAMPAIN_WORKFLOW_STATUS_APPROVED = "PreferenceCampainWorkflow/APPROVED";
    const PREFERENCE_CAMPAIN_WORKFLOW_STATUS_SENDINGINPROGRESS = "PreferenceCampainWorkflow/SENDINGINPROGRESS";
    const PREFERENCE_CAMPAIN_WORKFLOW_STATUS_SENT = "PreferenceCampainWorkflow/SENT";

    /**
     * Init 
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::PREFERENCE_CAMPAIN_WORKFLOW)->getInitialStatusId();
        }

        $this->on('afterEnterStatus{' . self::PREFERENCE_CAMPAIN_WORKFLOW_STATUS_APPROVED . '}', [new PreferenceWorkflowEvent(), 'whenCamapinIsValidated'], $this);

      
        /**
         * evento per i controlli aggiuntivi al cambio di stato della preference_campain
         */
        $this->on('beforeEnterStatus{'. self::PREFERENCE_CAMPAIN_WORKFLOW_STATUS_APPROVED . '}', [ new PreferenceWorkflowEvent(), 'whenCampainChangeStateToApproved'], $this);
        
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
                'defaultWorkflowId' => self::PREFERENCE_CAMPAIN_WORKFLOW,
                'propagateErrorsToModel' => true,
            ],
            'workflowLog' => [
                'class' => WorkflowLogFunctionsBehavior::className(),
            ],
        ]);
    }




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
                ArrayHelper::merge(parent::attributeLabels(),[]);
    }

    public static function getEditFields() {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'titolo',
                'label' => $labels['titolo'],
                'type' => 'string'
            ],
            [
                'slug' => 'status',
                'label' => $labels['status'],
                'type' => 'string'
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
    * Restituisce un'immagine se associata al model
    */
    public function getAvatarUrl($dimension = 'small')
    {
        $url = '/img/img_default.jpg';
        //funzione da implementare
        return $url;
    }
    

}
