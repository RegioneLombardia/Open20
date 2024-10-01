<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\partnership-profiles
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\partnershipprofiles\controllers\PartnershipProfilesController;
use open20\amos\partnershipprofiles\Module;
use yii\db\ActiveQuery;
use yii\web\View;

/**
 * @var \yii\web\View $this
 * @var open20\amos\partnershipprofiles\models\PartnershipProfiles $model
 */

$this->title = Module::t('amospartnershipprofiles', 'Select facilitator for') . ' "' . $model->getTitle() . '"';

/** @var PartnershipProfilesController $appController */
$appController = Yii::$app->controller;

/** @var Module $partnershipProfilesModule */
$partnershipProfilesModule = Module::instance();

/** @var UserProfile $userProfileModel */
$userProfileModel = AmosAdmin::instance()->createModel('UserProfile');
$userProfileClassName = $userProfileModel::className();
$userProfileTable = $userProfileModel::tableName();

$facilitatorUserIds = $appController->getFacilitatorsList($model);
if (!empty($model->partnership_profile_facilitator_id)) {
    $facilitatorUserIds = array_diff($facilitatorUserIds, [$model->partnership_profile_facilitator_id]);
}
if ($partnershipProfilesModule->hideAdminsInPartProfFacilitatorSelection) {
    $adminIds = \Yii::$app->getAuthManager()->getUserIdsByRole('ADMIN');
    $facilitatorUserIds = array_diff($facilitatorUserIds, $adminIds);
}

/** @var ActiveQuery $query */
$query = $userProfileModel::find();
$query
    ->andWhere(['user_id' => $facilitatorUserIds])
    ->andWhere(['not like', 'nome', UserProfileUtility::DELETED_ACCOUNT_NAME])
    ->orderBy(['cognome' => SORT_ASC, 'nome' => SORT_ASC]);
$post = Yii::$app->request->post();

if (isset($post['genericSearch'])) {
    $query->andFilterWhere(['or',
        ['like', $userProfileTable . '.cognome', $post['genericSearch']],
        ['like', $userProfileTable . '.nome', $post['genericSearch']],
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
   hiddenInputContainer.parents('form').submit();
});
JS;
$this->registerJs($js, View::POS_READY);

?>

<?= M2MWidget::widget([
    'model' => $model,
    'modelId' => $model->id,
    'modelData' => $userProfileModel::find()->andWhere(['user_id' => $model->partnership_profile_facilitator_id]),
    'modelDataArrFromTo' => [
        'from' => 'id',
        'to' => 'id'
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
    'targetFooterButtons' => M2MWidget::makeCancelButton(Module::className(), 'partnership-profiles', $model),
    'targetUrlController' => 'partnership-profiles',
    'targetUrlParams' => [
        'viewM2MWidgetGenericSearch' => true
    ],
    'targetColumnsToView' => [
        'name' => [
            'attribute' => 'profile.surnameName',
            'label' => Module::t('amospartnershipprofiles', 'Name'),
            'headerOptions' => [
                'id' => Module::t('amospartnershipprofiles', 'Name'),
            ],
            'contentOptions' => [
                'headers' => Module::t('amospartnershipprofiles', 'Name'),
            ]
        ]
    ]
]);
?>
