<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace backend\modules\userimporter\widgets\icons;

use backend\modules\userimporter\Module;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetIcon;
use Yii;
use yii\helpers\ArrayHelper;


class WidgetIconImporter extends WidgetIcon
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

        $this->setLabel(Module::tHtml('amosuserimporter', '#user_import'));
        $this->setDescription(Module::t('amosuserimporter',
                '#user_import_desc'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine']
            == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::DASH);
            $this->setIcon('address-card');
            $paramsClassSpan = [];
        }
        $this->setUrl(['/userimporter/user-import-task']);
        $this->setCode('ALL-USER-IMPORT-TASK');
        $this->setModuleName('userimporter');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(), $paramsClassSpan
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
                parent::getOptions(), ['children' => []]
        );
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return true;
    }
}