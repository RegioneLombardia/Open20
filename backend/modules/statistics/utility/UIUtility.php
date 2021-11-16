<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    
 * @category   CategoryName
 */

namespace backend\modules\statistics\utility;

use Yii;

/**
 * Class UIUtility
 * @package backend\modules\statistics\utility
 */
class UIUtility
{

    /**
     * @return array
     */
    public static function getSidebarDataAnalysis()
    {

        $menu = [
            [
                'mainMenu' => [
                    'label' => Yii::t('statistics', 'Dati campagne'),
                    'icon' => '/sprite/material-sprite.svg#ic_contact_mail',
                    'activeTargetAction' => 'index',
                    'titleLink' => Yii::t('statistics', 'Dati campagne'),
                ],
                'dinamicSubMenu' => [
                    '1' => [
                        'label' => Yii::t('statistics', 'Aperture'),
                        'url' => '/statistics/preference-campaigns/index',
                        'activeTargetAction' => 'index',
                        'titleLink' => Yii::t('statistics', 'Anagrafiche'),
                    ],
                    '2' => [
                        'label' => Yii::t('statistics', 'Aggr. Tematiche'),
                        'url' => '/statistics/preference-campaigns/items',
                        'activeTargetAction' => 'items',
                        'titleLink' => Yii::t('statistics', 'Iscrizioni'),
                    ]
                ]
            ],

            [
                'mainMenu' => [
                    'label' => Yii::t('statistics', 'Dati utenti'),
                    'icon' => '/sprite/material-sprite.svg#ic_dashboard',
                    'activeTargetAction' => 'update-contents',
                    'titleLink' => Yii::t('statistics', 'Dati utenti'),
                ],

                'dinamicSubMenu' => [
                    '1' => [
                        'label' => Yii::t('statistics', 'Anagrafiche'),
                        'url' => '/statistics/preference-campaigns/personal-data',
                        'activeTargetAction' => 'personal-data',
                        'titleLink' => Yii::t('statistics', 'Anagrafiche'),
                    ],
                    '2' => [
                        'label' => Yii::t('statistics', 'Iscrizioni'),
                        'url' => '/statistics/preference-campaigns/inscriptions',
                        'activeTargetAction' => 'inscriptions',
                        'titleLink' => Yii::t('statistics', 'Iscrizioni'),
                    ],
                    '3' => [
                        'label' => Yii::t('statistics', 'Errori'),
                        'url' => '/statistics/preference-campaigns/errors',
                        'activeTargetAction' => 'errors',
                        'titleLink' => Yii::t('statistics', 'Errori'),
                    ],
                ]
            ]
        ];

        return $menu;
    }
}
