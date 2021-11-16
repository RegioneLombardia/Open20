<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

namespace frontend\utility;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility as AmsoUserProfileUtility;
use open20\amos\community\models\Community;
use open20\amos\core\utilities\Email;
use open20\amos\emailmanager\AmosEmail;
use Exception;
use Yii;
use yii\log\Logger;



class UserProfileUtility extends AmsoUserProfileUtility
{
    /**
     * @param UserProfile $model
     * @param Community $model
     * @return bool
     */
    public static function sendCredentialsMail($model, $community = null, $module_name = null)
    {
        try {
            $model->user->generatePasswordResetToken();
            $model->user->save(false);
            /** @var AmosAdmin $adminModule */
            $adminModule = Yii::$app->getModule((empty($module_name)? AmosAdmin::getModuleName() : $module_name));
            $subjectView = $adminModule->htmlMailSubject;
            $contentView = $adminModule->htmlMailContent;
            $mailModule = Yii::$app->getModule(AmosEmail::getModuleName());
            $mailModule->defaultLayout = "layout_without_header_and_footer";
            $subject = Email::renderMailPartial($subjectView, ['profile' => $model], $model->user->id);
            $mail = Email::renderMailPartial($contentView, ['profile' => $model, 'community' => $community], $model->user->id);
            return Email::sendMail(Yii::$app->params['supportEmail'], [$model->user->email], $subject, $mail, []);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return false;
    }

    /**
     * @param UserProfile $model
     * @param Community $model
     * @param string $urlPrevious
     * @return bool
     */
    public static function sendPasswordResetMail($model, $community = null, $urlPrevious = null)
    {
        try {
            $model->user->generatePasswordResetToken();
            $model->user->save(false);
            $subjectView = '@vendor/open20/amos-admin/src/mail/user/forgotpassword-subject';
            $contentView = '@vendor/open20/amos-admin/src/mail/user/forgotpassword-html';
            $mailModule = Yii::$app->getModule(AmosEmail::getModuleName());
            $mailModule->defaultLayout = "layout_without_header_and_footer";
            $subject = Email::renderMailPartial($subjectView, ['profile' => $model], Yii::$app->getUser()->id);
            $mail = Email::renderMailPartial($contentView, ['profile' => $model, 'community' => $community, 'urlPrevious' => $urlPrevious], Yii::$app->getUser()->id);
            return Email::sendMail(Yii::$app->params['supportEmail'], [$model->user->email], $subject, $mail, []);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return false;
    }
}