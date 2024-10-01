<?php

namespace open20\video\utils;
class VideoUtil {
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
}