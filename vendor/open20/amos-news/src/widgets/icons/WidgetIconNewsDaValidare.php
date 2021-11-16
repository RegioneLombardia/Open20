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
//use open20\amos\news\models\News;
//use open20\amos\news\models\search\NewsSearch;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconNewsDaValidare
 * @package open20\amos\news\widgets\icons
 */
class WidgetIconNewsDaValidare extends WidgetIcon
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

        $this->setLabel(AmosNews::tHtml('amosnews', 'Notizie da validare'));
        $this->setDescription(AmosNews::t('amosnews', 'Visualizza le news da validare da parte dell\'utente'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('news');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('feed');
        }

        $this->setUrl(['/news/news/to-validate-news']);
        $this->setCode('NEWS_VALIDATE');
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
//                    News::class,
//                    $search->buildQuery([], 'to-validate')
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
