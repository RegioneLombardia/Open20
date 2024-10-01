<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    openinnovation/landing/widgets/
 * @category   CategoryName
 */

namespace amos\podcast\widgets\icons;

use amos\podcast\Module;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class WidgetIconPodcastOwn extends WidgetIcon
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

        $this->setLabel(Module::tHtml('amospodcast', 'Created by me'));
        $this->setDescription(Module::t('amospodcast', 'Created by me'));


        $this->setIconFramework('am');
        $this->setIcon('surround-sound');


        $this->setUrl(['/podcast/podcast/own']);
        $this->setCode('PODCAST_MODULE');
        $this->setModuleName('podcast');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
    }



    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     *
     * @return type
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => $this->getWidgetsIcon()]
        );
    }


}
