<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\prodottiservizi/views 
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\core\module\BaseAmosModule;
use open20\prodottiservizi\utility\ProdottiServiziUtility;
use open20\prodottiservizi\Module;

/**
 * @var yii\web\View $this
 * @var open20\prodottiservizi\models\ProdottiServizi $model
 * @var \open20\prodottiservizi\models\search\ProdottiServiziAccordionSearch $dataProviderAccordion
 */

$isSportello = !is_null($model->id_product_root);

$this->title = strip_tags($model->title);
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Prodotti-e-Servizi'), 'url' => ['index']];
$this->params['forceBreadcrumbs'][] = ['label' => Yii::t('prodottiservizi', 'Prodotti e Servizi'), 'url' => ['index']];
$this->params['forceBreadcrumbs'][] = ['label' => \open20\prodottiservizi\utility\ProdottiServiziUtility::getAmbitoLabel($model->scope), 'url' => ['index', 'ProdottiServiziSearch[scope]' => $model->scope]];
$this->params['forceBreadcrumbs'][] = ['label' => $model->title];

$AccordionDescriptionClass = "collapse in"; // Solo il primo è visibile

$js= <<<JS
    $('.icon-card').each(function(){
        if(window.getComputedStyle(this,':before').content == 'none'){
            let classes = $(this).attr('class');
            classes=classes.replace('-outline', '').replace('mdi ', 'mdi mdi-alert-outline ');
            $(this).attr('class', classes);
        }
    });
JS;

$this->registerJs($js);
?>

<?php if ($isSportello) echo '<div class="info-sportello m-b-20"><span class="mdi mdi-information-outline"></span><span class="m-r-20">Sportello del ' . \Yii::$app->getFormatter()->asDate($model->created_at) . '</span> ' . Html::a(Module::t('prodottiservizi', 'Vai al Servizio più recente'), ['prodotti-servizi/view', 'id' => $model->id_product_root]) . '</div>';
?>
<style>
    .pre-footer{
        display: block;
    }
</style>
<div class="prodotti-servizi-view">
    <div class="img-detail">
        <?= Html::img($model->getIconUrl(), ['alt' => Yii::t('prodottiservizi', 'Immagine associata al prodotto o servizio')]); ?>
    </div>
    <div class="intro-detail">
        <div class="riga-intro">
            <div class="soluzione"><span class="mdi mdi-<?= $model->soluzione->icon ?>-outline icon-card"></span><?= $model->soluzione->name ?></div>
            <div class="label-aggiornamento"><strong><?= Yii::t('prodottiservizi', 'Ultimo aggiornamento') ?>:</strong> <?= \Yii::$app->getFormatter()->asDate($model->updated_at) ?></div>
            <div class="status <?= ProdottiServiziUtility::getStatiClassLabel($model->status) ?>"><?= ProdottiServiziUtility::getStatoLabel($model->status) ?></div>
            <?php
            echo \open20\amos\core\forms\ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => "/prodotti-e-servizi/prodotti-servizi/update?id=" . $model->id,
                'actionDelete' => "/prodotti-e-servizi/prodotti-servizi/delete?id=" . $model->id,
                'labelDeleteConfirm' => \open20\prodottiservizi\Module::t('prodottiservizi', 'Sei sicuro di voler cancellare questo bookmark?')
            ]); ?>
        </div>
        <div class="riga-intro">
            <strong class="m-r-10"><?= Yii::t('prodottiservizi', 'Destinatari') ?>:</strong>
            <?php foreach ($model->destinatariMm as $mm) : ?>
                <div class="destinatario m-r-10"><span class="mdi mdi-<?= $mm->destinatario->icon ?> icon-destinatario m-r-5"></span><?= $mm->destinatario->name ?></div>
            <?php endforeach; ?>
            <div class="content-finalita">
                <strong class="m-r-10"><?= Yii::t('prodottiservizi', 'Finalità') ?>:</strong>
            </div>
            <?php foreach ($model->finalitaMm as $mm) : ?>
                <div class="finalita m-r-10"><?= $mm->finalita->name ?></div>
            <?php endforeach; ?>
        </div>
        <p><strong><?= Yii::t('prodottiservizi', 'PREMESSA') ?></strong></p>
        <p><?= $model->description; ?></p>
    </div>



    


    <div class="content-detail">
        <?php foreach($dataProviderAccordion->getModels() as $accordion) : ?>
            <div class="listato-contenuti">
                <a class="link-toggle" role="button" data-toggle="collapse" href="#accordion-<?= $accordion->id ?>" aria-expanded="<?= ($AccordionDescriptionClass == 'collapse in')?'true':'false'?>" aria-controls="accordion-<?= $accordion->id ?>">
                    <span><?= $accordion->title ?></span><span class="mdi mdi-chevron-down"></span>
                </a>
                <div class="<?= $AccordionDescriptionClass ?>" id="accordion-<?= $accordion->id ?>">
                    <p>
                        <?= $accordion->description; ?><br>
                    </p>
                </div>
            </div>
        <?php $AccordionDescriptionClass = "collapse"; endforeach; ?>

        <div class="row m-t-30">
            <div class="col-md-12">
                <?=
                \open20\amos\attachments\components\AttachmentsList::widget([
                    'model' => $model,
                    'attribute' => 'attachments',
                    'label' => Yii::t('prodottiservizi', 'Documenti per le imprese'),
                    'useOnlyOneColumn' => true
                ])
                ?>
            </div>
        </div>


    </div>

    <strong><?= Yii::t('prodottiservizi', 'ARCHIVIO SPORTELLI') ?></strong>
    <?php if(count($sportelli) > 0) : ?>
        <div class="content-sportello">
            <div class="scroll-sportello">
                <div class="lista-sportello">
                    <?php foreach ($sportelli as $sportello) :
                        $classActive = ($model->id == $sportello->id) ? ' active-sportello' : '';
                    ?>
                        <div class="item-sportello<?= $classActive ?>">
                            <?= Html::a('<span>sportello del</span> ' . '<strong>' . Yii::$app->getFormatter()->asDate($sportello->created_at, 'dd/MM/YYYY') . '</strong>', 
                                    [Url::to(['/prodotti-e-servizi/prodotti-servizi/view', 'id' => $sportello->id]), ],
                                    ['title' => 'Vai alla pagina dello sportello del '. Yii::$app->getFormatter()->asDate($sportello->created_at, 'dd/MM/YYYY')]
                                ); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php else : ?>
        <p><?= Yii::t('prodottiservizi', 'Nessun sportello salvato.') ?></p>
    <?php endif; ?>
</div>

<div id="form-actions" class="bk-btnFormContainer pull-right m-t-35">
    <?= Html::a(BaseAmosModule::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>
</div>
<div class="clearfix"></div>