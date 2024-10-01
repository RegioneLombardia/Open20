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
use open20\amos\cwh\base\ModelContentInterface;
use Yii;

/**
 * Class ValidatedMailBuilder
 * @package open20\amos\notificationmanager\base\builder
 */
class ValidatedMailBuilder extends AMailBuilder
{
    /**
     * @inheritdoc
     */
    public function getSubject(array $resultset)
    {
        $stdMsg = AmosNotify::t('amosnotify', "Content has been validated");
        $model = reset($resultset);
        if ($model instanceof ModelLabelsInterface) {
            $grammar = $model->getGrammar();
            if (!is_null($grammar) && ($grammar instanceof ModelGrammarInterface)) {
                $stdMsg = AmosNotify::t('amosnotify', '#publication_email_subject', ['contentName' => $grammar->getModelSingularLabel()]);
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
        $moduleNotify = Yii::$app->getModule('notify');

        try {
            $viewValidatorPath = "@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/validated";
            if($moduleNotify && !empty($moduleNotify->viewPathEmailNotifyValidated) && !empty($moduleNotify->viewPathEmailNotifyValidated[get_class($model)])){
                $viewValidatorPath = $moduleNotify->viewPathEmailNotifyValidated[get_class($model)];
            }

            $userid = \Yii::$app->user->id; //$model->getStatusLastUpdateUser($model->getValidatedStatus());
            if (!is_null($userid)) {
                $loggedUser = User::findOne($userid);
                $comment = null;
                if (method_exists($model, 'getStatusLastUpdateComment')) {
                    $comment = $model->getStatusLastUpdateComment($model->getValidatedStatus());
                }
                /**
                 *
                 */
                $validator = $loggedUser->getUserProfile()->one();

                $controller = \Yii::$app->controller;

                    $view = $controller->renderPartial($viewValidatorPath, [
                    'model' => $model,
                    'validator' => $validator ,
                    'comment' => $comment,
                    'profile' => $user->userProfile,
                    ]);


                $userProfile = $model->createdUserProfile;

                $layout = '{publisher}';

                if ($model instanceof ModelContentInterface) {
                    $layout = '{publisher}{publishingRules}{targetAdv}';
                }

                $params = [
                    'model' => $model,
                    'validator' => $validator,
                    'comment' => $comment,
                    'original' => $view,
                    'title' => $model->getTitle(),
                    'content_url' => \Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()),
                    'article' => $model->getGrammar()->getArticleSingular(),
                    'label' => $model->getGrammar()->getModelSingularLabel(),

                    'publisher_widget' => \open20\amos\core\forms\PublishedByWidget::widget([
                        'model' => $model,
                        'layout' => $layout,
                    ]),
                    'publisher_name' => $userProfile->nome,
                    'publisher_lastname' => $userProfile->cognome,
                    'publisher_avatar' => $userProfile->avatarUrl,

                    'validator_widget' => \open20\amos\admin\widgets\UserCardWidget::widget([
                        'model' => $validator,
                        'onlyAvatar' => true,
                        'absoluteUrl' => true
                    ]),
                    'validator_name' => $validator->nome,
                    'validator_lastname' => $validator->cognome,
                    'validator_avatar' => $validator->avatarUrl,
                ];

                if(!is_null($userProfile)) {
                    $params['publisher_widget'] = \open20\amos\admin\widgets\UserCardWidget::widget([
                        'model' => $userProfile,
                        'onlyAvatar' => true,
                        'absoluteUrl' => true
                    ]);
                }

                if (!$model instanceof \open20\amos\admin\models\UserProfile) {
                    $params['description'] = $model->getDescription(false);
                }

                $ris = $this->renderView(\Yii::$app->controller->module->name, "validated_content_email", $params);
            }
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }

        return $ris;
    }
}
