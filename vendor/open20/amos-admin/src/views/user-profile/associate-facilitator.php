<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use yii\db\ActiveQuery;

/**
 * @var \yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 */

$this->title = AmosAdmin::t('amosadmin', 'Select facilitator for') . ' ' . $model->getNomeCognome();

/** @var UserProfile $userProfileModel */
$userProfileModel = AmosAdmin::instance()->createModel('UserProfile');
$userProfileClassName = $userProfileModel::className();
$userProfileTable = $userProfileModel::tableName();

// All facilitators without the user profile in modify.
$toSkipFacilitatorIds = [$model->user_id];

if(\Yii::$app->request->get('external')){
    if (!is_null($model->externalFacilitator)) {
        $toSkipFacilitatorIds[] = $model->externalFacilitator->user_id;
    }
    $facilitatorUserIds = array_diff(UserProfileUtility::getAllExternalFacilitatorUserIds(), $toSkipFacilitatorIds);
}
else{
    if (!is_null($model->facilitatore)) {
        $toSkipFacilitatorIds[] = $model->facilitatore->user_id;
    }
    $facilitatorUserIds = array_diff(UserProfileUtility::getAllFacilitatorUserIds(), $toSkipFacilitatorIds);
}

/** @var ActiveQuery $query */
$query = $userProfileModel::find();
$query
    ->andWhere(['user_id' => $facilitatorUserIds])
    ->andWhere(['!=', 'dont_show_facilitator' ,  1])
    ->andWhere(['not like', 'nome', UserProfileUtility::DELETED_ACCOUNT_NAME])
    ->orderBy(['cognome' => SORT_ASC, 'nome' => SORT_ASC]);
$post = Yii::$app->request->post();

if (isset($post['genericSearch'])) {
    $query->andFilterWhere(['or',
        ['like', "CONCAT( " . $userProfileTable . ".nome , ' ', " . $userProfileTable . ".cognome )", $post['genericSearch']],
        ['like', "CONCAT( " . $userProfileTable . ".cognome , ' ', " . $userProfileTable . ".nome )", $post['genericSearch']],
        ['like', $userProfileTable . '.cognome', $post['genericSearch']],
        ['like', $userProfileTable . '.nome', $post['genericSearch']],
        ['like', $userProfileTable . '.codice_fiscale', $post['genericSearch']],
        ['like', $userProfileTable . '.domicilio_indirizzo', $post['genericSearch']],
        ['like', $userProfileTable . '.indirizzo_residenza', $post['genericSearch']],
        ['like', $userProfileTable . '.domicilio_localita', $post['genericSearch']],
        ['like', $userProfileTable . '.domicilio_cap', $post['genericSearch']],
        ['like', $userProfileTable . '.cap_residenza', $post['genericSearch']],
        ['like', $userProfileTable . '.numero_civico_residenza', $post['genericSearch']],
        ['like', $userProfileTable . '.domicilio_civico', $post['genericSearch']],
        ['like', $userProfileTable . '.telefono', $post['genericSearch']],
        ['like', $userProfileTable . '.cellulare', $post['genericSearch']],
        ['like', $userProfileTable . '.email_pec', $post['genericSearch']],
    ]);
}

$formName = 'UserProfile';
$postKey = 'user';
$js = <<<JS
var hiddenInputContainer = $('.hiddenInputContainer');
$('body').on('click', '.confirmBtn', function(event) {
    event.preventDefault();
   var selectedId = $(this).data('model_id');
   var newHiddenInput = '<input type="hidden" name="selected[]" value="'+ selectedId + '"/>';
   var selection = '<input type="hidden" name="selection[]" value="'+ selectedId + '"/>';
   hiddenInputContainer.empty();
   hiddenInputContainer.append(newHiddenInput);
   hiddenInputContainer.append(selection);
   frm = hiddenInputContainer.parents('form');
   document.body.appendChild(frm[0]);
   frm.submit();
  
});
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>


<?= M2MWidget::widget([
    'model' => $model,
    'modelId' => $model->id,
    'modelData' => $userProfileModel::find()->andWhere(['id' => $model->facilitatore_id]),
    'modelDataArrFromTo' => [
        'from' => 'facilitatore_id',
        'to' => 'user_id'
    ],
    'modelTargetSearch' => [
        'class' => $userProfileClassName,
        'query' => $query,
    ],
    'gridId' => 'associate-facilitator',
    'viewSearch' => (isset($viewM2MWidgetGenericSearch) ? $viewM2MWidgetGenericSearch : false),
    'multipleSelection' => false,
    'relationAttributesArray' => ['status', 'role'],
    'moduleClassName' => AmosAdmin::className(),
    'postName' => $formName,
    'postKey' => $postKey,
    'listView' => '@vendor/open20/amos-admin/src/views/user-profile/_item',
    'targetFooterButtons' => M2MWidget::makeCancelButton(AmosAdmin::className(), 'user-profile', $model),
    'targetUrlController' => 'user-profile',
    'targetUrlParams' => [
        'viewM2MWidgetGenericSearch' => true
    ],
    'targetColumnsToView' => [
        'name' => [
            'attribute' => 'profile.surnameName',
            'label' => AmosAdmin::t('amosadmin', 'Name'),
            'headerOptions' => [
                'id' => AmosAdmin::t('amosadmin', 'Name'),
            ],
            'contentOptions' => [
                'headers' => AmosAdmin::t('amosadmin', 'Name'),
            ]
        ],
    ],
]);
?>
