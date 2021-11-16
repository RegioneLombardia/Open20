<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

namespace open20\amos\discussioni\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\search\DiscussioniTopicSearch;

/**
 *
 * @deprecated
 * Class WidgetGraphicsDiscussioniInEvidenza
 * informational widget that lists threads in evidence
 * @package open20\amos\discussioni\widgets\graphics
 */
class WidgetGraphicsDiscussioniInEvidenza extends WidgetGraphic {

  /**
   * @inheritdoc
   */
  public function init() {
    parent::init();
    
    $this->setCode('ULTIME_DISCUSSIONI_GRAPHIC');
    $this->setLabel(AmosDiscussioni::tHtml('amosdiscussioni', 'Discussioni in evidenza'));
    $this->setDescription(AmosDiscussioni::t('amosdiscussioni', 'Elenca le ultime discussioni in evidenza'));
  }

  /**
   * Rendering of the view discussioni_in_evidenza
   *
   * @return string
   */
  public function getHtml() {
    $listaTopic = (new DiscussioniTopicSearch())->discussioniInEvidenza($_GET, AmosDiscussioni::MAX_LAST_DISCUSSION_ON_DASHBOARD);
    $viewPath = '@vendor/open20/amos-discussioni/src/widgets/graphics/views/';
    $viewToRender = $viewPath . 'ultime_discussioni';

    if (is_null(\Yii::$app->getModule('layout'))) {
      $viewToRender .= '_old';
    }

    return $this->render(
      $viewToRender,
      [
        'listaTopic' => $listaTopic,
        'widget' => $this,
        'toRefreshSectionId' => 'widgetGraphicStickyThreads'
      ]
    );
  }

}