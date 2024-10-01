<?php

use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;


?>
<?php
/** @var $model \open20\amos\documenti\models\Documenti
 *  @var $widget \open20\amos\documenti\widgets\DocumentsFrontendWidget
 */
?>
<?php
$showPageSummary = $widget->showPageSummary;
echo \open20\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'showPageSummary' => $showPageSummary,
    'columns' => [
        [
            'label' => '',
            'value' => function($model) use ($view_item){
                return $this->render($view_item, ['model' => $model]);
            },
            'format' => 'raw',
        ]
    ]
]);

