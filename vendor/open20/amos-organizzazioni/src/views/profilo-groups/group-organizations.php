<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo-groups
 * @category   CategoryName
 */

use open20\amos\organizzazioni\widgets\GroupOrganizationsWidget;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\organizzazioni\models\Profilo $model
 * @var bool $isUpdate
 */

$widgetConf = [
    'model' => $model,
    'isUpdate' => (isset($isUpdate) ? $isUpdate : false)
];
?>
<?= GroupOrganizationsWidget::widget($widgetConf); ?>
