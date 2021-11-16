<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

use open20\amos\emailmanager\AmosEmail;
use open20\amos\core\views\DataProviderView;
use open20\amos\core\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\EmailTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = AmosEmail::t('amosemail', 'Email Templates');
$this->params['breadcrumbs'][] = ['label' => AmosEmail::t('amosemail', 'Email Manager'), 'url' => ['/email']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-template-index">
    <?php echo $this->render('_search', ['model' => $model]); ?>
    <?php echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                'name',
                'subject',
                'heading',
                [
                    'label' => AmosEmail::t('amosemail', 'Message'),
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->message;
                    },
                ],
                //'message:ntext',
                // 'created_at',
                // 'updated_at',

                ['class' => 'open20\amos\core\views\grid\ActionColumn',],
            ],
        ],

    ]); ?>
</div>
