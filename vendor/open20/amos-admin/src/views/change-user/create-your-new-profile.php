<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\change-user
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;

/**
 * @var yii\web\View $this
 * @var open20\amos\admin\models\ChangeUserCreateForm $model
 */

$this->title = AmosAdmin::t('amosadmin', '#change_user_create_new_user_profile');
$this->params['breadcrumbs'][] = $this->title;

$adminModuleName = AmosAdmin::getModuleName();
$emailElementId = Html::getInputId($model, 'email');

$js = <<<JS
function checkEmail(email) {
$.get("/$adminModuleName/change-user/check-email-ajax", {email: email})
    .done(function(data) {
        data = $.parseJSON(data);
        var checkMailElement = $('#check-email');
        if (data.success !== undefined) {
            checkMailElement.html(data.message);
            if (data.message == '') {
                checkMailElement.hide();
                $('#submit_create_new_profile').prop('disabled', '');
            } else {
                checkMailElement.show();
                $('#submit_create_new_profile').prop('disabled', 'disabled');
            }
        } else {
            checkMailElement.html('');
            checkMailElement.show();
        }
    });
}

$('#$emailElementId').change(function(e) {
    e.preventDefault();
    checkEmail($(this).val());
    return true;
});

JS;
$this->registerJs($js);

?>

<?php
$form = ActiveForm::begin([
    'id' => 'change-user-create-profile-form',
]);
?>
<div class="change-user-create-profile-form">
    <?= $this->render('_introduction', ['form' => $form, 'model' => $model]); ?>

    <div class="row">
        <?= $this->render('_fields_begin', ['form' => $form, 'model' => $model]); ?>
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => 255, 'readonly' => false]) ?>
        </div>
        <?= $this->render('_fields_end', ['form' => $form, 'model' => $model]); ?>
    </div>

    <div class="row">
        <div class="col-xs-12 alert alert-warning" style="display: none;" id="check-email"></div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?= Html::tag('div',
                Html::submitButton(AmosAdmin::t('amosadmin', '#change_user_create_new_user_profile_submit_btn_label'), ['class' => 'btn btn-primary btn-administration-primary pull-right', 'title' => AmosAdmin::t('amosadmin', '#change_user_create_new_user_profile_submit_btn_label'), 'id' => 'submit_create_new_profile']) .
                Html::a(AmosAdmin::t('amosadmin', '#change_user_create_new_user_profile_goback_btn_label'), ['/' . AmosAdmin::getModuleName() . '/change-user/my-users-list'], ['class' => 'btn btn-secondary pull-left', 'title' => AmosAdmin::t('amosadmin', '#change_user_create_new_user_profile_goback_btn_label')])
            ); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
