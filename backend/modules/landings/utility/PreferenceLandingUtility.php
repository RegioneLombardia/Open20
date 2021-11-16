<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 09/09/2020
 * Time: 18:36
 */
namespace backend\modules\landings\utility;


use backend\modules\campains\models\PreferenceCampain;
use backend\modules\landings\models\PreferenceLanding;
use backend\modules\campains\models\PreferenceCampainSection;
use backend\modules\campains\models\PreferenceCampainContainer;

class PreferenceLandingUtility
{

    /**
     * @param $model PreferenceLanding
     * @return string
     */
    public static function getUrlLanding($model)
    {
        if ($model) {
            return \Yii::$app->params['platform']['frontendUrl'] . '/it/' . $model->url;
        }
        return '';
    }

    /**
     * @param $model PreferenceLanding
     * @return string
     */
    public static function getUrlLandingPreview($model)
    {
        if ($model) {
            return \Yii::$app->params['platform']['frontendUrl'] . '/api/1/preview-landing?itemId=' . $model->luya_page_id;
        }
        return '';
    }


    /**
     * Metodo per l'estrazione della preference_campain associato alla landing
     *
     * @param PreferenceLandig $preference_landing
     * @return PreferenceLanding
     */
    public static function getPreferenceCampainContainer($preference_landing){

        $preference_campains = PreferenceCampain::find()
                                ->join('LEFT JOIN', 'preference_campain_channel_mm', 'preference_campain_channel_mm.preference_campain_id = preference_campain.id')
                                ->join('LEFT JOIN', 'preference_campain_section', 'preference_campain_channel_mm.id = preference_campain_section.preference_campain_channel_mm_id')
                                ->join('LEFT JOIN', 'preference_campain_container', 'preference_campain_container.preference_campain_section_id = preference_campain_section.id')
                                ->andWhere(['preference_campain_channel_mm.deleted_at' => null ])
                                ->andWhere(['preference_campain_section.deleted_at' => null ])
                                ->andWhere(['preference_campain_container.deleted_at' => null ])
                                ->andWhere([
                                    'preference_campain_container.preference_landing_id' => $preference_landing->id,
                                ])
                                ->all();

        return $preference_campains;
    }   
}