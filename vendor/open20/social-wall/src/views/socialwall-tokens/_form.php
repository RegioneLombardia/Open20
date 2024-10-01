<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/socialwall/views 
 */
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use open20\amos\core\forms\TextEditorWidget;
use yii\helpers\Inflector;

/**
* @var yii\web\View $this
* @var open20\socialwall\models\SocialwallTokens $model
* @var yii\widgets\ActiveForm $form
*/


 ?>
<div class="socialwall-tokens-form col-xs-12 nop">

    <?php     $form = ActiveForm::begin([
    'options' => [
    'id' => 'socialwall-tokens_' . ((isset($fid))? $fid : 0),
    'data-fid' => (isset($fid))? $fid : 0,
    'data-field' => ((isset($dataField))? $dataField : ''),
    'data-entity' => ((isset($dataEntity))? $dataEntity : ''),
    'class' => ((isset($class))? $class : '')
    ]
    ]);
     ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>
    
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'type')->dropDownList(\open20\socialwall\utility\Social::enabledSocialsList(), $model->isNewRecord ? [] : ['disabled' => true]); ?>

                <?php
                    if($model->type === \open20\socialwall\utility\Social::FACEBOOK) :
                ?>

                <?=
                    Html::a('Facebook Login', \open20\socialwall\utility\Social::facebookLoginUrlGenerator($model->id, $model->secret));
                ?>

                <?php
                    $this->registerJs(<<<JS
window.fbAsyncInit = function() {
FB.init({
  appId      : '718967183311300',
  cookie     : true,
  xfbml      : true,
  version    : 'v16.0'
});
  
FB.AppEvents.logPageView();   

FB.getLoginStatus(function(response) {
    console.log(response);
    // statusChangeCallback(response);
});
  
};

(function(d, s, id){
 var js, fjs = d.getElementsByTagName(s)[0];
 if (d.getElementById(id)) {return;}
 js = d.createElement(s); js.id = id;
 js.src = "https://connect.facebook.net/en_US/sdk.js";
 fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));



JS
                    );
                ?>

                <?php
                    endif;
                ?>

                <?php
                if($model->type === \open20\socialwall\utility\Social::INSTAGRAM) :
                    ?>

                    <?=
                    Html::a('Instagram Login', \open20\socialwall\utility\Social::instagramLoginUrlGenerator($model->id, $model->secret));
                    ?>

                <?php
                endif;
                ?>

                <?php
                    if(!$model->isNewRecord) :
                ?>

                    <?php
                        if($model->type === \open20\socialwall\utility\Social::TWITTER) :
                    ?>

                        <?= $form->field($model, 'twitter_username')->textInput() ?>

                        <?= $form->field($model, 'twitter_token')->textInput() ?>

                    <?php
                        endif;
                    ?>

                    <?php
                        if($model->type !== \open20\socialwall\utility\Social::TWITTER) :
                    ?>
                    <div class="m-t-20">
                        <div class="row">
                            <div class="col-xs-12">
                                <strong>
                                    <?= Yii::t('socialwall', 'Scadrà il'); ?>
                                    <?=
                                    Yii::$app->formatter->asDate(
                                            strtotime($model->expires_at),
                                            isset(
                                                    \Yii::$app->components['formatter']['datetimeFormat']) ?
                                                    \Yii::$app->components['formatter']['datetimeFormat'] :
                                                    'd-m-Y H:i:s');
                                    ?>
                                </strong>
                            </div>
                        </div>
                    </div>

                            <?php
                            if(Yii::$app->getModule('socialwall')->enableAdvancedSocialConfigs) :
                                ?>

                                <div class="m-t-20">

                                    <?= Html::a(\open20\socialwall\Module::t('socialwall', "Mostra campi avanzati"), '#', [
                                        'class' => 'm-t-5',
                                        'onClick' => "(function () { $(\"#advanced-fields\").toggle(); })();return false;"
                                    ]); ?>

                                    <div id="advanced-fields" class="m-t-10" style="display:none;">

                                        <?= $form->field($model, 'data')->textarea(['rows' => 6]) ?>

                                        <?php
                                            if($model->type === \open20\socialwall\utility\Social::INSTAGRAM) :
                                        ?>

                                            <?=
                                                Html::a(\open20\socialwall\Module::t('socialwall', 'Forza aggiornamento cache Instagram'), ['/socialwall/socialwall-tokens/force-instagram-cache', 'socialwallTokensId' => $model->id]);
                                            ?>

                                            <div>
                                                (Ultimo aggiornamento:
                                                <?=
                                                Yii::$app->formatter->asDate(
                                                    strtotime(\open20\socialwall\models\SocialwallInstagramStore::findOne(['id' => 1])->updated_at),
                                                    isset(
                                                        \Yii::$app->components['formatter']['datetimeFormat']) ?
                                                        \Yii::$app->components['formatter']['datetimeFormat'] :
                                                        'd-m-Y H:i:s');
                                                ?>)
                                            </div>

                                        <?php
                                            endif;
                                        ?>

                                    </div>

                                </div>

                            <?php
                            endif;
                            ?>
                    <?php
                        endif;
                    ?>

                <?php
                    endif;
                ?>

                <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
            </div>

            <div class="col-md-4 col xs-12"></div>

        </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>

</div>

