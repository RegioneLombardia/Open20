<?php
namespace open20\amos\notificationmanager\listeners;
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\notificationmanager\base\BuilderFactory;
use open20\amos\notificationmanager\models\ChangeStatusEmail;
use open20\amos\notificationmanager\models\NotificationConf;
use open20\amos\notificationmanager\record\NotifyRecordInterface;
use ReflectionClass;
use Yii;
use yii\base\Exception;


class NotifyWorkflowListener extends \yii\base\BaseObject
{

    /**
     * @param $event
     * @return bool
     */
    public function afterChangeStatus($event)
    {
        try{
            /** @var Record $owner */
            $owner = $event->sender->owner;
            if (!empty($owner))
            {
                if($owner instanceof  NotifyRecordInterface) 
                {
                    if ($owner->sendNotification()) {
                        if(empty($owner->mailStatuses)) {
                            $this->sendValidatorsEmail($event, $owner);
                        } else {
                            $this->setCustomEmails($event, $owner);
                        }
                    }
                }
            }
        }
        catch(Exception $ex)
        {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }
        return true;
    }


    /**
     * Status to Validate, send mail to model validators
     * @param $event
     * @param $model
     */
    public function sendValidatorsEmail($event,$model)
    {
        try {
            $module = \Yii::$app->getModule('notify');
            $rc = new ReflectionClass($model->className());
            if ($rc->hasMethod('getToValidateStatus') && $rc->hasMethod('getValidatedStatus')) {

                if (!strcmp($model->getToValidateStatus(), $event->getEndStatus()->getId())) {
                    $factory = new BuilderFactory();
                    $builder = $factory->create(BuilderFactory::VALIDATORS_MAIL_BUILDER);
                    if ($model instanceof Record) 
                    {
                        //send email to VALIDATORS
                        $userIds = $model->getValidatorUsersId();

                        // send email to FACILITATOR and FACILITATOR_EXTERNAL
                        $user = User::findOne($model->created_by);
                        if(!is_null($user)) {
                            /**var $userprofile UserProfile */
                            $userprofile = $user->getUserProfile()->one();
                            if (!is_null($userprofile)) {
                                $facilUserProfile = $userprofile->getFacilitatorOrDefFacilitator();
                                if (!is_null($facilUserProfile)) {
                                    $userIds[] = $facilUserProfile->user_id;
                                }
                                $facilExternalUserProfile = $userprofile->externalFacilitator;
                                if (!is_null($facilUserProfile)) {
                                    $userIds[] = $facilExternalUserProfile->user_id;
                                }

                            }
                        }

                        $builder->sendEmail($userIds,[$model]);
                    }
                }else{
                    if (!strcmp($model->getValidatedStatus(), $event->getEndStatus()->getId())) 
                    {
                        $validator_id = \Yii::$app->user->id;//$model->getStatusLastUpdateUser($model->getValidatedStatus());
                        $factory = new BuilderFactory();
                        $builder = $factory->create(BuilderFactory::VALIDATED_MAIL_BUILDER);
                        $to[] = $validator_id;
                        if($model->getNotifiedUserId() != $validator_id)
                        {
                            $to[] = $model->getNotifiedUserId();
                        }
                        $builder->sendEmail($to,[$model]);
                    }
                }
            }
        }
        catch(Exception $ex)
        {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }

    }

    /**
     * Send customized mails on status changes defined in model->mailStatuses
     * @param $event
     * @param $model
     */
    public function setCustomEmails($event, $model)
    {
        try {
            $eventEndStatus = $event->getEndStatus()->getId();
            //if model was new record the start status is not defined, check initial status id
            if(!is_null($event->getStartStatus())){
                $eventStartStatus = $event->getStartStatus()->getId();
            }else{
                $eventStartStatus = $model->getWorkflowSource()->getWorkflow($model->formName().'Workflow')->getInitialStatusId();
            }

            /**
             * @var string $endStatus
             * @var ChangeStatusEmail $email
             */
            foreach ($model->mailStatuses as $endStatus => $email){
                // if the model is going to the status for which mail sending is needed
                if(!strcmp($endStatus, $eventEndStatus)){
                    //if send mail independently by start status or the start status matches
                    $startStatus = $email->startStatus;
                    if( !isset($startStatus) || !strcmp($startStatus, $eventStartStatus)){
                        $factory = new BuilderFactory();
                        $builder = $factory->create(BuilderFactory::CUSTOM_MAIL_BUILDER, $email, $endStatus);

                        if (!empty($email->recipientUserIds)) {
                            $builder->sendEmail([$email->recipientUserIds], [$model]);
                        } elseif ($email->toCreator) {
                            $recipients[] = $model->getNotifiedUserId();
                            if ($email->toValidator) {
                                $validator_id = $model->getStatusLastUpdateUser($endStatus);
                                $recipients[] = $validator_id;
                            }
                            $builder->sendEmail($recipients,[$model]);
                        } else { //mail to validators
                            $userIds = $model->getValidatorUsersId();
                            if (empty($userIds)) {
                                $user = User::findOne($model->created_by);
                                if (!is_null($user)) {
                                    /**var $userprofile UserProfile */
                                    $userprofile = $user->getUserProfile()->one();
                                    if (!is_null($userprofile)) {
                                        $facilUserProfile = $userprofile->getFacilitatorOrDefFacilitator();
                                        if (!is_null($facilUserProfile)) {
                                            $userIds[] = $facilUserProfile->user_id;
                                        }
                                    }
                                }
                            }
                            $builder->sendEmail($userIds, [$model]);
                        }
                    }
                }
            }

        } catch(Exception $ex)
        {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }
    }

}