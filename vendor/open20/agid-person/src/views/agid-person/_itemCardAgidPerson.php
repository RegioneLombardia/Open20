<?php
/* use open20\design\assets\BootstrapItaliaDesignAsset;
$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this); */

use app\modules\backendobjects\frontend\Module;

$columnWidth = (isset($columnWidth)) ? $columnWidth : 'col-12';
$topicIcon = (isset($topicIcon)) ? $topicIcon : 'help-circle-outline';

if ($model->agid_person_type_id == 1) {
    $categoryIcon = 'account-tie';
} else {
    if ($model->agid_person_type_id == 2) {
        $categoryIcon = 'card-account-details-outline';
    } else {
        $categoryIcon = 'file-document';
    }
}


$module = Module::getInstance();
$urlDetail = \Yii::$app->getModule('backendobjects')::getDetachUrl($model->id, $model->className(), 
                    $module->modelsDetailMapping[ $model->className() ]);


$titleCard = $model->name . ' ' . $model->surname;
if ($model->email) {
    $email = ' ' . $model->email;
}
if ($model->telephone) {
    $telephone = ' ' . $model->telephone;
}
if (!is_null($model->photo)) {
    $imageUrl = $model->photo->getWebUrl('dashboard_news', false, true);
}

?>


<div class="agid-person-card-container box-card mb-4">

    <div class=" h-100 agid-person-card-shadow">

        <div class="row h-100 ">

            <?php if (!is_null($model->photo)) : ?>

                <div class="agid-person-card-info col-sm-6 col-lg-8 d-flex flex-column">
                    <a class="h5 pt-4 px-4 agid-person-card-title text-uppercase font-weight-bold primary-color" title="Vai alla scheda di <?=$titleCard?>" href="<?=$urlDetail?>"><?=$titleCard?></a>
                    
                    <div class="px-4 agid-person-card-role-description"><?=$model->role_description?> in carica dal
                        <?=Yii::$app->formatter->asDate($model->date_start_settlement, 'dd') . ' ' . ucwords(Yii::$app->formatter->asDate($model->date_start_settlement, 'MMMM')) . ' ' . Yii::$app->formatter->asDate($model->date_start_settlement, 'YYYY');?>
                    </div>

                    <div class="py-4 px-4 agid-person-card-read-more flex-grow-1 d-flex align-items-end">
                        <a class="text-uppercase font-weight-bold" title="Esplora <?=$titleCard?>" href="<?=$urlDetail?>">Leggi tutto →</a>
                    </div>
                </div>

                <div class="agid-person-card-img col-sm-6 col-lg-4 pl-0">
                    <img src="<?=$imageUrl?>" class="h-100 w-100 figure-img img-fluid" alt="Un'immagine generica segnaposto con angoli arrotondati in una figura.">
                </div>
                
            <?php else: ?>

            <div class="col-xs-12 agid-person-card-info d-flex flex-column">
                <a class="h5 pt-4 px-4 agid-person-card-title text-uppercase font-weight-bold primary-color" title="Vai alla scheda di <?=$titleCard?>" href="<?=$urlDetail?>"><?=$titleCard?></a>
                
                <div class="px-4 agid-person-card-role-description">
                    <?=$model->role_description?> <?=(isset($model->date_start_settlement)) ? 'in carica dal ' . Yii::$app->formatter->asDate($model->date_start_settlement, 'dd') . ' ' . ucwords(Yii::$app->formatter->asDate($model->date_start_settlement, 'MMMM')) . ' ' . Yii::$app->formatter->asDate($model->date_start_settlement, 'YYYY') : ''?>
                </div>
                
                <div class="py-4 px-4 agid-person-card-read-more flex-grow-1 d-flex align-items-end">
                    <a class="text-uppercase font-weight-bold" title="Esplora <?=$titleCard?>" href="<?=$urlDetail?>">Leggi tutto →</a>
                </div>
            </div>

            <?php endif; ?>
        </div>

    </div>
</div>
