<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\AmosGridView;
use open20\amos\cwh\AmosCwh;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\cwh\models\search\CwhAuthAssignmentSearch $searchModel
 */

$this->title = AmosCwh::t('amoscwh', 'Cwh Domini');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cwh-config-index">

    <?php

    Pjax::begin();
    echo AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'user_id' => [
                'label' => AmosCwh::t('amoscwh','User id'),
                'attribute' => 'user_id',
            ],
//            'username'=>[
//                'label' => 'Username',
//                'attribute' => 'user.username',
//            ],
            'name' => [
                'label' => 'Name',
                'attribute' => 'user.profile.nomeCognome',
            ],
//            [
//                'attribute' => 'item_name',
////                'format' => 'raw',
////                //'headerOptions' => ['style' => 'width:10%'],
////                'contentOptions'=>['style'=>'max-width: 20%;']
//            ],
            'item_name' =>[
                'label' => AmosCwh::t('amoscwh', 'Permission'),
                'attribute' => 'item_name',
                'format' => 'html',
                'contentOptions'=>['style'=>'max-width: 35px; overflow:hidden; word-break: break-word'],
            ],
            'authItemDescription' =>[
                'attribute' => 'authItemDescription',
                'format' => 'html',
                'contentOptions'=>['style'=>'max-width: 50px; overflow:hidden; word-break: break-word'],
            ],
            'cwh_nodi_id' =>[
                'label' => AmosCwh::t('amoscwh','Domain id'),
                'attribute' => 'cwh_nodi_id',
                'format' => 'html',
                'contentOptions'=>['style'=>'min-width:25px;max-width: 30px'],
            ],
            'domain' =>[
                'label' => AmosCwh::t('amoscwh','Domain name'),
                'attribute' => 'cwhNodi.text',
            ],
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'template' => '{update} {delete}', //{view}
            ],
        ],
    ]);
    Pjax::end();


    ?>

</div>
