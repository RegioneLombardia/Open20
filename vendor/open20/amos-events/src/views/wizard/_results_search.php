<?php

use open20\amos\events\AmosEvents;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\events\models\search\UserEventSearch;

$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
?>

<div id="form-results" class="mb-5 p-4 neutral-1-bg-a1">
    <div class="row">
        <div class="col-md-6">
            <div class="my-4 d-flex">
                <div class="mr-2">
                    <svg class="icon">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_mail"></use>
                    </svg>
                </div>

                <h5 class="font-weight-bold ">
                <?= AmosEvents::t('amosevents', 'Risultati della ricerca') ?>
                </h5>
            </div>
            
            <?php if ($modelSearch->type == UserEventSearch::SEARCH_TYPE_ALL) {
                $typeSearchText = AmosEvents::t('amosevents', 'Hai cercato tutti gli utenti');
            } else {
                $typeSearchText = AmosEvents::t('amosevents', 'Hai cercato utenti appartenenti alla lista');
            } ?>
            <p><?= $typeSearchText ?></p><br>
            <!--        <p>--><?php //AmosEvents::t('amosevents', 'Con le seguenti preferenze anagrafiche:'); 
                                ?>
            <!--</p>-->

        </div>
        <div id="save-inputs" class="col-md-6">
            <p class="text-danger m-t-20">
                <strong><?= AmosEvents::t('amosevents', "Sono stati trovati: {n} UTENTI", ['n' => $count]) ?></strong></p>
            <div>
                <div>
                    <?= $form->field($internalList, 'name')->textInput(['placeholder' =>  AmosEvents::t('amosevents', 'Assegna un titolo alla tua ricerca')])->label(AmosEvents::t('amosevents', 'Titolo della ricerca')) ?>
                </div>
                <?php if (!empty($saveAjax)) { ?>
                    <div class="col-sm-6 mt-2">
<!--                        --><?php //\yii\helpers\Html::a(AmosEvents::t('amosevents', "Save"), '', ['class' => 'btn btn-primary pull-left', 'id' => 'save-list']) ?>
                    </div>
                <?php } ?>
            </div>
            <div style="display:none">
                <?= $form->field($internalList, 'event_id')->hiddenInput() ?>
                <?= $form->field($internalList, 'query_sql')->hiddenInput() ?>
                <?= $form->field($internalList, 'active_query')->hiddenInput() ?>
                <?= $form->field($internalList, 'search_params')->hiddenInput(['id' => 'result-search-params']) ?>
                <?= $form->field($internalList, 'n_user_found')->hiddenInput() ?>
            </div>
        </div>
    </div>
</div>