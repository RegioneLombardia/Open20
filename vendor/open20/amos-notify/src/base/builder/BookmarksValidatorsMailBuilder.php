<?php

/**
 * Aria S.p.A.
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
 * Class BookmarksValidatorsMailBuilder
 * @package open20\amos\notificationmanager\base\builder
 */
class BookmarksValidatorsMailBuilder extends AMailBuilder
{
    /**
     * @inheritdoc
     */
    public function getSubject(array $resultset)
    {
        $msg = AmosNotify::t('amosnotify', '#publish_request_bookmark_email_subject');
        return $msg;
    }

    /**
     * @inheritdoc
     */
    public function renderEmail(array $resultset, User $user)
    {
        $ris = "";
        $model = reset($resultset);
        $moduleNotify = Yii::$app->getModule('notify');
        $url = Yii::$app->urlManager->createAbsoluteUrl($model->getFullUpdateUrl());

        try {
            $viewValidatorPath = "@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/bookmarksValidator";
            if($moduleNotify && !empty($moduleNotify->viewPathEmailNotifyValidator) && !empty($moduleNotify->viewPathEmailNotifyValidator[get_class($model)])){
                $viewValidatorPath = $moduleNotify->viewPathEmailNotifyValidator[get_class($model)];
            }

            $controller = Yii::$app->controller;
            $view = $controller->renderPartial($viewValidatorPath, [
                'model' => $model,
                'url' => $url,
                'profile' => $user->userProfile
            ]);

            $ris = $this->renderView(\Yii::$app->controller->module->name, "bookmarks_validators_content_email", [
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
