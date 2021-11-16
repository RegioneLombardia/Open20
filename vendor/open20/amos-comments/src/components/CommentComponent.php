<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\components
 * @category   CategoryName
 */

namespace open20\amos\comments\components;

use open20\amos\comments\AmosComments;
use open20\amos\comments\models\CommentInterface;
use open20\amos\comments\widgets\CommentsWidget;
use Yii;
use yii\base\Component;
use yii\base\Event;

/**
 * Class CommentComponent
 * @package open20\amos\events\components
 */
class CommentComponent extends Component implements CommentComponentInterface
{

    /**
     * Method that enable the comments on a specific model.
     * @param \yii\base\Event $event
     */
    public function showComments(Event $event)
    {
        if (isset(Yii::$app->controller->model)) {
            /** @var \open20\amos\core\record\Record $controllerModel */
            $controllerModel = Yii::$app->controller->model;           
            if ($this->checkDisableAutoDisplay($controllerModel) && $this->checkCommentsModuleEnabled($controllerModel) && $this->checkCommentsEnabledOnModel($controllerModel)) {
                echo CommentsWidget::widget([
                    'model' => Yii::$app->controller->model
                ]);
            }
        }
    }

    /**
     * The method checks if the comment module is present and the actual controller
     * model class name is present in the comments module configurations.
     * @param \open20\amos\core\record\Record $controllerModel
     * @return bool
     */
    protected function checkCommentsModuleEnabled($controllerModel)
    {
        /** @var AmosComments $commentsModule */
        $commentsModule = Yii::$app->getModule(AmosComments::getModuleName());
        return (
            !is_null($commentsModule) &&
            isset($commentsModule->modelsEnabled) &&
            in_array($controllerModel::className(), $commentsModule->modelsEnabled)
            );
    }

    /**
     * Method that checks if the controller model is an instance of CommentInterface
     * and then if the model is commentable.
     * @param \open20\amos\core\record\Record $controllerModel
     * @return bool
     */
    protected function checkCommentsEnabledOnModel($controllerModel)
    {
        if ($controllerModel instanceof CommentInterface) {
            /** @var CommentInterface $controllerModel */
            return $controllerModel->isCommentable();
        }
        return false;
    }

    protected function checkDisableAutoDisplay($controllerModel)
    {
        $module = \Yii::$app->getModule('comments');       
        if (!in_array(get_class($controllerModel), $module->disableAutoDisplay)) {
            return true;
        }
        return false;
    }
}