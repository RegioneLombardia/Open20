<?php

/**
 * Art-ER Attrattività, ricerca e territorio dell’Emilia-Romagna
 * OPEN 2.0
 *
 * @package    backend\modules\aster_partnership_profiles\widget\icons
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\widgets\icons;


use open20\amos\organizzazioni\Module;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetIcon;
use yii\helpers\ArrayHelper;

class WidgetIconProfiloToValidate extends WidgetIcon
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

        $this->setLabel(Module::tHtml('amosorganizzazioni', 'Da pubblicare'));
        $this->setDescription(Module::t('amosorganizzazioni', 'Organizzazioni'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
             $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('organizzazioni');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('building-o');
        }

        $this->setUrl(['/organizzazioni/profilo/profilo-to-publish']);
        $this->setModuleName('organizzazioni');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
    }
}
