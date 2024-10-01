<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\widgets\graphics
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\widgets\graphics;


use open20\amos\core\widget\WidgetGraphic;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\search\SondaggiSearch;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;
use yii\db\Expression;

class WidgetGraphicsSondaggioLive extends WidgetGraphic
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setCode('LIVE_SONDAGGI_GRAPHIC');
        $this->setLabel(AmosSondaggi::tHtml('amossondaggi', 'Sondaggio Live'));
        $this->setDescription(AmosSondaggi::t('amossondaggi', 'Sondaggio Live'));
    }

    /**
     *
     * @return string
     */
    public function getHtml()
    {
        $search = new SondaggiSearch();
        $search->setNotifier(new NotifyWidgetDoNothing());

        $sondaggi_types = new \open20\amos\sondaggi\models\base\SondaggiTypes();
        $dataProvider = $search->ultimiSondaggiLive($_GET);

        $sondaggi = $dataProvider->query
            ->andWhere(['>', 'end_date_hour_live', new Expression('NOW()')])
            ->andWhere(['<', 'begin_date_hour_live', new Expression('NOW()')])
            ->all();

//            ->andWhere(['OR',
//                ['AND', ['end_date_hour_live' => null],  ['begin_date_hour_live' => null]],
//                ['AND', ['>', 'end_date_hour_live', new Expression('NOW()')],  ['<', 'begin_date_hour_live', new Expression('NOW()')]],
//            ])
        $ok = false;

        foreach ($sondaggi as $sondaggio) {
            if (!empty($sondaggio) && $sondaggio->sondaggio_chiuso_frontend != 1) {
                $ok = true;
            }
        }

        if ($ok) {
            return $this->render('sondaggi_live', [
                'sondaggi' => $sondaggi,
                'widget' => $this,
                'toRefreshSectionId' => 'widgetGraphicSondaggi'
            ]);
        }

        return '';
    }

}
