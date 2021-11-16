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

use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconNewsCategorie extends WidgetIcon
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

        $this->setLabel(AmosNews::tHtml('amosnews', 'Categorie notizie'));
        $this->setDescription(AmosNews::t('amosnews', 'Visualizza le categorie delle news'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('news');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('feed');
        }

        $this->setUrl(['/news/news-categorie/index']);
        $this->setCode('NEWS_CATEGORIE');
        $this->setModuleName('news');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        //aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => []]
        );
    }

}
