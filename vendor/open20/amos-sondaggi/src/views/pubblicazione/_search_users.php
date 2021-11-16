<?php

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiUsersInvitationMm;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\sondaggi\models\search\SondaggiDomandeSearch $searchModel
 */

 $sondaggio = Sondaggi::findOne($idSondaggio);
 $session_id = 'null';
 $session = $sondaggio->getSondaggiRisposteSessionisByEntity()->one();
 if (!empty($session))
  $session_id = $session->id;
 $state = SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA;

  $assignDialog = AmosSondaggi::t('amossondaggi', '#assign_compiler_confirm', [
    'firstName' => $model->user->userProfile->nome,
    'lastName' => $model->user->userProfile->cognome
  ]);

  $removeDialog = AmosSondaggi::t('amossondaggi', '#remove_compiler_confirm', [
    'firstName' => $model->user->userProfile->nome,
    'lastName' => $model->user->userProfile->cognome
  ]);

 $js = <<<JS
  $('.assign-compiler').click(function(e){
      e.preventDefault();
      var to_id = $(this).data('to_id');
      var user_id = $(this).data('user_id');
      var sondaggio_id = $(this).data('sondaggio_id');
      var button = this;
      krajeeDialog.confirm("{$assignDialog}", function (result) {
      if (result) { // ok button was pressed
          var session_id = {$session_id};
          var state = "{$state}";
          if (session_id)
            $.ajax({
              url:"/sondaggi/ajax/change-status-session?id="+session_id+"&new_state="+state+"&modelObj=open20\\\\amos\\\\sondaggi\\\\models\\\\SondaggiRisposteSessioni",
              type: "GET",
              data: {},
              success:function(result){
                $.ajax({
                  url: '/sondaggi/pubblicazione/assign-compiler?to_id='+to_id+'&user_id='+user_id+'&sondaggio_id='+sondaggio_id,
                  type: 'get',
                  success: function () {
                      $('#modal_users_list').modal('hide');
                      $('#modal_users_content').html('');
                      $.pjax.reload({container: '#pubblicazione-view', timeout: 5000});
                  }
                });
              },
              error: function(richiesta,stato,errori){
              }
            });
          else
            $.ajax({
              url: '/sondaggi/pubblicazione/assign-compiler?to_id='+to_id+'&user_id='+user_id+'&sondaggio_id='+sondaggio_id,
              type: 'get',
              success: function () {
                  $('#modal_users_list').modal('hide');
                  $('#modal_users_content').html('');
                  $.pjax.reload({container: '#pubblicazione-view', timeout: 5000});
              }
            });
      } else { // confirmation was cancelled
          return;
      }
    });

  });

  $('.remove-compiler').click(function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var button = this;
    krajeeDialog.confirm("{$removeDialog}", function (result) {
      if (result) { // ok button was pressed
        $.ajax({
            url: '/sondaggi/pubblicazione/remove-compiler?id='+id,
            type: 'get',
            success: function () {
                $(button).attr('style','display:none !important');
                $(button).siblings('.assign-compiler').attr('style','');
            }
        });
      } else {
        return;
      }
    });
});


JS;
$this->registerJs($js);

    if (AmosSondaggi::instance()->enableCompilationWorkflow) {
      $message = AmosSondaggi::t('amossondaggi', '#assign_compiler_warning');
      echo '<div class="container-messages container"><div id="w1" class="alert-warning alert fade in" role="alert">'.$message.'</div></div>';
    }

    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                // ['class' => 'yii\grid\SerialColumn'],
                // 'id',
                'user.userProfile.nome:ntext',
                'user.userProfile.cognome:ntext',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{enable}',
                    'buttons' => [
                        'enable' => function ($url, $model) use ($idSondaggio ){
                            $isEnabled = SondaggiUsersInvitationMm::find()->andWhere(['sondaggi_id' => $idSondaggio,'to_id' => $model->profilo_id, 'user_id' => $model->user_id])->one();

                            if (\Yii::$app->getUser()->can('RESPONSABILE_ENTE')) {
                                return Html::a(AmosIcons::show('minus-circle'), '#', [
                                    'title' => AmosSondaggi::t('amossondaggi', '#remove_compiler'),
                                    'class' => 'btn btn-danger remove-compiler',
                                    'style' => $isEnabled ? '' : 'display:none !important',
                                    'data' => [
                                        'id' => $isEnabled->id
                                    ]
                                ]).Html::a(AmosIcons::show('check-circle'), '#', [
                                    'title' => AmosSondaggi::t('amossondaggi', '#assign_compiler'),
                                    'class' => 'btn btn-success assign-compiler',
                                    'style' => $isEnabled ? 'display:none !important' : '',
                                    'data' => [
                                        'to_id' => $model->profilo_id,
                                        'user_id' => $model->user_id,
                                        'sondaggio_id' => $idSondaggio
                                    ]
                                ]);
                            } else {
                                return '';
                            }
                        }
                    ]
                ],
            ],
        ]
    ]);
    ?>
