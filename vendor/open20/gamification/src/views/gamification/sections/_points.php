<?php
use open20\amos\core\helpers\Html;

if (\Yii::$app->controller->action->id == 'create') { ?>
    <div class="row">
        <div class="col-sm-12">
            <?= Html::tag('h3', 'Utente', ['class' => 'h4']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'utente_iscrizione')->textInput(['maxlength' => true, 'value' => '200']) ?>
        </div>

        <div class="col-sm-4">
            <?= $form->field($model, 'utente_foto')->textInput(['maxlength' => true, 'value' => '150']) ?>
        </div>

        <div class="col-sm-4">
            <?= $form->field($model, 'utente_colleg')->textInput(['maxlength' => true, 'value' => '10']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= Html::tag('h3', 'Community', ['class' => 'h4  m-t-10']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'community_creazione')->textInput(['maxlength' => true, 'value' => '100']) ?>
        </div>

        <div class="col-sm-4">
            <?= $form->field($model, 'community_iscrizione')->textInput(['maxlength' => true, 'value' => '20']) ?>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= Html::tag('h3', 'Notizie', ['class' => 'h4  m-t-10']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'notizie_creazione')->textInput(['maxlength' => true, 'value' => '50'])->label(Yii::t('gamification', 'Creazione di una notizia').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="La notizia può essere di piattaforma o di community"></span>') ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'notizie_commento')->textInput(['maxlength' => true, 'value' => '20'])->label(Yii::t('gamification', 'Commento ad una notizia').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="La notizia può essere di piattaforma o di community"></span>') ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'notizie_like')->textInput(['maxlength' => true, 'value' => '20'])->label(Yii::t('gamification', 'Like ad una notizia').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="La notizia può essere di piattaforma o di community"></span>') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= Html::tag('h3', 'Documenti', ['class' => 'h4 m-t-10']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'documenti_creazione')->textInput(['maxlength' => true, 'value' => '50'])->label(Yii::t('gamification', 'Creazione di un documento').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="Il documento può essere di piattaforma o di community"></span>') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= Html::tag('h3', 'Discussioni', ['class' => 'h4 m-t-10']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'discussioni_creazione')->textInput(['maxlength' => true, 'value' => '100'])->label(Yii::t('gamification', 'Creazione di una discussione').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="La discussione può essere di piattaforma o di community"></span>') ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'discussioni_commento')->textInput(['maxlength' => true, 'value' => '20'])->label(Yii::t('gamification', 'Commento a una discussione').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="La discussione può essere di piattaforma o di community"></span>') ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'discussioni_like')->textInput(['maxlength' => true, 'value' => '20'])->label(Yii::t('gamification', 'Like a una discussione').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="La discussione può essere di piattaforma o di community"></span>') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= Html::tag('h3', 'Sondaggi', ['class' => 'subtitle-form']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'sondaggi_partec')->textInput(['maxlength' => true, 'value' => '50']) ?>
        </div>
    </div>
<?php } else { ?>

    <div class="row">
        <div class="col-sm-12">
            <?= Html::tag('h3', 'Utente', ['class' => 'h4']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'utente_iscrizione')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-sm-4">
            <?= $form->field($model, 'utente_foto')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-sm-4">
            <?= $form->field($model, 'utente_colleg')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= Html::tag('h3', 'Community', ['class' => 'h4  m-t-10']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'community_creazione')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-sm-4">
            <?= $form->field($model, 'community_iscrizione')->textInput(['maxlength' => true]) ?>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= Html::tag('h3', 'Notizie', ['class' => 'h4  m-t-10']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'notizie_creazione')->textInput(['maxlength' => true])->label(Yii::t('gamification', 'Creazione di una notizia').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="La notizia può essere di piattaforma o di community"></span>') ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'notizie_commento')->textInput(['maxlength' => true])->label(Yii::t('gamification', 'Commento ad una notizia').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="La notizia può essere di piattaforma o di community"></span>') ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'notizie_like')->textInput(['maxlength' => true])->label(Yii::t('gamification', 'Like ad una notizia').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="La notizia può essere di piattaforma o di community"></span>') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= Html::tag('h3', 'Documenti', ['class' => 'h4 m-t-10']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'documenti_creazione')->textInput(['maxlength' => true])->label(Yii::t('gamification', 'Creazione di un documento').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="Il documento può essere di piattaforma o di community"></span>') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
                        <?= Html::tag('h3', 'Discussioni', ['class' => 'h4 m-t-10']) ?>
        </div>
        <div class="col-sm-4">
                        <?= $form->field($model, 'discussioni_creazione')->textInput(['maxlength' => true])->label(Yii::t('gamification', 'Creazione di una discussione').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="La discussione può essere di piattaforma o di community"></span>') ?>
        </div>
        <div class="col-sm-4">
                        <?= $form->field($model, 'discussioni_commento')->textInput(['maxlength' => true])->label(Yii::t('gamification', 'Commento a una discussione').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="La discussione può essere di piattaforma o di community"></span>') ?>
        </div>
        <div class="col-sm-4">
                        <?= $form->field($model, 'discussioni_like')->textInput(['maxlength' => true])->label(Yii::t('gamification', 'Like a una discussione').'<span class="am am-info m-l-5 m-r-5" data-toggle="tooltip" title="La discussione può essere di piattaforma o di community"></span>') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
                    <?= Html::tag('h3', 'Sondaggi', ['class' => 'subtitle-form']) ?>
        </div>
        <div class="col-sm-4">
                <?= $form->field($model, 'sondaggi_partec')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
<?php } ?>