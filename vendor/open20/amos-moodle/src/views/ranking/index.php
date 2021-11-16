<?php
use open20\amos\core\views\DataProviderView;
use open20\amos\moodle\AmosMoodle;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var yii\data\ArrayDataProvider $parametro
 */
$actionColumn = '{view}';


$this->title = AmosMoodle::t('amosmoodle', 'Classifica');
$this->params['breadcrumbs'][] = $this->title;
$dataProvider = $this->params['dataProvider'];
?>

<div class="moodle-topic-index">

    <?php // echo $this->render('_search', ['model' => $model]);    ?>

        <?php
        //pr($parametro);
        echo DataProviderView::widget([
            'dataProvider' => $dataProvider, // $parametro,
            //'filterModel' => $model,
            'currentView' => $currentView,
            'gridView' => [
                'columns' => [
                    // ['class' => 'yii\grid\SerialColumn'],
                    'position',
                    'name',
                    'points'
                ],
            ],
               
        ]);
    
    ?>

</div>
