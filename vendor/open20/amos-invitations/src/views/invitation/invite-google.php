<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations\views\invitation
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\core\views\AmosGridView;
use open20\amos\invitations\Module;
use open20\amos\invitations\assets\InvitationsAsset;
use yii\widgets\Pjax;

InvitationsAsset::register($this);
/**
 * @var yii\web\View $this
 * @var \open20\amos\invitations\models\GoogleInvitationForm $invitationForm
 * @var open20\amos\invitations\models\Invitation $invitation
 * @var open20\amos\invitations\models\InvitationUser $invitationUser
 * @var yii\widgets\ActiveForm $form
 */

$this->title = Module::t('amosinvitations', '#send_invitation_google');
$this->params['breadcrumbs'][] = $this->title;

$selection = implode(",", $invitationForm->selection);

$js = <<<JS
var selectionHidden = $('#googleinvitationform-selection');
var selection = [];
var selectedContacts = $('#selected-contacts');

  function setChecked() {
    var selected = [];
    var rows = selectedContacts.find('tr');
    if(rows.length){
        rows.each(function () {
            var email = $(this).attr('data-key');
            selected.push(email);
        });
        if(selected.length){
            $('#google-contacts tr').each(function () {
                if($(this).attr('data-key')){
                    var input = $(this).find('input');
                    if(selected.indexOf(input.val()) >= 0){
                        input.prop('checked', true);
                        if(!$(this).hasClass('success')){
                            $(this).addClass('success');
                        }
                    }else{
                        if(input.prop('checked') == true){
                            input.removeProp('checked');
                           $(this).removeClass('success');
                        }
                    }
                }
            });
            selection = selected;
            selectionHidden.val(selection);
        }
    }
   }
        
    function getChecked(){
      jQuery('input[name="GoogleInvitationForm[selection][]"]:checked').each(function() {
          selection.push(jQuery(this).val()); 
          selectionHidden.val(selection);
      });
    }

    $("#search-google").on('keyup', function(e) {
      if(e.which == 13) {
        $(".search-button-google").click();
      }
    });
    $(".reset-search-google").click(function() {
      $("#search-google").val('');
       $(".search-button-google").click(); 
    });
    $(".search-button-google").click(function (event) {
        event.preventDefault();
        var data = {
           search: $("#search-google").val()
        };
        $('.loading').show();
        $.pjax.reload({
            container: '#pjax-google-container',
            method: 'post',
            data: data
        }).done(function () {
            $('.loading').hide();
        }); //Reload GridView
    });

$('#google-invitation-form').on('change', 'input[type="checkbox"]', function() {
    var email = $(this).val();
    var tr = $(this).closest('tr');
  if($(this).is(':checked')){
      if(!selection.length || !(selection.indexOf($(this).val()) >= 0) ){
        selection.push(email);
        var new_tr = tr.clone();
        ((new_tr.find('td'))[0]).remove();
        new_tr.removeClass('success');
        selectedContacts.find('tbody').append(new_tr);
    }
  }else{
      selection.splice(selection.indexOf(email),1);
      var trToRemove = selectedContacts.find('tr[data-key="'+email+'"]');
      if(trToRemove.length){
        trToRemove.remove();
      }
      tr.removeClass('success');
  }
  selectionHidden.val(selection);
});
$('.invite-google').on('click', '#google-contacts tr td', function(e) {
    if(!$(this).hasClass('kv-row-select')){
    var input = $(this).closest('tr').find('input[type="checkbox"]');
      if(input.length){
          input.click();
      }
  }
});

$('.invite-google').on('pjax:end', function (data, status, xhr, options) {
     setChecked();
 });

getChecked();
setChecked();

JS;

$this->registerJs($js);
?>


<div class="invite-google col-xs-12 nop">
    <div id="loader" class="loading hidden"></div>
    <?php $form = ActiveForm::begin(['id' => 'google-invitation-form', 'action' => 'invite-google']); ?>

    <div class="container-change-view">
        <div class="btn-tools-container">
            <div class="tools-right">
                    <span class="btn btn-tools-primary show-hide-element am am-search"
                          data-toggle-element="form-search"></span>
            </div>
        </div>
    </div>

    <div class="search-users-index">
        <div id="search-form-contacts" data-toggle-element="form-search" class="element-to-toggle">
            <div class="col-xs-12">
                <h3 class="col-sm-12 col-lg-3"><?= $invitationForm->getAttributeLabel('search') ?></h3>
                <div class="col-sm-6 col-lg-7">
                    <?= $form->field($invitationForm, 'search')->textInput(['id' => 'search-google'])->label(false) ?>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <?= Html::submitButton(Module::t('amosinvitations', 'Search'),
                        ['class' => 'btn btn-navigation-primary search-button-google']) ?>
                    <?= Html::button(Module::t('amosinvitations', 'Cancel'),
                        ['class' => 'btn btn-secondary reset-search-google']) ?>
                </div>
            </div>
        </div>
    </div>


    <?php Pjax::begin([
        'id' => 'pjax-google-container',
        'options' => ['class' => 'col-lg-7 nop'],
        'timeout' => 2000,
        'clientOptions' => ['data-pjax-container' => 'google-contacts']
    ]); ?>
    <?= AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'google-contacts',
        'columns' => [
            [
                'class' => '\kartik\grid\CheckboxColumn',
                'name' => 'GoogleInvitationForm[selection][]',
                'header' => '',
                'rowSelectedClass' => \kartik\grid\GridView::TYPE_SUCCESS,
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return [
                        'value' => $model->email,
                        'checked' => $model->selected,
                    ];
                }
            ],
            'photoUrl' => [
                'attribute' => 'photoUrl',
                'format' => 'raw',
                'value' => function ($model) {
                    $url = '/img/defaultProfilo.png';
                    if (!empty($model->photoUrl)) {
                        $url = $model->photoUrl;
                    }
                    return Html::tag('div', Html::img($url, ['class' => 'square-img']),
                        ['class' => 'container-round-img-sm']);
                }
            ],
            'displayName',
            'email',
            'sentInvitations' => [
                'attribute' => 'sentInvitations',
                'value' => function ($model) {
                    return ($model->sentInvitations) ? $model->sentInvitations : '';
                }
            ]
        ],
    ]) ?>
    <?php Pjax::end() ?>
    <div class="col-lg-5">
        <div class="col-xs-12 selected-users">
            <?= $form->field($invitationForm, 'selection')->hiddenInput([]) ?>

            <?= AmosGridView::widget([
                'dataProvider' => $dataProviderSelected,
                'id' => 'selected-contacts',
                'columns' => [
                    'photoUrl' => [
                        'attribute' => 'photoUrl',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $url = '/img/defaultProfilo.png';
                            if (!empty($model->photoUrl)) {
                                $url = $model->photoUrl;
                            }
                            return Html::tag('div', Html::img($url, ['class' => 'square-img']),
                                ['class' => 'container-round-img-sm']);
                        }
                    ],
                    'displayName',
                    'email',
                    'sentInvitations' => [
                        'attribute' => 'sentInvitations',
                        'value' => function ($model) {
                            return ($model->sentInvitations) ? $model->sentInvitations : '';
                        }
                    ]
                ],
                'emptyText' => ''
            ]) ?>
        </div>
    </div>

    <div class="col-xs-12 nop">
        <?= $form->field($invitationForm, 'message')->widget(\open20\amos\core\forms\TextEditorWidget::className(), [
            'clientOptions' => [
                'placeholder' => Module::t('amosinvitations', '#message_placeholder'),
                'lang' => substr(Yii::$app->language, 0, 2)
            ]
        ])->hint('max 2500 ' . Module::t('amosresults', '#chars')) ?>
    </div>

    <div class="col-xs-12 nop">
        <div class='bk-btnFormContainer'>
            <?= Html::submitButton(Module::t('amosinvitations', 'Send invitation'), [
                'class' => 'btn btn-navigation-primary',
                'id' => 'send-invitation',
            ]) ?>
            <?= Html::a(Module::t('amoscore', 'Annulla'), null,
                ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>