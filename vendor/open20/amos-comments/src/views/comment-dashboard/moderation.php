<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-comments/src/views
 */

use open20\amos\comments\AmosComments;
use open20\amos\comments\utility\CommentsUtility;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\views\DataProviderView;
use kartik\widgets\SwitchInput;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var yii\data\ActiveDataProvider $commentsDataProvider
 * @var string $currentView
 */

$this->title = 'Comment Dashboard';
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/comments']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['forceBreadcrumbs'][] = ['label' => AmosComments::t('amoscomments', 'Moderazione commenti')];

\open20\amos\layout\assets\LoadingSpinnerAsset::register($this);

$models = $dataProvider->getModels();
$model = $models[0];


$js = <<<JS
    // Hide change view div! Comment this if you add additional buttons or filter configs!
    $('.container-change-view').hide();
JS;

$this->registerJs($js);

$jsSwitchInputChange = <<<JS
    function switchInputChange(e, state) {
        var id = '{$model->id}';
        var attribute = e.target.id;
        var value = e.target.checked;
        var modal = $('.modal-comments-moderation');
        var confirmBtn = $('.modal-comments-moderation #modalConfirmBtn');
        var switchInput = $(this);

        if (state === false) {
            switchInput.bootstrapSwitch('state', !state, true);
            modal.modal('show');
            confirmBtn.on('click', function (e) {
                confirmBtn.prop('disabled', true);
                updateAjax();
            });
        } else {
            updateAjax();
        }
        
        function updateAjax() {
            $.ajax({
                    url: '/comments/comment-dashboard/update-ajax',
                    method: 'GET',
                    data: {
                        id: id,
                        attribute: attribute,
                        value: value
                    },
                    success: function(response) {
                        modal.modal('hide');
                        confirmBtn.prop('disabled', false);
                        switchInput.bootstrapSwitch('state', state, true);
                        if (value == false) {
                            location.reload();
                        }
                    },
                    error: function(error) {
                        alert('Si è verificato un errore durante il salvataggio.');
                        console.log(error);
                    }
                });
        }
    }
JS;

?>

<div class="comment-dashboard-index">

    <?php
    if (Yii::$app->user->can('COMMENTDASHBOARD_ADMINISTRATOR')) {
        $form = ActiveForm::begin([
            'options' => [
                'id' => 'comment-dashboard_' . ((isset($fid)) ? $fid : 0),
                'data-fid' => (isset($fid)) ? $fid : 0,
                'data-field' => ((isset($dataField)) ? $dataField : ''),
                'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
                'class' => ((isset($class)) ? $class : '')
            ]
        ]);
        ?>

        <div class="row">
            <div class="col-xs-12">
                <h4 class="text-uppercase"><?= AmosComments::t('amoscomments', 'Amministra moderazione commenti') ?></h4>
            </div>
        </div>

        <div class="row m-b-10">
            <div class="col-xs-12">
                <div class="subcommunity-section" style="padding:24px 24px 0 24px">
                    <div class="row">
                        <div class="col-xs-10 form-group m-b-0">
                            <?= Html::label(
                                AmosComments::t('amoscomments', 'La funzionalità di moderazione sui commenti della piattaforma è attiva?'),
                                'moderation_platform',
                                ['class' => 'control-label m-t-15']
                            ); ?>
                            <span class="mdi mdi-information-outline" data-toggle="tooltip" title="<?= AmosComments::t('amoscomments', 'Plugin coinvolti:<br>{plugins}.', ['plugins' => CommentsUtility::getModerationInvolvedPlugins()]) ?>"></span>
                        </div>
                        <div class="col-xs-2">
                            <?= $form->field($model, 'moderation_platform')->widget(SwitchInput::classname(), [
                                'pluginOptions' => [
                                    'onColor' => 'success',
                                    'onText' => BaseAmosModule::t('amoscore', 'Sì'),
                                    'offText' => BaseAmosModule::t('amoscore', 'No'),
                                ],
                                'options' => ['id' => 'moderation_platform'],
                                'pluginEvents' => [
                                    "switchChange.bootstrapSwitch" => new \yii\web\JsExpression($jsSwitchInputChange),
                                ]
                            ])->label(false); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="subcommunity-section" style="padding:24px 24px 0 24px">
                    <div class="row">
                        <div class="col-xs-10 form-group m-b-0">
                            <?= Html::label(
                                AmosComments::t('amoscomments', 'Le Community supportano la moderazione dei commenti?'),
                                'moderation_communities',
                                ['class' => 'control-label m-t-15']
                            ); ?>
                            <span class="mdi mdi-information-outline" data-toggle="tooltip" title="<?= AmosComments::t('amoscomments', 'Se attivato, consente al Community Manager di attivare o disattivare la funzionalità all\'interno delle Community') ?>"></span>
                        </div>
                        <div class="col-xs-2">
                            <?= $form->field($model, 'moderation_communities')->widget(SwitchInput::classname(), [
                                'pluginOptions' => [
                                    'onColor' => 'success',
                                    'onText' => BaseAmosModule::t('amoscore', 'Sì'),
                                    'offText' => BaseAmosModule::t('amoscore', 'No'),
                                ],
                                'options' => ['id' => 'moderation_communities'],
                                'pluginEvents' => [
                                    "switchChange.bootstrapSwitch" => new \yii\web\JsExpression($jsSwitchInputChange),
                                ]
                            ])->label(false); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <hr class="m-b-15 m-t-20" style="border-top: 1px solid #CCC">
    <?php } ?>

    <h4 class="text-uppercase"><?= AmosComments::t('amoscomments', 'Commenti in attesa di validazione') ?></h4>

    <div class="grid-view" style="margin-left: 5px">
        <?= $this->render('@vendor/open20/amos-comments/src/views/comment-moderation/parts/gridView', [
            'dataProvider' => $commentsDataProvider,
            'currentView' => $currentView
        ]); ?>
    </div>

    <hr class="m-b-15 m-t-20" style="border-top: 1px solid #CCC">

    <h4 class="text-uppercase"><?= AmosComments::t('amoscomments', 'Risposte in attesa di validazione') ?></h4>

    <div class="replies-grid-view" style="margin-left: 5px">
        <?= $this->render('@vendor/open20/amos-comments/src/views/comment-moderation/parts/repliesGridView', [
            'dataProvider' => $commentFromRepliesDataProvider,
            'currentView' => $currentView
        ]); ?>
    </div>

</div>

<!-- Modal -->
<?= $this->render('parts/_modal'); ?>