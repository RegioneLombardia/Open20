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

use open20\amos\dashboard\models\AmosWidgets;

use open20\amos\news\AmosNews;
//use open20\amos\news\models\search\NewsSearch;
//use open20\amos\news\models\News;

use open20\amos\utility\models\BulletCounters;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconNews
 * @package open20\amos\news\widgets\icons
 */
class WidgetIconNews extends WidgetIcon
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

        $this->setLabel(AmosNews::tHtml('amosnews', 'Notizie di mio interesse'));
        $this->setDescription(AmosNews::t('amosnews', 'Visualizza le notizie di mio interesse'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('news');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('feed');
        }

        $this->setUrl(['/news/news/own-interest-news']);
        $this->setCode('NEWS');
        $this->setModuleName('news');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

        // Read and reset counter from bullet_counters table, bacthed calculated!
        if ($this->disableBulletCounters == false) {
            $this->setBulletCount(
                BulletCounters::getAmosWidgetIconCounter(
                    Yii::$app->getUser()->getId(), 
                    AmosNews::getModuleName(),
                    $this->getNamespace(),
                    $this->resetBulletCount(),
                    WidgetIconAllNews::className()
                )
            );
        }
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
            ['children' => $this->getWidgetsIcon()]
        );
    }

    /**
     * @inheritdoc
     * 
     * @return type
     */
    public function getWidgetsIcon()
    {
        return AmosWidgets::find()
            ->andWhere(['child_of' => self::className()])
            ->all();
    }

}
