<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\modules\formazione\widgets\graphics
 * @category   CategoryName
 */

namespace open20\video\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open20\video\models\search\VideoSearch;
use open20\video\Module;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;

/**
 * Class WidgetGraphicFormazione
 * @package frontend\modules\formazione\widgets\graphics
 */
class WidgetGraphicVideo extends WidgetGraphic
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setCode('ULTIMI_VIDEO_GRAPHIC');
        $this->setLabel(Module::t('amosvideo', '#widget_graphic_video_label'));
        $this->setDescription(Module::t('amosvideo', '#widget_graphic_video_description'));
    }

    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        $search = new VideoSearch();
        $search->setNotifier(new NotifyWidgetDoNothing());
        $params = [
            'VideoSearch' => [
                'in_evidenza' => 1
            ]
        ];
        $formazioneLimit = Module::MAX_VIDEO_ON_DASHBOARD;
        $dataProvider = $search->search($params, $formazioneLimit);
        $video = $dataProvider->query->one();

        return $this->render('ultimo_video_community', [
            'video' => $video,
            'widget' => $this,
            'toRefreshSectionId' => 'widgetGraphicVideo'
        ]);
    }
}
