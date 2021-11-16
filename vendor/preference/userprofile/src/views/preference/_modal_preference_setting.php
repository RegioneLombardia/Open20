<?php

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\design\assets\BootstrapItaliaDesignAsset;
use preference\userprofile\models\PreferenceChannel;
use preference\userprofile\utility\TopicTagUtility;
use preference\userprofile\utility\UserInterestTagUtility;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

?>

<div class="modal modal-border-radius fade" id="modal-preference-setting-id" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <img alt="icona tematica" class="mr-3" src="<?= '/img/icone-tematiche/' . $tag->getIcon() ?>">
        <h3 class="modal-title" id="settingsModalLabel"><?= $tag->nome ?></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pb-4">
        <div class="text-container d-flex flex-column  pb-4">
          <p class="p-2 pb-4"><?= $tag->descrizione ?></p>
        </div>
        <?php
        $form = ActiveForm::begin([
          'action' => Url::to(['settings']),
          'options' => [
            'id' => 'preference-setting-form-id',
            'data-fid' => 0,
            'data-field' => '',
            'data-entity' => '',
            'enctype' => 'multipart/form-data',
          ],
        ]);
        ?>
        <div class="gestione-canali d-flex flex-column  pb-4">
          <p class="font-weight-bold">Gestisci canali di contatto</p>
          <p class="pb-2 text-muted">Tramite quali canali vuoi rimanere informato? </p>

          <input type="hidden" name="UserChannel[tag_id]" value="<?= $tag->id ?>">
          <input type="hidden" name="UserChannel[user_id]" value="<?= Yii::$app->user->id ?>">
          <input type="hidden" name="target" value="<?= $tagRoot->codice ?>">
          <div class="canali  mt-3">
            <?php
            foreach (ArrayHelper::map(UserInterestTagUtility::getAllChannels(), 'id', 'title') as $key => $value) :
              $checked = in_array($key, UserInterestTagUtility::getUserChannelsIds($tag, Yii::$app->user->id)) ? 'checked' : '';
            ?>
              <div class="form-check form-check-group">
                <div class="toggles">
                  <label for="checkbox-<?= $key ?>" class="d-flex flex-wrap h-auto small align-items-center">
                    <div class="btn-icon d-block mr-2">
                      <span class="rounded-icon border border-secondary rounded-circle mx-auto p-1">
                        <?php
                          if ($key == PreferenceChannel::SMS_ID): 
                        ?> 
                          <svg class="icon icon-secondary" role="img" aria-label="Icona per gestire la preferenza">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#message-text"></use>
                          </svg>
                        <?php
                          endif;
                          if ($key == PreferenceChannel::NEWSLETTER_ID):
                        ?> 
                          <svg class="icon icon-secondary" role="img" aria-label="Icona per gestire la preferenza">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#email"></use>
                          </svg>
                        <?php
                          endif;
                          if ($key == PreferenceChannel::APP_ID):
                          ?> 
                            <svg class="icon icon-secondary" role="img" aria-label="Icona per gestire la preferenza">
                              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#cellphone"></use>
                            </svg>
                          <?php
                            endif;
                          ?>

                      </span>
                    </div>
                    <?= $value ?>
                    <div class="ml-auto">
                      <?php
                      $fequency = TopicTagUtility::getFrequencyByChannelAndTopicCode($key, $tag->codice);
                      ?>
                      <small class="text-muted mr-3"><?= (!empty($fequency)) ? $fequency->title : '' ?></small>
                      <?php
                        if ($key == PreferenceChannel::APP_ID):
                        ?>
                          <input disabled id="checkbox-disabled" type="checkbox" name="UserChannel[channels][]" value="<?= $key ?>" <?= $checked ?>>
                          <?php
                          else:
                          ?>                      
                          <input id="checkbox-<?= $key ?>" type="checkbox" name="UserChannel[channels][]" value="<?= $key ?>" <?= $checked ?>>
                          <?php
                        endif;
                      ?>
                      <span class="lever ml-auto"></span>
                    </div>
                  </label>
                </div>
              </div>

            <?php
            endforeach;
            ?>
          </div>

        </div>

        <div class="input-button-container d-flex pb-4">
          <button type="submit" class="btn btn-primary px-5  ml-2 d-flex justify-content-center m-auto">Salva canali di contatto</button>
        </div>
        <?php ActiveForm::end(); ?>
      </div>
      <?php
      if (isset($disableTopic) && ($disableTopic == true)) :
      ?>
        <div class="modal-footer justify-content-center justify-content-md-between text-center text-md-left flex-wrap lightgrey-bg-c1 border-top p-4">
          <p class="m-0 text-muted">Non vuoi più ricevere informazioni su questo argomento?</p>
          <div class="mt-1">
            <svg class="icon icon-sm icon-primary mr-1">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#trash-can-outline"></use>
            </svg>
            <small>
              <?php
              echo Html::a('<strong>Disattiva preferenza</strong>', '#', ['data-tagid' => $tag->id, 'id' => 'modal-disattiva-preferenze-anchor-id']);
              ?>
            </small>
          </div>

        </div>
      <?php
      endif
      ?>

    </div>
  </div>
</div>

<div class="modal fade modale-conferma" id="sure-preference-removing-id" tabindex="-1" role="dialog" aria-labelledby="removeConfermaModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h3 class="modal-title" id="removeConfermaModalLabel">Sei sicuro di voler disattivare la tua preferenza?</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pb-4">
        <div class="row pb-4 text-center text-md-left align-items-center">
          <div class="col-md-4">
            <img alt="icona messaggio" class="mr-3" src="/img/messaggio-alert.svg">
          </div>
          <div class="col-md-8">
            <p class="p-2">Eliminando la tematica "<?= $tag->nome ?>" dalle tue preferenze non riceverai più alcuna comunicazione associata a questo argomento.
              <br />Sei sicuro di voler continuare?</p>
          </div>

        </div>
        <div class="input-button-container d-flex flex-wrap flex-sm-nowrap justify-content-center pb-4">
          <button type="button" class="btn btn-outline-secondary w-100 w-sm-25 m-2" id="sure-preference-removing-annulla">Annulla</button>
          <button type="button" class="btn btn-primary w-100 w-sm-75 m-2" id="sure-preference-removing-conferma">Disattiva</button>
        </div>
      </div>

    </div>
  </div>
</div>