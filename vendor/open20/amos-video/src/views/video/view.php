<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\modules\formazione/views
 */

use yii\helpers\Html;

use yii\helpers\Url;
use open20\amos\core\module\BaseAmosModule;
use amos\sitemanagement\utility\EmbeddedUtility;
use open20\video\assets\ModuleVideoAsset;

ModuleVideoAsset::register($this);

/**
 * @var yii\web\View $this
 * @var frontend\modules\formazione\models\Formazione $model
 */

$this->title = strip_tags($model->title);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/video/video/all']];

$this->params['forceBreadcrumbs'][] = ['label' => 'Video', 'url' => ['all']];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <!--<div class="col-md-12">
        <p><strong><?=BaseAmosModule::t('amoscore', 'Link del video:')?></strong> <a href="<?= $model->url?>" title="<?=BaseAmosModule::t('amoscore', 'Vai al link del video')?>" target="_blank"><?= $model->url?></a> </p>
    </div>-->
    <div class="col-md-12">
        <p><strong><?=BaseAmosModule::t('amoscore', 'Descrizione:')?></strong></p>
        <?= $model->description?>
    </div>
</div>


<?= EmbeddedUtility::getUrlEmbeddedVideo($model->url, [
    'type' => 'iframe',
    'hideWatchOnYoutube' => true
])?>
<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(BaseAmosModule::t('amoscore', 'Indietro'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
