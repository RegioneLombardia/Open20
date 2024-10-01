<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\partnership-profiles
 * @category   CategoryName
 */

/**
 * @var yii\web\View $this
 * @var open20\amos\partnershipprofiles\models\PartnershipProfiles $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?= $this->render('_form', [
    'model' => $model,
    'fid' => (null !== (filter_input(INPUT_GET, 'fid'))) ? filter_input(INPUT_GET, 'fid') : '',
    'dataField' => (null !== (filter_input(INPUT_GET, 'dataField'))) ? filter_input(INPUT_GET, 'dataField') : '',
    'dataEntity' => (null !== (filter_input(INPUT_GET, 'dataEntity'))) ? filter_input(INPUT_GET, 'dataEntity') : '',
    'class' => 'dynamicCreation'
]) ?>