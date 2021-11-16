<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\moodle\widgets;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\views\DataProviderView;
use open20\amos\core\user\User;
use open20\amos\core\widget\WidgetGraphic;
use open20\amos\core\icons\AmosIcons;

use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\utility\MoodleUtility;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class UserNetworkWidget
 * @package open20\amos\cwh\widgets
 *
 * Get User networks as table list
 * Foreach network type configured in cwh_config (except user), prints the list of networks of which the user is member
 */
class UserNetworkWidget extends WidgetGraphic
{
    /**
     * @var string $widgetTitle
     */
    public $widgetTitle = '';
    
    /**
     * @var int $userId - if null, logged user Id is considered
     */
    public $userId = null;

    /** @var bool|false $isUpdate - true if it edit mode, false otherwise */
    public $isUpdate = false;

    /**
     * @var int $pageSize
     */
    public $pageSize = 6;

    /**
     * @var int $maxButtonCount
     */
    public $maxButtonCount = 5;

    /**
     * @var int $userId
     */
    public $userProfile = null;
    
    /**
     * widget initialization
     */
    public function init()
    {
        parent::init();
        
        $this->widgetTitle = AmosMoodle::tHtml('amosmoodle', 'Corsi FAD');

        $this->setCode('CORSI FAD');
        $this->setLabel(AmosMoodle::tHtml('amosmoodle', 'Corsi FAD'));
        $this->setDescription(AmosMoodle::t('amosmoodle', 'Corsi FAD'));

        if (is_null($this->userId)) {
            $this->userId = \Yii::$app->user->id;
        }
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        $userProfile = User::findOne($this->userId)->userProfile;
        $btnSubscribe = SubscribeUserToFADCourseWidget::widget([
            'userProfile' => $userProfile
        ]);

        if ((!$userProfile->validato_almeno_una_volta) || ($btnSubscribe == '')) {
            return '';
        }
        
        $widget = DataProviderView::widget([
            'dataProvider' => MoodleUtility::getUserCoursesList($this->userId),
            'currentView' =>  ['name' => 'grid'],
            'gridView' => [
                'columns' => [
                    'immagine' => [
                        'label' => AmosMoodle::t('amosmoodle', 'Logo'),
                        'format' => 'html',
                        'value' => function ($model) {
                            /** @var MoodleCourse $model */
                            $url = '/img/img_default.jpg';
                            if (!is_null($model->imageurl)) {
                                $url = $model->imageurl;
                            }
                            $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => AmosMoodle::t('amosmoodle', 'Immagine del corso'), 'title' => $model->name]);
                            return $contentImage;
                        }
                    ],
                    'name' => [
                        'label' => AmosMoodle::t('amosmoodle', 'Titolo'),
                        'value' => function($model) {
                            return $model->name;
                        }
                    ],
                    'created_at' => [
                        'label' => AmosMoodle::t('amosmoodle', 'Data Iscrizione'),
                        'value' => function($model) {
                            return Yii::$app->formatter->asDate($model->created_at);
                        }
                    ],
                    [
                        'class' => 'open20\amos\core\views\grid\ActionColumn',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                $urlParams = [
                                    '/community/join',
                                    'id' => $model->community_id,
                                ];

                                return Html::a(
                                    AmosIcons::show('sign-in', [
                                        'class' => 'btn btn-tool-secondary'
                                    ]), 
                                    Yii::$app->urlManager->createUrl($urlParams), [
                                        'title' => AmosMoodle::t('amosmoodle', 'Vai al corso'),
                                        'data-confirm' => AmosMoodle::t('amosmoodle', 'Vuoi continuare?')
                                        ]
                                    );
                                    
                            },
                        ]
                    ],
                ],
            ],
        ]);

        $title = AmosMoodle::t('amosmoodle', '#list_user_fad_courses');
        $titleLink = AmosMoodle::t('amosmoodle', '#list_user_fad_courses');
        $buttonUrl = [
            '/moodle/course/index',
            'uid' => $this->userId,
        ];

        return '<div id="moodle-fad-courses">'
            . '<h3>' . AmosMoodle::tHtml('amosmoodle', '#my_own_fad_courses') . '</h3>'
            . $btnSubscribe
            . $widget
            . '</div>';
    }
    
    /**
     * @return string
     */
    public static function getSearchPostName()
    {
        return 'searchMoodleCourse';
    }
    
}
