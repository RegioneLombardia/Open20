<?php

use open20\amos\community\AmosCommunity;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;

?>
<div class="community-legenda callout callout-info m-b-30">
    <div class="callout-title">
        <span><?= AmosCommunity::t('amoscommunity', 'Legenda') ?></span>
    </div>
    <div class="flexbox flexbox-row flexbox-wrap">
        <div class="community-status community-open">
            <p>
                <?=
                AmosIcons::show('lock-open') . ' ' .
                    AmosCommunity::t('amoscommunity', 'Community aperta')
                    // .
                    // Html::a(
                    //     AmosIcons::show('help-outline'),
                    //     'javascript:void(0)',
                    //     [
                    //         'class' => 'legenda-tooltip m-l-15',
                    //         'data-toggle' => 'tooltip',
                    //         'title' => AmosCommunity::t('amoscommunity', 'Una community aperta permette la visione dei contenuti pubblicati al suo interno a tutti i partecipanti della piattaforma e la notifica ai soli iscritti alla community. La community sarà visibile nei listati e chiunque potrà iscriversi senza alcun flusso di approvazione.')
                    //     ]
                    // );
                ?>
            </p>
        </div>
        <div class="community-status community-private">
            <p><?= AmosIcons::show('lock-outline') . ' ' . AmosCommunity::t('amoscommunity', 'Community riservata ai partecipanti') ?></p>
        </div>
        <div class="community-status community-closed">
            <p><?= AmosIcons::show('eye-off') . ' ' . AmosCommunity::t('amoscommunity', 'Community ristretta ai partecipanti') ?></p>
        </div>
    </div>
</div>