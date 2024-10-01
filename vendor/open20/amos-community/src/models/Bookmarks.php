<?php

namespace open20\amos\community\models;

use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\notificationmanager\behaviors\NotifyBehavior;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "community_links".
 */
class Bookmarks extends \open20\amos\community\models\base\Bookmarks implements ContentModelInterface, ViewModelInterface
{
    // Workflow ID
    const BOOKMARKS_WORKFLOW = 'BookmarksWorkflow';
    // Workflow states IDS
    const BOOKMARKS_STATUS_DRAFT = 'BookmarksWorkflow/DRAFT';
    const BOOKMARKS_STATUS_PUBLISHED = 'BookmarksWorkflow/PUBLISHED';
    const BOOKMARKS_STATUS_TOVALIDATE = 'BookmarksWorkflow/TOVALIDATE';

    const LINK_VIEW_NUMBER = 5;

    public function init()
    {
        parent::init();
        if($this->isNewRecord){
            $this->status = $this->getWorkflowSource()->getWorkflow(self::BOOKMARKS_WORKFLOW)->getInitialStatusId();
        }
    }

    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'NotifyBehavior' => [
                    'class' => NotifyBehavior::className(),
                    'conditions' => []
                ],
                'workflow' => [
                    'class' => SimpleWorkflowBehavior::className(),
                    'defaultWorkflowId' => self::BOOKMARKS_WORKFLOW,
                    'propagateErrorsToModel' => true
                ],
                'WorkflowLogFunctionsBehavior' => [
                    'class' => WorkflowLogFunctionsBehavior::class,
                ],
            ]
        );
    }

    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'titolo',
                'label' => $labels['titolo'],
                'type' => 'string'
            ],
            [
                'slug' => 'link',
                'label' => $labels['link'],
                'type' => 'string'
            ],
            [
                'slug' => 'data_pubblicazione',
                'label' => $labels['data_pubblicazione'],
                'type' => 'datetime'
            ],
            [
                'slug' => 'community_id',
                'label' => $labels['community_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'creatore_id',
                'label' => $labels['creatore_id'],
                'type' => 'integer'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }


    /**
     * @return string
     */
    public function getCommunityName(){
        return $this->community->name;
    }

    /**
     * @inheritdoc
     */
    public function getToValidateStatus()
    {
        return self::BOOKMARKS_STATUS_TOVALIDATE;
    }

    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return self::BOOKMARKS_STATUS_PUBLISHED;
    }

    /**
     * @inheritdoc
     */
    public function getDraftStatus()
    {
        return self::BOOKMARKS_STATUS_DRAFT;
    }

    /**
     * @inheritdoc
     */
    public function getValidatorRole()
    {
        return 'VALIDATORE_BOOKMARK';
    }


}
