<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;
use open20\amos\translation\AmosTranslation;
use open20\amos\translation\models\TranslationConf;
use open20\amos\core\icons\AmosIcons;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\modules\registry\models\search\ProfessionalProfilesSearch $model
 */
$this->title = AmosTranslation::t('amostranslation', 'Translate contents');
$this->params['breadcrumbs'][] = ['label' => AmosTranslation::t('amostranslation', 'Translate manager'), 'url' => ['/translation']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="professional-profiles-index">
    <?php echo $this->render('_filter', ['model' => $model]); ?>

    <p>
        <?php /* echo         Html::a('Nuovo Professional Profiles'        , ['create'], ['class' => 'btn btn-amministration-primary']) */ ?>
    </p>

    <?php     
    echo open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'plugin',
                'label' => AmosTranslation::t('amostranslation', 'Plugin'),
                'value' => function ($model){
                    return ucfirst($model['plugin']);
                },
              'group' => true,
            ],
            [
                'attribute' => 'id_translate', 
                'label' => AmosTranslation::t('amostranslation', 'Models'),
                'value' => function ($model) {
                    return Inflector::camel2words(StringHelper::basename($model['namespace']));
                }
            ],         
            [
                'attribute' => 'language_id',
                'label' => AmosTranslation::t('amostranslation', 'Language'),      
                'value' => function($model){                  
                    return strtoupper($model['language_id']) . ' (' . $model['name'] . ')';
                }
            ],
                    [
            'attribute' => 'progress_bar',
            'label' => AmosTranslation::t('amostranslation', 'Translation progress'),
            'format' => 'raw',
            'value' => function($model) {
                $conf = new TranslationConf();
                $bars = $conf->getProgress($model['namespace'], $model['language_id']);
                return \yii\bootstrap\Progress::widget($bars);
            }
        ],
               [
            'class' => \open20\amos\core\views\grid\ActionColumn::className(),
            'template' => '{custom}',
            'buttons' => [
                'custom' => function ($url, $model) {         
                    $url = yii\helpers\Url::current();
                    $newModel = \Yii::$app->getModule('translation')->modelNs . "\\" . StringHelper::basename($model['namespace']) . "Translation";
                    $urlDestinazione = \Yii::$app->urlManager->createUrl(['/translation/default/records', 'namespace' => $model['namespace'] , 'lang' => $model['language_id'], 'url' => $url]);
                    if(\Yii::$app->getUser()->can('TRANSLATOR', ['model' => (new $newModel), 'language' => $model['language_id']]) && ((!empty(\Yii::$app->getModule('translation')->defaultLanguage) && $model['language_id'] != \Yii::$app->getModule('translation')->defaultLanguage) || (!isset(\Yii::$app->getModule('translation')->defaultLanguage)))){
                    return \yii\helpers\Html::a(AmosIcons::show('square-right', ['class' => 'btn btn-tool-secondary']), $urlDestinazione, [
                        'title' => AmosTranslation::t('app', 'Go to records to translate'),
                        'model' => $model
                    ]);
                    } else {
                        return '';
                    }
                },

            ]
        ]
        ],
    ]);
    ?>

</div>
