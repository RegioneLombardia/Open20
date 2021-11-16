<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\base\builder
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\base\builder;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\core\user\User;
use open20\amos\notificationmanager\AmosNotify;

use Yii;

/**
 * Class ValidatorsMailBuilder
 * @package open20\amos\notificationmanager\base\builder
 */
class ValidatorsMailBuilder extends AMailBuilder
{
    /**
     * @inheritdoc
     */
    public function getSubject(array $resultset)
    {
        $stdMsg = AmosNotify::t('amosnotify', '#validation_request_email_subject');
        $model = reset($resultset);
        if ($model instanceof ModelLabelsInterface) {
            $grammar = $model->getGrammar();
            if (!is_null($grammar) && ($grammar instanceof ModelGrammarInterface)) {
                $stdMsg = AmosNotify::t('amosnotify', '#publication_request_email_subject', ['contentName' => $grammar->getModelSingularLabel()]);
            }
        }
        return $stdMsg;
    }

    /**
     * @inheritdoc
     */
    public function renderEmail(array $resultset, User $user)
    {
        $ris = "";
        $model = reset($resultset);
        $moduleMyActivities = Yii::$app->getModule('myactivities');
        $moduleNotify = Yii::$app->getModule('notify');
        $url = isset($moduleMyActivities) ? Yii::$app->urlManager->createAbsoluteUrl('myactivities/my-activities/index') : $model->getFullViewUrl();

        try {
            $viewValidatorPath = "@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/validator";
            if($moduleNotify && !empty($moduleNotify->viewPathEmailNotifyValidator) && !empty($moduleNotify->viewPathEmailNotifyValidator[get_class($model)])){
                $viewValidatorPath = $moduleNotify->viewPathEmailNotifyValidator[get_class($model)];
            }

            $controller = Yii::$app->controller;
            $view = $controller->renderPartial($viewValidatorPath, [
                'model' => $model,
                'url' => $url,
                'profile' => $user->userProfile
            ]);

            $ris = $this->renderView(\Yii::$app->controller->module->name, "validators_content_email", [
                'model' => $model,
                'url' => $url,
                'profile' => $user->userProfile,
                'original' => $view
            ]);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }

        return $ris;
    }
}
