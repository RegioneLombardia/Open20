<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/socialwall/views 
 */
/**
* @var yii\web\View $this
* @var open20\socialwall\models\SocialwallPostsProfiles $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Socialwall Posts Profiles',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/socialwall']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Socialwall Posts Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="socialwall-posts-profiles-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
