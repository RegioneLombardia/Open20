<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/socialwall/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\socialwall\models\search\SocialwallPostsSearch $model
 */

$this->title = Yii::t('amoscore', 'Importa contenuti social');
$this->params['breadcrumbs'][] = ['label' => 'Asset Social', 'url' => ['/socialwall/socialwall-posts']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['titleSection'] = $this->title;
$this->params['hideCreate'] = true;
$this->params['disable-search'] = true;

$this->params['labelLinkAll'] = Yii::t('socialwall', 'Torna all\'elenco dei contenuti già importati');
$this->params['urlLinkAll'] = '/socialwall/socialwall-posts';
$this->params['titleLinkAll'] = Yii::t('socialwall', 'Torna all\'elenco dei contenuti già importati');
?>

<?php $form = \open20\amos\core\forms\ActiveForm::begin([
    'options' => [
        'id' => 'socialwall-load-post-from-social_' . ((isset($fid)) ? $fid : 0),
        'data-fid' => (isset($fid)) ? $fid : 0,
        'data-field' => ((isset($dataField)) ? $dataField : ''),
        'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
        'class' => ((isset($class)) ? $class : '')
    ]
]);
?>

<div class="row">
    <div class="col-xs-12">
        <?=
            $form->field($modelSocialwall, 'socialChoices')->checkboxList(\open20\socialwall\utility\Social::getSocialwallTokensEnabled());
        ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-4">
        <div>
            <?=
            $form->field($modelSocialwall, 'socialTags')->widget(\xj\tagit\Tagit::classname(), [
                'clientOptions' => [
                    'singleField' => true,
                ],
            ])
            ?>
        </div>
    </div>
    <div class="col-xs-4">
        <?=
            $form->field($modelSocialwall, 'since')->widget(\kartik\widgets\DatePicker::class,
                [
                    'pluginOptions' => [
                        "endDate" => "0d",
                        'format' => 'dd-mm-yyyy',
                    ],
                ]
            );
        ?>
    </div>
    <div class="col-xs-4">
        <?=
            $form->field($modelSocialwall, 'until')->widget(\kartik\widgets\DatePicker::class,
                [
                    'pluginOptions' => [
                        "endDate" => "0d",
                        'format' => 'dd-mm-yyyy',
                    ],
                ]
            );
        ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <?= Html::submitButton(\open20\socialwall\Module::t('socialwall', 'Cerca'), [
            'class' => 'btn btn-primary',
        ]); ?>
    </div>
</div>

<?php \open20\amos\core\forms\ActiveForm::end(); ?>

<div class="row m-b-20">
    <div class="col-xs-12">
        <?=
            \open20\socialwall\widgets\SocialwallWidget::widget([
                'posts' => $posts,
                'socialwallModelId' => 1,
                'showLoadMoreButton' => false,
                'showPostCheckboxes' => true,
                'postCheckboxesLabel' => \open20\socialwall\Module::t('socialwall', 'Aggiungi come asset'),
            ]);
        ?>
    </div>
</div>

<?php
$this->registerCss(<<<CSS
#socialwall-posts-container div.select-social-post:not(.loaded) {
    cursor: pointer;
}

#socialwall-posts-container .select-social-post span.loading {
    -webkit-animation:spin 1.5s linear infinite;
    -moz-animation:spin 1.5s linear infinite;
    animation:spin 1.5s linear infinite;
}

@-moz-keyframes spin { 
    100% { -moz-transform: rotate(360deg); } 
}
@-webkit-keyframes spin { 
    100% { -webkit-transform: rotate(360deg); } 
}
@keyframes spin { 
    100% { 
        -webkit-transform: rotate(360deg); 
        transform:rotate(360deg); 
    } 
}
CSS
);
?>
<?php
    $goToPostTransl = \open20\socialwall\Module::t('socialwall', 'Modifica post');
    $editAssetTransl = \open20\socialwall\Module::t('socialwall', 'Modifica asset');
    $savingAssetTransl = \open20\socialwall\Module::t('socialwall', 'Salvataggio in corso...');
    $this->registerJs(<<<JS
$("#socialwall-posts-container").find("div.select-social-post:not(.loaded)").click(function () {
  let elem = $(this);
  $(elem).find(".icon-check").addClass("loading");
  $(elem).find(".icon-check").removeClass("am-plus");
  $(elem).find(".icon-check").addClass("am-rotate-right");
  $(elem).find(".label-checkboxes").text("{$savingAssetTransl}");
  
  $.ajax({
    timeout: 8000,
    url: '/socialwall/socialwall-posts/search-post-to-asset',
    method: 'GET',
    data: {'postId':$(elem).attr('data-post-id')}
  }).done(function (res) {
    $(elem).addClass("loaded success btn-success");
    $(elem).find(".icon-check").addClass("am-edit");
    $(elem).unbind("click");
    $(elem).find(".label-checkboxes").text("{$editAssetTransl}");
    $(elem).wrap("<a href=\"" + res + "\" title=\"{$goToPostTransl}\" target=\"_blank\"></a>");
  })
  .fail(function (res) {
    elem.find(".icon-check").addClass("am-plus");
  })
  .always(function () {
    elem.find(".icon-check").removeClass("am-rotate-right");
    elem.find(".icon-check").removeClass("loading");
  });
});
JS
    );
?>