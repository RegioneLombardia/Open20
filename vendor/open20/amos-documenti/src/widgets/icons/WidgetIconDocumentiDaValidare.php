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
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\search\DocumentiSearch;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconDocumentiDaValidare
 * @package open20\amos\documenti\widgets\icons
 */
class WidgetIconDocumentiDaValidare extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {

        parent::init();

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', '#documenti_widget_label_to_validate'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#documenti_widget_description_to_validate'));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti/documenti/to-validate-documents']);
        $this->setCode('DOCUMENTI_VALIDATE');
        $this->setModuleName('documenti');
        $this->setNamespace(__CLASS__);

        $paramsClassSpan = [
            'bk-backgroundIcon',
        ];

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