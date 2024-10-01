<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\newsletter
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use open20\amos\notificationmanager\AmosNotify;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\notificationmanager\models\search\NewsletterSearch $model
 * @var string $currentView
 */

$this->params['breadcrumbs'][] = $this->title;
$user = \open20\amos\core\user\User::findOne(\Yii::$app->user->id);
$email = null;
if ($user) {
    $email = $user->email;
}

$dontNotify = [];
$dontNotifyNewsCategories = [];
foreach ($dataProvider->models as $model) {
    $class = $model->class_name;
    $record_id = $model->content_id;
    $contentModel = $class::findOne($record_id);
    if ($contentModel) {
        $canNotifyDatePubl = \open20\amos\notificationmanager\utility\NotifyUtility::canNotifyContentPublishDate($contentModel);
        if (!$canNotifyDatePubl) {
            $dontNotify[] = $model->id;
        }
        if($contentModel instanceof \open20\amos\news\models\News){
            if(!$contentModel->newsCategorie->notify_category){
                $dontNotifyNewsCategories[] = $model->id;
                //passare alla colonna [verrà notificato]
            }
        }
    }

}
//echo $this->render('_search', ['model' => $modelSearch]);
?>
<div style="display:none">
    <pre>
        <?= $sql ?>
    </pre>
</div>

<h5><?= AmosNotify::t('amosnotify', "Simula invio del riepilogo di un utente a tua scelta") ?></h5>
<?php
$form = ActiveForm::begin([
    'action' => Yii::$app->controller->action->id,
    'method' => 'get',
    'options' => [
        'id' => 'news_form_' . $model->id,
        'enctype' => 'multipart/form-data',
    ]
]);
?>
<div class="row">
    <div class="col-md-4">
        <label><?= 'Utente' ?></label>
        <?php
        echo \kartik\select2\Select2::widget([
                'data' => [],
                'name' => 'user_ids',
                'options' => ['placeholder' => AmosNotify::t('amosnotify', 'Cerca ...')],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => \yii\helpers\Url::to(['/' . \open20\amos\admin\AmosAdmin::getModuleName() . '/user-profile-ajax/ajax-user-list']),
                        'dataType' => 'json',
                        'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                    ],
                ],
            ]
        );
        ?>
    </div>
    <div class="col-md-4">
        <label><?= 'email' ?></label>
        <?= \yii\helpers\Html::textInput('email', $email, ['class' => 'form-control', 'placeholder' => 'Email a cui inviare il riepilogo']) ?>
    </div>

    <div class="col-md-4">
        <?= \yii\helpers\Html::submitButton(AmosNotify::t('amosnotify', 'Simula invio'), [
            'class' => 'btn btn-secondary m-t-30'
        ]) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<br>
<h5><?= AmosNotify::t('amosnotify', 'Contenuti da notificare') ?></h5>
<div class="notification-index">
    <?= \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            'id',
            [
                'value' => function ($model) {
                    $class = $model->class_name;
                    $modelClassname = \open20\amos\core\models\ModelsClassname::find()->andWhere(['classname' => $class])->one();
                    if ($modelClassname) {
                        return $modelClassname->label;
                    } else {
                        return $class;
                    }
                },
                'label' => 'Contenuto'
            ],
            [
                'attribute' => 'notification_id',
                'value' => function ($model) {
                    $class = $model->class_name;
                    $record_id = $model->content_id;
                    $contentModel = $class::findOne($record_id);
                    if ($contentModel) {
                        $canNotifyDatePubl = \open20\amos\notificationmanager\utility\NotifyUtility::canNotifyContentPublishDate($contentModel);

                        if (method_exists($contentModel, 'getTitle')) {
                            return $contentModel->getTitle();
                        }
                    }
                    return '-';
                },
                'label' => 'Titolo'
            ],
            [
                'value' => function ($model) {

                    $modelClassname = \open20\amos\core\models\ModelsClassname::findOne($model->models_classname_id);
                    if ($modelClassname) {
                        $Class = $modelClassname->classname;
                        $network = $Class::findOne($model->record_id);
                        if ($network && method_exists($network, 'getTitle')) {
                            return $network->id . ' - ' . $network->getTitle();
                        }
                    }
                    return ' - ';
                },
                'label' => 'Dalla community'
            ],
            [
                'value' => function ( $model )use ($dontNotify, $dontNotifyNewsCategories) {
                    $errorMessage = '';
                    if(in_array($model->id,$dontNotify)){
                        $errorMessage.= "Non verrà notificato perchè la data di pubblicazione è successiva ad oggi<br>";
                    }
                    if(in_array($model->id,$dontNotifyNewsCategories)){
                        $errorMessage.= "Non verrà notificato perchè la categoria è non notificabile<br>";
                    }
                   if(!empty($errorMessage)){
                      return  \open20\amos\core\icons\AmosIcons::show('close-circle',[
                               'class' => 'text-danger',
                           'data-toggle' => 'tooltip',
                           'title' => $errorMessage
                       ]);
                   }else{
                       return \open20\amos\core\icons\AmosIcons::show('check-circle',[
                           'class' => 'text-success',
                           'data-toggle' => 'tooltip',
                           'title' => 'Verrà notificato correttamente'
                       ]);
                   }
                },
                'label' => '',
                'format' => 'raw'
            ],
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $class = $model->class_name;
                        $record_id = $model->content_id;
                        $contentModel = $class::findOne($record_id);
                        if ($contentModel) {
                            return Html::a(\open20\amos\core\icons\AmosIcons::show('file'), $contentModel->getFullViewUrl(), [
                                'class' => 'btn btn-tools-secondary',
                                'title' => 'Vedi contenuto'
                            ]);
                        }

                        return '';
                    },

                ]
            ]
        ]
    ]); ?>

    <?= Html::a('Indietro', ['/notify/notification-schedule/index'], ['class' => 'btn btn-secondary']) ?>
</div>
