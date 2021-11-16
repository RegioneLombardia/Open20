<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\amos\news\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;

use open20\amos\news\AmosNews;
//use open20\amos\news\models\search\NewsSearch;
//use open20\amos\news\models\News;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconAllNews
 * @package open20\amos\news\widgets\icons
 */
class WidgetIconAdminAllNews extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-primary'
        ];

        $this->setLabel(AmosNews::tHtml('amosnews', 'Amministra notizie'));
        $this->setDescription(AmosNews::t('amosnews', 'Visualizza tutte le notizie'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('news');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('feed');
        }

        $this->setUrl(['/news/news/admin-all-news']);
        $this->setCode('ADMIN-ALL-NEWS');
        $this->setModuleName('news');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

//        if ($this->disableBulletCounters == false) {
//            $search = new NewsSearch();
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    News::className(),
//                    $search->buildQuery([], 'admin-all')
//                )
//            );
//        }
    }

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     *         
     * @inheritdoc
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => []]
        );
    }

}
