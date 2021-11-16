<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\campains\widgets\icons
 * @category   CategoryName
 */

namespace backend\modules\landings\widgets\icons;

use backend\modules\campains\Module;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetCampains
 * @package backend\modules\campains\widgets\icons
 */
class WidgetIconPreferenceLandings extends WidgetIcon
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

        $this->setLabel(Module::t('campains', 'Landing pages'));
        $this->setDescription(Module::t('campains', 'Landing'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::AM);
            $this->setIcon('view-web');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('feed');
        }

        $this->setUrl(['/landings/preference-landing/index']);
        $this->setCode('LANDINGS');
        $this->setModuleName('landings');
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
