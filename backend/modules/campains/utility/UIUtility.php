<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\utility
 * @category   CategoryName
 */

namespace backend\modules\campains\utility;

use backend\modules\campains\models\PreferenceCampain;
use backend\modules\campains\utility\CampainsUtility;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class UIUtility
 * @package backend\modules\campains\utility
 */
class UIUtility
{

    /**
     * @param PreferenceCampain $model
     * @return array
     */
    public static function getSidebarCampains($model)
    {
        // submenu
        $dinamicSubMenu = array();

        // PreferenceCampainChannelMm SMS associated with PreferenceCampain
        $preference_campain_channel_sms = CampainsUtility::campainGetSmsModel($model);

        // PreferenceCampainChannelMm Newsletter associated with PreferenceCampain
        $preference_campain_channel_newsletter = CampainsUtility::campainGetNewsletterModel($model);

        // check if the model is not new record
        if (!$preference_campain_channel_newsletter->isNewRecord) {

            $dinamicSubMenu['menuChannelEmail'] = [
                'label' => Yii::t('campains', 'Email - Dati generali'),
                'url' => '/campains/preference-campain/update-detail-email?id=' . $preference_campain_channel_newsletter->id,
                'activeTargetAction' => 'update-detail-email',
                'titleLink' => Yii::t('campains', 'Email - Dati generali'),
            ];

            $dinamicSubMenu['menuChannelEmailContent'] = [
                'label' => Yii::t('campains', 'Email - Struttura'),
                'url' => '/campains/preference-campain/update-content-email?id=' . $model->id,
                'activeTargetAction' => 'update-content-email',
                'titleLink' => Yii::t('campains', 'Email - Struttura'),
            ];
        }

        // PreferenceCampainChannelMm App associated with PreferenceCampain
        $preference_campain_channel_app = CampainsUtility::campainGetAppModel($model);

        // check if the model is not new record
        if (!$preference_campain_channel_sms->isNewRecord) {

            $dinamicSubMenu['menuChannelSMS'] = [
                'label' => Yii::t('campains', 'SMS'),
                'url' => '/campains/preference-campain/update-content-sms?id=' . $preference_campain_channel_sms->id,
                'activeTargetAction' => 'update-content-sms',
                'titleLink' => Yii::t('campains', 'SMS'),
            ];
        }

        // check if the model is not new record
        if (!$preference_campain_channel_app->isNewRecord) {

            $dinamicSubMenu['menuChannelApp'] = [
                'label' => Yii::t('campains', 'App - Dati generali'),
                'url' => '/campains/preference-campain/update-detail-app?id=' . $preference_campain_channel_app->id,
                'activeTargetAction' => 'update-detail-app',
                'titleLink' => Yii::t('campains', 'App - Dati generali'),
            ];

            $dinamicSubMenu['menuChannelEmailApp'] = [
                'label' => Yii::t('campains', 'App - Struttura'),
                'url' => '/campains/preference-campain/update-content-app?id=' . $model->id,
                'activeTargetAction' => 'update-content-app',
                'titleLink' => Yii::t('campains', 'App - Struttura'),
            ];
        }

        $menu = [

            [
                'mainMenu' => [
                    'label' => Yii::t('campains', 'Destinatari e canali'),
                    'icon' => '/sprite/material-sprite.svg#ic_contact_mail',
                    'activeTargetAction' => 'update-recipients',
                    'titleLink' => Yii::t('campains', 'Destinatari e canali'),
                    'url' => '/campains/preference-campain/update-recipients?id=' . $model->id,
                ],
            ],

            [
                'mainMenu' => [
                    'label' => Yii::t('campains', 'Contenuti'),
                    'icon' => '/sprite/material-sprite.svg#ic_dashboard',
                    'activeTargetAction' => 'update-contents',
                    'titleLink' => Yii::t('campains', 'Contenuti'),
                    // 'url' => '/campains/preference-campain/update-contents?id=' . $model->id,
                ],

                'dinamicSubMenu' => $dinamicSubMenu,
            ]
        ];

        if (CampainsUtility::campainHaveNewsletterChannel($model)){
            $menu = array_merge( $menu, [ 

                [
                    'mainMenu' => [
                        'label' => Yii::t('campains', 'Landing pages'),
                        'icon' => '/sprite/material-sprite.svg#ic_web',
                        'activeTargetAction' => 'update-landing-pages',
                        'titleLink' => Yii::t('campains', 'Landing pages'),
                        'url' => '/campains/preference-campain/update-landing-pages?id=' . $model->id,
                    ],
                ],
            ]);
        }
        
        $menu = array_merge( $menu, [
            [
                'mainMenu' => [
                    'label' => Yii::t('campains', 'Invio e monitoraggio'),
                    'icon' => '/sprite/material-sprite.svg#ic_email',
                    'activeTargetAction' => 'update-sending-tracking',
                    'titleLink' => Yii::t('campains', 'Invio e monitoraggio'),
                    'url' => '/campains/preference-campain/update-sending-tracking?id=' . $model->id,
                ],
            ],

        ]);

        return $menu;
    }
}
