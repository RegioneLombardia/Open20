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
use open20\amos\dashboard\models\AmosUserDashboards;
use open20\amos\documenti\AmosDocumenti;
//use open20\amos\documenti\models\Documenti;
//use open20\amos\documenti\models\search\DocumentiSearch;
use open20\amos\core\record\Record;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Application as Web;

/**
 * Class WidgetIconDocumentiDashboard
 * @package open20\amos\documenti\widgets\icons
 */
class WidgetIconDocumentiDashboard extends WidgetIcon
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

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', '#documenti_widget_label_dashboard'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#documenti_widget_description_dashboard'));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti/documenti/all-documents']);
        $this->setCode('DOCUMENTI_MODULE');
        $this->setModuleName('documenti-dashboard');
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
        if ($this->disableBulletCounters == false) {
            $this->setBulletCount(Record::getStaticBullet(Record::BULLET_TYPE_ALL, false, 'documenti', false, true));
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
                parent::getOptions(), ['children' => $this->getWidgetsIcon()]
        );
    }

    /**
     *  TEMPORANEA
     * 
     * @return type
     */
    public function getWidgetsIcon()
    {
        $widgets = [];

        $WidgetIconDocumentiCategorie = new WidgetIconDocumentiCategorie();
        if ($WidgetIconDocumentiCategorie->isVisible()) {
            $widgets[] = $WidgetIconDocumentiCategorie->getOptions();
        }

        $WidgetIconDocumentiCreatedBy = new WidgetIconDocumentiCreatedBy();
        if ($WidgetIconDocumentiCreatedBy->isVisible()) {
            $widgets[] = $WidgetIconDocumentiCreatedBy->getOptions();
        }

        return $widgets;
    }
}