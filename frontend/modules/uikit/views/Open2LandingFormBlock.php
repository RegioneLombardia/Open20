<!DOCTYPE html>
<?php

use app\assets\SocialAsset;
use app\modules\uikit\Module;
use open20\amos\attachments\components\AttachmentsInput;
use open20\design\components\bootstrapitalia\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use kartik\datecontrol\DateControl;
use kartik\widgets\DepDrop;
use luya\helpers\Url;
use trk\uikit\Uikit;
use app\modules\uikit\BaseUikitBlock;

$canSeeBlock = true;
$visibility = $this->varValue('visibility');

switch($visibility){
    case 'guest':
        $canSeeBlock = Yii::$app->user->isGuest;          
    break;
    case 'logged':
        $canSeeBlock = !Yii::$app->user->isGuest; 
		$n_class = $this->varValue('addclass');
		if($canSeeBlock && !empty($n_class)){
			$canSeeBlock = BaseUikitBlock::visivility($n_class);
		}
    break;
}





SocialAsset::register($this);

$hideForm = false;

$id = '';
$class = [];
$attrs = [];

$item_attrs = [];

$style = $this->varValue('style', '');
if ($style) {
    $class[] = 'uk-form-' . $style;
    if ($style == 'stacked') {
        $item_attrs['class'][] = 'uk-margin';
    }
}

$attrs['role'] = 'form';
$attrs['method'] = 'post';

$userProfile = null;
if(!\Yii::$app->user->isGuest)
    $userProfile = \Yii::$app->user->identity->userProfile;
?>
<?php if ($canSeeBlock): ?>
    <div>
        <?php
        //Uikit::trace($data);
        //Uikit::trace($debug);
        //Uikit::trace($request);
        //Uikit::trace($model->attributes());
        $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data', 'autocomplete' => "off"],
            'encodeErrorSummary' => false,
            'fieldConfig' => ['errorOptions' => ['encode' => false, 'class' => 'help-block']]
        ]);
        ?>
        <?php if ($data['register_with_social']) { ?>
            <div id="social-container" class="col-xs-12 m-t-30 nop social-container text-center">
                <span style="width:100%;display:block;"><strong>Accedi</strong> con i tuoi account social</span>
                <div class="social-buttons col-xs-12 text-center" style="float:none;">
                    <?php if ($data['goolge_reg']) { ?>

                        <a data-key="google" class="btn btn-facebook social-link btn-login-social"
                        title="Accedi con Google" target="_self"
                        href="<?= Url::ensureHttp(Yii::$app->menu->current->getAbsoluteLink(),
                            true) ?>?social=google">
                            <span class="am am-google"></span>
                            <!--                            <span class="text">Facebook</span>-->

                        </a>
                    <?php } ?>

                    <?php if ($data['facebook_reg']) { ?>
                        <a data-key="facebook" class="btn btn-facebook social-link btn-login-social"
                        title="Accedi con Facebook" target="_self"
                        href="<?= Url::ensureHttp(Yii::$app->menu->current->getAbsoluteLink(),
                            true) ?>?social=facebook">
                            <span class="am am-facebook"></span>
                            <!--                            <span class="text">Facebook</span>-->

                        </a>
                    <?php } ?>

                    <?php if ($data['twitter_reg']) { ?>
                        <a data-key="twitter" class="btn btn-twitter social-link btn-login-social"
                        title="Accedi con Twitter" target="_self"
                        href="<?= Url::ensureHttp(Yii::$app->menu->current->getAbsoluteLink(),
                            true) ?>?social=twitter">
                            <span class="am am-twitter"></span>
                            <!--                            <span class="text">Twitter</span>-->
                        </a>
                    <?php } ?>

                    <?php if ($data['linkedin_reg']) { ?>

                        <a data-key="linkedin" class="btn btn-linkedin social-link btn-login-social"
                        title="Accedi con LinkedIn" target="_self"
                        href="<?= Url::ensureHttp(Yii::$app->menu->current->getAbsoluteLink(),
                            true) ?>?social=linkedin">
                            <span class="am am-linkedin"></span>
                            <!--                            <span class="text">LinkedIn</span>-->
                        </a>
                    <?php } ?>

                </div>
                <span style="width:100%;display:block;margin-top:20px;">o <strong>compila</strong> i seguenti campi</span>
            </div>
        <?php } ?>
        <div class="col-xs-12 nop">
            <?php
            echo Html::hiddenInput('id', $data['id']);

            $attributes = $model->attributes();
            unset($attributes[array_search($model->getPrimaryKey(), $attributes)]);
            foreach ($attributes as $attribute) {
                
                $autocomplete = false;
                if(isset($data['items'])){
                    $key = array_search($attribute, array_column($data['items'], 'field'));
                    if(isset($data['items'][$key]) && $data['items'][$key]['autocomplete']){                    
                        if($data['items'][$key]['autocomplete'] == 1)
                            $autocomplete = true;                   
                    }
                } 
                
                if($autocomplete && !is_null($userProfile)){
                    if(isset($userProfile->$attribute))
                        $model->$attribute = $userProfile->$attribute;
                }
                
                ?>
                <div<?= Uikit::attrs($item_attrs) ?> class="<?= $attribute ?>_item">
                    <?php
                    $filedItem = '';
                    switch ($model->attributeTypes()[$attribute]) {
                        case 'hidden':
                            $filedItem = $form->field($model, $attribute)->hiddenInput($model->getAttributeOptions($attribute))->label(false);
                            break;
                        case 'label':
                            $filedItem = Html::tag('span',
                                $model->getAttributeLabel($attribute),
                                $model->getAttributeOptions($attribute));
                            break;
                        case 'attachmentsInput':
                            $filedItem = $form->field($model, $attribute,
                                [
                                    'labelOptions' => ['encode' => false],
                                ])->widget(AttachmentsInput::classname(),
                                [
                                    'options' => [
                                        'owner' => 1,
                                        'multiple' => FALSE,
                                    ],
                                    'pluginOptions' => [// Plugin options of the Kartik's FileInput widget
                                        'maxFileCount' => 1,
                                        'showRemove' => false,
                                        'indicatorNew' => false,
                                        'allowedPreviewTypes' => false,
                                        'previewFileIconSettings' => false,
                                        'overwriteInitial' => false,
                                        'layoutTemplates' => false,
                                    ]
                                ])->label($model->getAttributeLabel($attribute));
                            break;
                        case 'checkbox':
                            $filedItem = $form->field($model, $attribute,
                                $model->getAttributeOptions($attribute))->checkbox([
                                'label' => $model->getAttributeLabel($attribute),
                                'labelOptions' => ['encode' => false]
                            ])->hint($model->getAttributeHint($attribute));
                            break;
                        case 'radioList':
                            $filedItem = $form->field($model, $attribute,
                                $model->getAttributeOptions($attribute))->radioList($model->attributeSubvalues()[$attribute])
                                ->label($model->getAttributeLabel($attribute));
                            break;
                        case 'reCaptcha':

                            $filedItem = $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className());
                            break;
                        case 'checkList':
                            $filedItem = $form->field($model, $attribute,
                                $model->getAttributeOptions($attribute))->checkboxList($model->attributeSubvalues()[$attribute])
                                ->label($model->getAttributeLabel($attribute));
                            break;
                        case 'string':
                            $filedItem = $form->field($model, $attribute,
                                $model->getAttributeOptions($attribute))->textInput([
                                'title' => $model->getAttributeHint($attribute),
                                'data-placement' => 'bottom',
                                'onclick' => "$(this).tooltip('show');"
                            ])->label($model->getAttributeLabel($attribute))->hint(false);
                            break;
                        case 'email':
                            /**
                             * Eccezione per la mail che viene presa da user e non da userprofile
                             * in caso di autocomplete
                             */
                            if($autocomplete && !is_null($userProfile)){                            
                                $user = $userProfile->getUser()->one();                            
                                if(isset($user) && isset($user->$attribute))
                                    $model->$attribute = $user->$attribute;
                            }
                            $filedItem = $form->field($model, $attribute,
                            $model->getAttributeOptions($attribute))->label($model->getAttributeLabel($attribute));
                            break;
                        case 'textarea':
                            $filedItem = $form->field($model, $attribute,
                                $model->getAttributeOptions($attribute))->textarea([
                                'title' => $model->getAttributeHint($attribute),
                                'data-placement' => 'bottom',
                                'onclick' => "$(this).tooltip('show');"
                            ])->label($model->getAttributeLabel($attribute))->hint(false);
                            break;
                        case 'date':
                            $filedItem = $form->field($model, $attribute,
                                $model->getAttributeOptions($attribute))->widget(DateControl::className(),
                                [
                                    'type' => DateControl::FORMAT_DATE
                                ])->label($model->getAttributeLabel($attribute));
                            break;
                        case 'password':
                            $filedItem = $form->field($model, $attribute,
                                $model->getAttributeOptions($attribute))->passwordInput([
                                'autocomplete' => 'off'])->label($model->getAttributeLabel($attribute));
                            break;
                        case 'select':
                            $filedItem = $form->field($model, $attribute, 
                                $model->getAttributeOptions($attribute))->widget(\open20\design\components\bootstrapitalia\Select::className(),
                                [
                                    'value' => '',
                                    'label' => $model->getAttributeLabel($attribute),
                                    'items' => $model->attributeSubvalues()[$attribute],
                                    'options' => [
                                        'placeholder' => Module::t('Seleziona'),
                                    ],
                                ])->label(false);
                            break;
                        case 'selectdb':
                            if (count($model->attributeSubvalues()[$attribute])) {
                                $data = [];
                                $method_data = key($model->attributeSubvalues()[$attribute]);
                                if (!empty($method_data)) {
                                    $method_params = $model->attributeSubvalues()[$attribute][$method_data];
                                    $data = $method_data($method_params);
                                }
                                $filedItem = $form->field($model, $attribute,
                                    $model->getAttributeOptions($attribute))->widget(
                                            \open20\design\components\bootstrapitalia\Select::className(),
                                    [
                                        'value' => '',
                                        'label' => $model->getAttributeLabel($attribute),
                                        'items' => $data,
                                        'options' => [
                                            'id' => $attribute . '-id',
                                            'placeholder' => Module::t('Seleziona'),
                                        ],
                                    ])->label(false);
                            }
                            break;
                        case 'selectrel':
                            if (count($model->attributeSubvalues()[$attribute])) {
                                $data = [];
                                $id_rel = key($model->attributeSubvalues()[$attribute]);
                                if (!empty($id_rel)) {
                                    $method_data = $model->attributeSubvalues()[$attribute][$id_rel];
                                }
                                $filedItem = $form->field($model, $attribute,
                                    $model->getAttributeOptions($attribute))->widget(DepDrop::className(),
                                    [
                                        'type' => DepDrop::TYPE_SELECT2,
                                        'options' => [
                                            'id' => $attribute . '-id',
                                            'prompt' => Module::t('Seleziona'),
                                            'label' => $model->getAttributeLabel($attribute),
                                        ],
                                        'pluginOptions' => [
                                            'depends' => [$id_rel],
                                            'placeholder' => [Module::t('Seleziona')],
                                            'url' => Url::to([$method_data]),
                                            'initialize' => true,
                                            'params' => [$attribute . '-id'],
                                        ],
                                    ])->label($model->getAttributeLabel($attribute));
                            }
                            break;
                        default:
                            $filedItem = $form->field($model, $attribute,
                                $model->getAttributeOptions($attribute))->label($model->getAttributeLabel($attribute));
                            break;
                    }
                    echo $filedItem;
                    ?>
                </div>
                <?php
            }
            ?>
            <div class="uk-form-controls">
                <?=
                Html::submitButton(!empty($data['submitlabel']) ? $data['submitlabel'] : 'Submit',
                    ['class' => 'btn btn-primary'])
                ?>
            </div>
        </div>
        <?php
        ActiveForm::end();
        ?>
    </div>
<?php endif; ?>