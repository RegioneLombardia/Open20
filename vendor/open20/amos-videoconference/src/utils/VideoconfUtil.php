<?php
namespace open20\amos\videoconference\utils;

use open20\amos\core\user\User;
use open20\amos\videoconference\models\VideoconfUsersMm;

class VideoconfUtil {

    /**
     * @param $model
     * @return bool
     * @throws \Exception
     */
    public static function implodeStartEndDate($model) {
        $model->begin_date_hour = $model->start_date . ' ' . $model->start_hour;
        if($model->start_hour > $model->end_hour) {
            $endDate = new \DateTime($model->start_date);
            $endDate->modify('+1day');
            $model->end_date_hour = $endDate->format('Y-m-d') . ' ' . $model->end_hour;
        } elseif($model->start_hour <= $model->end_hour) {
            $model->end_date_hour = $model->start_date . ' ' . $model->end_hour;
        }
    }

    /**
     * @param $model
     * @return void
     */
    public static function explodeStartEndDate($model) {
        list($model->start_date, $model->start_hour) = explode(" ", $model->begin_date_hour);
        list($model->end_date, $model->end_hour) = explode(" ", $model->end_date_hour);
    }


    public static function isCurrentUserSubscribed($idVideoconf) {
        $user = User::findOne(\Yii::$app->user->id);
        $result = VideoconfUsersMm::find()
            ->andWhere(['user_profile_id' => $user->userProfile->id])
            ->andWhere(['videoconf_id' => $idVideoconf])
            ->andWhere(['invited_only' => 0])
            ->one();
        return !is_null($result);
    }

    /**
     * @return mixed
     */
    public static function getScopeCommunity()
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (!is_null($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                return $scope['community'];
            }
        }
        return null;
    }

    /** Prepara i messaggi di errore $model->getErrors() per essere correttamente visualizzati nei flash
     * @param $array
     * @return array
     */
    public static function extractErrorsFlash($array)
    {
        $messages = array_values($array);
        $errors = [];
        foreach($messages as $message) {
            $errors []= $message[0];
        }
        return $errors;
    }
}
