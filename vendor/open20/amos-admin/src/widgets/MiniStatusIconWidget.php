<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\widgets
 * @category   CategoryName
 */

namespace open20\amos\admin\widgets;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use raoul2000\workflow\base\Status;
use yii\bootstrap\Widget;

/**
 * Class MiniStatusIconWidget
 * @package open20\amos\admin\widgets
 */
class MiniStatusIconWidget extends Widget
{
    /**
     * @var UserProfile $model
     */
    public $model;
    
    /**
     * @var AmosAdmin $adminModule
     */
    public $adminModule = null;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if (is_null($this->model)) {
            throw new \Exception(AmosAdmin::t('amosadmin', 'Missing model'));
        }
        
        if (is_null($this->adminModule)) {
            $this->adminModule = AmosAdmin::instance();
        }
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        // In this case nothing can be shown to the user because the profiles workflow is disabled.
        if ($this->adminModule->completeBypassWorkflow) {
            return '';
        }
        
        /**
         * The metadata can be found in sw_metadata table. Search UserProfileWorkflow in workflow_id
         * field and one of the below keys in "key" field. You can see the key values for each workflow status.
         */
        
        /** @var Status $workflowStatus */
        $workflowStatus = $this->model->getWorkflowStatus();
        
        // If were found this key, the workflow status must be skipped and nothing can be shown to the user.
        if ($workflowStatus->hasMetadata('tooltipSkip')) {
            return '';
        }
        
        // Regular case when the icon must be shown to the user.
        return Html::tag(
            'span',
            Html::tag('span', '', ['class' => 'mdi ' . $workflowStatus->getMetadata('tooltipMdi')]),
            [
                'class' => 'icon-info-avatar icon-status ' . $workflowStatus->getMetadata('tooltipIcon'),
                'data-toggle' => 'tooltip',
                'data-html' => 'true',
                'title' => AmosAdmin::t('amosadmin', $workflowStatus->getMetadata('tooltipLabel'))
            ]
        );
    }
}
