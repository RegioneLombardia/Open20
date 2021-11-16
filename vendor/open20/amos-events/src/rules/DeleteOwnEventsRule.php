<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\rules
 * @category   CategoryName
 */

namespace open20\amos\events\rules;

use open20\amos\core\rules\DefaultOwnContentRule;
use open20\amos\events\models\Event;

/**
 * Class DeleteOwnEventsRule
 * @package open20\amos\events\rules
 */
class DeleteOwnEventsRule extends DefaultOwnContentRule
{
    public $name = 'deleteOwnEvents';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['model'])) {
            /** @var Event $model */
            $model = $params['model'];

            if (!$model->id) {
                $post = \Yii::$app->getRequest()->post();
                $get = \Yii::$app->getRequest()->get();
                if (isset($get['id'])) {
                    $model = $this->instanceModel($model, $get['id']);
                } elseif (isset($post['id'])) {
                    $model = $this->instanceModel($model, $post['id']);
                }
            }
            if (!($model instanceof Event) || !$model->id) {
                return false;
            }

            /**
             * La logica implementata nel codice commentato non è conforme agli altri plugin di contenuto e non è descritta in nessuna analisi.
             * L'unico punto in cui si parla di workflow dei contenuti è il task POII-1193 nel quale in ogni caso non si parla di amos-events,
             * se non per i pulsanti durante la creazione, quindi viene implementato il funzionamento standard degli altri plugin di contenuto.
             */
//            if (!empty($model->getWorkflowStatus())) {
//                if (($model->getWorkflowStatus()->getId() == Event::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST ) && !(\Yii::$app->user->can('EventValidate', ['model' => $model]))) {
//                    return false;
//                }
//            }
            if (!empty($model->getWorkflowStatus())) {
                if ((($model->getWorkflowStatus()->getId() == Event::EVENTS_WORKFLOW_STATUS_DRAFT) || \Yii::$app->user->can('EventValidate', ['model' => $model])) && ($model->created_by == $user)) {
                    return true;
                }
            }
        }
        return false;
    }
}
