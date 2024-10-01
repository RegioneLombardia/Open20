<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\rules
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\rules;

use yii\rbac\Rule;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;

/**
 * Class ReadContentRule
 * @package open20\amos\sondaggi\rules
 */
class SondaggiSessioniWorkflowGeneric extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'sondaggiSessioniWorkflowGeneric';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $sondaggiModule = \open20\amos\sondaggi\AmosSondaggi::instance();
        $roles          = \Yii::$app->authManager->getRolesByUser($user);
        if ($params['model'] instanceof SondaggiRisposteSessioni && !$params['model']->sondaggi->isCompilable())
            return false;
        if (!empty($params['status'])) {
            $status = $params['status'];
            if (!empty($status)) {
                foreach ($roles as $k => $v) {
                    if (in_array($k, $sondaggiModule->compilationWorkflowRules[$status])) {

                        return true;
                    }
                }
                return false;
            }
        } else if (!empty($params['model'])) {
            /**
             * @var \open20\amos\sondaggi\models\SondaggiRisposteSessioni $sessionModel
             */
            $sessionModel = $params['model'];
            if ($sessionModel->status == \open20\amos\sondaggi\models\SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO) {
                foreach ($roles as $k2 => $v2) {
                    if (in_array($k2, $sondaggiModule->compilationWorkflowRules)) {
                        return true;
                    }
                }
                return false;
            } else {
                return true;
            }
        }
        return true;
    }
}