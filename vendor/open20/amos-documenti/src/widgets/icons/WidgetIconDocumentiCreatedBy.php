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
 * Class WidgetIconDocumentiCreatedBy
 * @package open20\amos\documenti\widgets\icons
 */
class WidgetIconDocumentiCreatedBy extends WidgetIcon
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

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', '#documenti_widget_label_created_by'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#documenti_widget_description_created_by'));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti/documenti/own-documents']);
        $this->setCode('DOCUMENTI_CREATEDBY');
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
                $options = parent::getOptions(), ['children' => []]
        );
    }
}