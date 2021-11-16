<?php
use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;
use open20\design\utility\DesignIcon;
?>
<?php
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;

/*
if (!Yii::$app->getRequest()->getCookies()->has('dl_semplification_modal_cookie')) {
    $js = <<<JS
    $('#modal-dl-semplification').modal('show');
    $('#modal-dl-semplification-dont-show-again-link').on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: '/preferenceuser/preference/set-dl-semplification-modal-cookie',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                $('#modal-dl-semplification').modal('hide');
            }
        });
    });
JS;
    $this->registerJs($js);
}
*/

$btnLabel = AmosAdmin::t('amosadmin', '#dl_semplification_modal_btn_label');

//if (isset($_GET['cod_tematica']) && !empty($_GET['cod_tematica'])) {
//    Yii::$app->session->set('__cod_tematica', $_GET['cod_tematica'] );
//}

//yii\bootstrap\ActiveForm $form

//$form = \yii\bootstrap4\ActiveForm::begin(['id' => 'login-form']);
?>

  <div class="modal alert-modal" tabindex="-1" role="dialog" id="modal-dl-semplification">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <?= DesignIcon::show('it-info-circle', DesignIcon::ICON_BI) ?>
          <h5 class="modal-title"><?= AmosAdmin::t('amosadmin', '#dl_semplification_modal_header') ?>
          </h5>
        </div>
        <div class="modal-body">
          <?= Html::tag('p', AmosAdmin::t('amosadmin', '#dl_semplification_modal_text')) ?>
        </div>
        <div class="modal-footer">
        <div>
        <button id="modal-dl-semplification-dont-show-again-link" class="btn btn-link" title="<?=  AmosAdmin::t('amosadmin', '#dl_semplification_modal_dont_show_again') ?>" target="_blank" type="button"><?=  AmosAdmin::t('amosadmin', '#dl_semplification_modal_dont_show_again') ?></button>  <button class="btn btn-primary" type="button" data-dismiss="modal" title="<?=  AmosAdmin::t('amosadmin', 'Ok grazie') ?>"><?= $btnLabel ?></button>
        </div>
        </div>
      </div>
    </div>
  </div>

<?php
//\yii\bootstrap4\ActiveForm::end();
?>
