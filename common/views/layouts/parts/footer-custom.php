<?php

use open20\amos\admin\AmosAdmin;

$this->render('modal-delete-user-profile');
?>

<div class="modal fade modale-conferma" id="profile-delete-id" role="dialog" aria-labelledby="confermaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h3 class="modal-title" id="confermaModalLabel">Sei sicuro di voler eliminare il tuo profilo?</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" title="<?= AmosAdmin::t('amosadmin', 'Chiudi') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-4">
                <div class="icon-text-container d-flex pb-4">
                    <img class="mr-3" alt="icona messaggio" src="<?= $bootstrapItaliaAsset->baseUrl ?>/img/elimina-profilo.svg">
                    <p class="p-2 w-50">Non riceverai più alcuna comunicazione da Regione Lombardia. I tuoi dati e le tue preferenze saranno cancellati</p>
                </div>
                <div class="input-button-container flex-wrap flex-sm-nowrap d-flex pb-4 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-5 m-2 d-flex justify-content-center" id="cancel-profile-delete-id" title="<?= AmosAdmin::t('amosadmin', 'Annulla') ?>">Annulla</button>
                    <button type="button" class="btn btn-primary px-5 w-75 m-2 d-flex justify-content-center" id="ok-profile-delete-id" title="<?= AmosAdmin::t('amosadmin', 'Elimina profilo') ?>">Elimina profilo</button>
                </div>
            </div>
        </div>
    </div>
</div>



<footer id="footer" class="it-footer-preference">
    <div class="it-footer-main py-5 bg-tertiary">
        <div class="container noprint">
            <div class="row">
                <div class="col-md-12">
                    <div>
                        <div class="row">
                            <div class="col-md-12 colonna-menu">
                                <div class="link-list-wrapper">
                                    <!-- < ?= $cmsFooterMenu ?> -->
                                    <ul class="link-list">
                                        <li>
                                            <a class="list-item" href="/it/credits" title="Credits" target="_self">Credits</a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="/it/privacy-policy" title="Privacy Policy" target="_self">Privacy Policy</a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="/it/cookie-policy" title="Cookie Policy" target="_self">Cookie Policy</a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="/it/note-legali" title="Note Legali" target="_self">Note Legali</a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="/preferenceuser/contacts/contacts" title="Assistenza tecnica" target="_self">Assistenza tecnica</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- <div class="col-md-5">
                                <div>
                                    <div class="d-flex justify-content-md-end link-list-wrapper footer-social">
                                        <ul class="footer-list link-list clearfix">
                                            <li>
                                                <a href="https://www.facebook.com/Regione.Lombardia.official/?fref=ts" class="social-icon" title="Vai alla pagina Facebook" aria-label="Vai alla pagina Facebook" target="_blank">
                                                    <svg class="bg-white icon icon-lg icon-tertiary icon-padded rounded-circle">
                                                        <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-facebook"></use>
                                                    </svg>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://twitter.com/RegLombardia" class="social-icon" title="Vai alla pagina Twitter" aria-label="Vai alla pagina Twitter" target="_blank">
                                                    <svg class="bg-white icon icon-lg icon-tertiary icon-padded rounded-circle">
                                                        <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-twitter"></use>
                                                    </svg>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.linkedin.com/company/regione-lombardia/" class="social-icon" title="Vai alla pagina Linkedin" aria-label="Vai alla pagina Linkedin" target="_blank">
                                                    <svg class="bg-white icon icon-lg icon-tertiary icon-padded rounded-circle">
                                                        <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-linkedin"></use>
                                                    </svg>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.youtube.com/user/regionelombardia" class="social-icon" title="Vai alla pagina YouTube" aria-label="Vai alla pagina YouTube" target="_blank">
                                                    <svg class="bg-white icon icon-lg icon-tertiary icon-padded rounded-circle">
                                                        <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-youtube"></use>
                                                    </svg>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div> -->
                            <!--<div class="col-md-3">
                                <div class="link-list-wrapper">
                                    <ul class="footer-list link-list clearfix">
                                         FOOTER 
                                    </ul>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>

            </div>
            <div role="contentinfo" aria-label="Copyleft" class="pt-2">
                <div class="row">
                    <div class="col-lg-12">
                        <p dir="ltr">© Copyleft Regione Lombardia tutti i diritti Riservati CF 80050050154 - Piazza Città di Lombardia, 1 20124 Milano v.8.3.04-278156-06052019</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php
$socialModule = \Yii::$app->getModule('social');
if (!empty($socialModule) && class_exists('\kartik\social\GoogleAnalytics')) :
    if (YII_ENV_PROD && !empty($socialModule->googleAnalytics)) :
        echo \kartik\social\GoogleAnalytics::widget([]);
    endif;
endif;
?>