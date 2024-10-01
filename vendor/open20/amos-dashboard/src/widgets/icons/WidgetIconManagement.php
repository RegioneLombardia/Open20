<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard\widgets
 * @category   CategoryName
 */

namespace open20\amos\dashboard\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\AmosDashboard;

use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconEenDashboard
 *
 * @package open20\amos\een\widgets\icons
 */
class WidgetIconManagement extends WidgetIcon {

  /**
   * @inheritdoc
   */
  public function init() {
    parent::init();

    $paramsClassSpan = [
      'bk-backgroundIcon',
      'color-primary'
    ];

    $this->setLabel(AmosDashboard::tHtml('amosdashboard', 'Management'));
    $this->setDescription(AmosDashboard::t('amosdashboard', 'Plugin Management'));

    if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
      $this->setIconFramework(AmosIcons::IC);
      $this->setIcon('gestione');
      $paramsClassSpan = [];
    } else {
      $this->setIcon('wrench');
    }

    $this->enableDashboardModal();
    $this->setUrl(['']);
    $this->setCode('MANAGEMENT');
    $this->setModuleName('admin');
    $this->setNamespace(__CLASS__);

    $this->setClassSpan(
      ArrayHelper::merge(
        $this->getClassSpan(),
        $paramsClassSpan
      )
    );
  }

}
