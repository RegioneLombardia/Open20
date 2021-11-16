<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile\boxes
 * @category   CategoryName
 */
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\models\UserProfileAgeGroup;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 * @var string $idTabInsights
 */
/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

$js = "
$('#extended-presentation-link').click(function(event) {
    event.preventDefault();
    $('a[href=\"' + $(this).attr('href') + '\"]').tab('show');
});
";
$this->registerJs($js, View::POS_READY);
?>
<section>
    <div class="row">
        <?php if ($adminModule->confManager->isVisibleField('nome', ConfigurationManager::VIEW_TYPE_FORM)): ?>
            <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'nome')->textInput(['maxlength' => 255, 'readonly' => false]) ?>
            </div>
        <?php endif; ?>
        <?php if ($adminModule->confManager->isVisibleField('cognome', ConfigurationManager::VIEW_TYPE_FORM)): ?>
            <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'cognome')->textInput(['maxlength' => 255, 'readonly' => false]) ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    if (
        ($adminModule->confManager->isVisibleField('sesso', ConfigurationManager::VIEW_TYPE_FORM)) ||
        ($adminModule->confManager->isVisibleField('user_profile_age_group_id', ConfigurationManager::VIEW_TYPE_FORM))
    ):
        ?>
    <?php endif; ?>
    <div class="row">
        <?php if ($adminModule->confManager->isVisibleField('sesso', ConfigurationManager::VIEW_TYPE_FORM)): ?>
            <div class="col-lg-3 col-sm-6">
                <?=
                $form->field($model, 'sesso',
                    [
                    'template' => "{label}\n{hint}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
                ])->widget(Select::classname(),
                    [
                    'options' => ['placeholder' => AmosAdmin::t('amosadmin', 'Select/Choose').'...', 'disabled' => false],
                    'data' => [
                        'None' => AmosAdmin::t('amosadmin', '#undefinded'),
                        'Maschio' => AmosAdmin::t('amosadmin', '#man'),
                        'Femmina' => AmosAdmin::t('amosadmin', '#women')
                    ]
                ])->label($model->getAttributeLabel(AmosAdmin::t('amosadmin', '#sex')).' '.AmosIcons::show('lock',
                        ['title' => AmosAdmin::t('amosadmin', '#confidential')]));
                ?>
            </div>
        <?php endif; ?>
        <?php
        if ($adminModule->confManager->isVisibleField('user_profile_age_group_id', ConfigurationManager::VIEW_TYPE_FORM)):
            ?>
            <div class="col-lg-3 col-sm-6">
                <?=
                $form->field($model, 'user_profile_age_group_id',
                    [
                    'template' => "{label}\n{hint}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
                ])->widget(Select::classname(),
                    [
                    'options' => ['placeholder' => AmosAdmin::t('amosadmin', '#select').'...', 'disabled' => false],
                    'data' => ArrayHelper::map(UserProfileAgeGroup::find()->orderBy(['id' => SORT_ASC])->asArray()->all(),
                        'id', 'age_group')
                ])->label($model->getAttributeLabel('age_group').' '.AmosIcons::show('lock',
                        ['title' => AmosAdmin::t('amosadmin', '#confidential')]));
                ?>
            </div>
        <?php endif; ?>
        <?php
        if ($adminModule->tightCoupling == true && !empty($adminModule->tightCouplingMethod) && is_array($adminModule->tightCouplingMethod)):
            $class  = null;
            $method = null;
            foreach ($adminModule->tightCouplingMethod as $k => $v) {
                $class  = $k;
                $method = $v;
            }

            if (!empty($class) && !empty($method) && !empty($adminModule->tightCouplingMethodField)):
                ?>
                <div class="col-lg-6 col-sm-6">
                    <?=
                    $form->field($model, 'tightCouplingField')->widget(Select2::classname(),
                        [
                        'options' => [
                            'placeholder' => AmosAdmin::t('amosadmin', 'Digita il nome del gruppo'),
                            'id' => 'tightCouplingField-id',
                            'disabled' => false,
                            'multiple' => true,
                        ],
                        'data' => ArrayHelper::map($class::$method(), 'id', $adminModule->tightCouplingMethodField)
                    ]);
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php
    if ($adminModule->confManager->isVisibleField('presentazione_breve', ConfigurationManager::VIEW_TYPE_FORM)):
        ?>
        <div class="row">
            <div class="col-xs-12 m-b-20">

                <?php
                /*
                  pr($model->presentazione_breve);
                  die();
                 */
                /* Pulizia del campo di input "presentazione_breve" da tags potenzialmente pericolosi */
                //$presentazione_breve = strip_tags($model->presentazione_breve);
                ?>

<!--                TODO perché è stata messa sta roba, che ho tolto, che si perde tutte le cose che fa Yii2 sulla validazione????????????????????????????????? -->
<!--                < ?= AmosAdmin::t('amosadmin', 'Presentazione Breve') ?>-->
<!--                < ?=-->
<!--                Html::input('text', 'UserProfile[presentazione_breve]', $model->presentazione_breve,-->
<!--                    [-->
<!--                    'id' => 'search-users-share',-->
<!--                    'class' => 'form-control pull-left',-->
<!--                    'placeholder' => AmosAdmin::t('amosadmin', '#short_presentation_placeholder'),-->
<!--                    'maxlength' => 140,-->
<!--                ]);-->
<!--                ?>-->
                
                <?= $form->field($model, 'presentazione_breve')->textInput(['maxlength' => true, 'placeholder' => AmosAdmin::t('amosadmin', '#short_presentation_placeholder')]); ?>

                <!--                < ?= Html::a(AmosAdmin::t('amosadmin', 'Do you want to include a more complete professional presentation') . '?', '#' . $idTabInsights, [-->
                <!--                    'data-toggle' => 'tab',-->
                <!--                    'class' => 'pull-right',-->
                <!--                    'id' => 'extended-presentation-link'-->
                <!--                ]) ?>-->
            </div>
        </div>
    <?php endif; ?>
    <!--    <?php /* if ($adminModule->confManager->isVisibleField('note', ConfigurationManager::VIEW_TYPE_FORM)): */ ?>
            <hr>
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    < ?= $form->field($model, 'note')->textarea(['rows' => 6, 'readonly' => false, 'maxlength' => 500]) ?>
                </div>
            </div>
    --><?php /* endif; */ ?>
</section>
