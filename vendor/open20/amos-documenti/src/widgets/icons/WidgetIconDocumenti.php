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
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\search\DocumentiSearch;
use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\core\record\Record;

/**
 * Class WidgetIconDocumenti
 * @package open20\amos\documenti\widgets\icons
 */
class WidgetIconDocumenti extends WidgetIcon
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

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', '#documenti_widget_label_own_interest'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#documenti_widget_description_own_interest'));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti/documenti/own-interest-documents']);
        $this->setCode('DOCUMENTI');
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
        if ($this->disableBulletCounters == false) {
            $this->setBulletCount(Record::getStaticBullet(Record::BULLET_TYPE_OWN, false, 'documenti', false, true));
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
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getWidgetsIcon()
    {
        return AmosWidgets::find()
                ->andWhere(['child_of' => self::className()])
                ->all();
    }
}