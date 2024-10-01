<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-community/src/views
 */
/**
 * @var yii\web\View $this
 * @var open20\amos\community\models\Bookmarks $model
 */

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Bookmarks',
]);

$this->params['forceBreadcrumbs'][] = ['label' => 'Community', 'url' => ['/community/community/index']];
$this->params['forceBreadcrumbs'][] = ['label' => $community->name, 'url' => ['/community/join/open-join', 'id' => $community->id]];
$this->params['forceBreadcrumbs'][] = ['label' => 'Bookmarks', 'url' => ['/community/bookmarks/index', 'id' => $community->id]];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bookmarks-update">

    <?= $this->render('_form', [
        'model' => $model,
        'community' => $community,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>
