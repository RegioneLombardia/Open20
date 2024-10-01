<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\documenti\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\documenti\AmosDocumenti;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconDocumentiCategorie
 * @package open20\amos\documenti\widgets\icons
 */
class WidgetIconDocumentiCategorie extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
        ];

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', '#documenti_widget_label_categories'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#documenti_widget_description_categories'));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti/documenti-categorie/index']);
        $this->setCode('DOCUMENTI_CATEGORIE');
        $this->setModuleName('documenti');
        $this->setNamespace(__CLASS__);

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $paramsClassSpan = [];
        }

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
}