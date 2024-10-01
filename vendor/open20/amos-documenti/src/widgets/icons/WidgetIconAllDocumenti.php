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

use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\search\DocumentiSearch;
use open20\amos\core\record\Record;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconAllDocumenti
 * @package open20\amos\documenti\widgets\icons
 */
class WidgetIconAllDocumenti extends WidgetIcon
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

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', '#documenti_widget_label_all'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#documenti_widget_description_all'));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti/documenti/all-documents']);
        $this->setCode('ALL-DOCUMENTI');
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
        
        // Read and reset counter from bullet_counters table, bacthed calculated!
        if ($this->disableBulletCounters == false ) {
            $this->setBulletCount(Record::getStaticBullet(Record::BULLET_TYPE_ALL, false, 'documenti', false, true));
        }
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        //aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge(
                parent::getOptions(), ['children' => []]
        );
    }
}