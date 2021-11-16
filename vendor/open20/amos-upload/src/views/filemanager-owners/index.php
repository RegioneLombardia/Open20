<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\upload
 * @category   CategoryName
 */

use yii\helpers\Html;
use backend\components\views\AmosGridView;
use yii\widgets\Pjax;

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\upload\models\FilemanagerOwnersSearch $searchModel
*/

$this->title = Yii::t('amosupload', 'Filemanager Owners');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="filemanager-owners-index">
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <p>
        <?php /* echo         Html::a(Yii::t('amosupload', 'Nuovo {modelClass}', [
    'modelClass' => 'Filemanager Owners',
])        , ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

            <?php Pjax::begin(); echo AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

                    'mediafile_id',
            'owner_id',
            'owner',
            'owner_attribute',

        [
        'class' => 'backend\components\views\grid\ActionColumn',
        ],
        ],
        ]); Pjax::end(); ?>
    
</div>
