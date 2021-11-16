<?php

use open20\amos\core\helpers\Html;
use open20\amos\news\AmosNews;
?>

<?php
/** @var $model \open20\amos\news\models\base\News
 * @var $view_item string
 */?>
<?php
echo \open20\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => '',
            'value' => function($model) use ($view_item){
                return $this->render($view_item, ['model' => $model]);
            },
            'format' => 'raw'
        ]
    ]
])
?>

