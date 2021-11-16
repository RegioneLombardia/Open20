<?php

use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\assets\ModuleSondaggiAsset;
use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use open20\amos\sondaggi\models\SondaggiInvitations;
use kartik\dropdown\DropdownX;

/**
 * @var Sondaggi $model
 */

ModuleSondaggiAsset::register($this);

$this->title = $model->getTitle();

$numberListTag = \Yii::$app->controller->sondaggiModule->numberListTag;

$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['sondaggi/manage']];
$this->params['breadcrumbs'][] = $this->title;

$url                            = \yii\helpers\Url::current();

$sondaggioPubblicabile = $model->verificaSondaggioPubblicabile();

if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI')) {
    $this->params['titleButtons'][] = Html::a(AmosIcons::show('delete').'&nbsp;'.AmosSondaggi::t('amossondaggi',
                '#delete_poll'),
            Yii::$app->urlManager->createUrl([
                '/'.$this->context->module->id.'/sondaggi/delete',
                'id' => $model->id,
                'url' => $url,
            ]),
            [
            'title' => AmosSondaggi::t('amossondaggi', '#delete_poll'),
            'class' => 'btn btn-danger-inverse',
            'data' => [
                'confirm' => AmosSondaggi::t('amossondaggi', '#delete_poll_dialog')
            ]
        ]);
    // controllo prima di tutto che l'utente abbia il permesso di portare il sondaggio in stato attivo o disattivo
    // qundi 1) il parametro è settatto 2) se non hai il ruolo per poter getire il pulsante ti blocco subito!
    if (!Yii::$app->controller->sondaggiModule->currentUserCanActivatePool()) {
        // due blocchi distinti, attiva Sondaggio e Disattiva sondaggio in base allo status
        if ($model->status != Sondaggi::WORKFLOW_STATUS_VALIDATO) {
            $this->params['titleButtons'][] = '<span data-toggle="tooltip" data-placement="bottom" class="m-l-5" title="' . AmosSondaggi::t('amossondaggi', '#no_role_to_activate_pool_message') . '">' . Html::a(AmosIcons::show('check-circle') . '&nbsp;' . AmosSondaggi::t('amossondaggi',
                        '#publish_poll'), '#',
                    [
                        'class' => 'btn btn-default disabled'
                    ]) . '</span>';
        } else {
            $this->params['titleButtons'][] = '<span data-toggle="tooltip" data-placement="bottom" class="m-l-5" title="' . AmosSondaggi::t('amossondaggi', '#no_role_to_deactivate_pool_message') . '">' . Html::a(AmosIcons::show('minus-circle') . '&nbsp;' . AmosSondaggi::t('amossondaggi',
                        '#depublish_poll'),'#',
                    [
                        'title' => AmosSondaggi::t('amossondaggi', '#no_role_to_deactivate_pool_message'),
                        'class' => 'btn btn-danger disabled',
                    ]) . '</span>';
        }
    } elseif ($sondaggioPubblicabile && $model->status != Sondaggi::WORKFLOW_STATUS_VALIDATO) {
        $invitations = SondaggiInvitations::find()->andWhere(['sondaggi_id' => $model->id])->andWhere(['active' => 1])->all();
        $list = '';
        foreach($invitations as $item) {
            $list .= '<ul>'.$item->name.'</ul>';
        }

        $this->params['titleButtons'][] = Html::a(AmosIcons::show('check-circle').'&nbsp;'.AmosSondaggi::t('amossondaggi',
                    '#publish_poll'),
                Yii::$app->urlManager->createUrl([
                    '/'.$this->context->module->id.'/pubblicazione/pubblica',
                    'idSondaggio' => $model->id,
                    'url' => $url,
                ]),
                [
                'title' => AmosSondaggi::t('amossondaggi', '#publish_poll'),
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => (empty($list))?
                        AmosSondaggi::t('amossondaggi', '#publish_poll_dialog'):
                        AmosSondaggi::t('amossondaggi', '#publish_poll_dialog_invitations', ['list' => $list])
                ]
        ]);
    } else if ($model->status == Sondaggi::WORKFLOW_STATUS_VALIDATO) {
        $this->params['titleButtons'][] = Html::a(AmosIcons::show('minus-circle').'&nbsp;'.AmosSondaggi::t('amossondaggi',
                    '#depublish_poll'),
                Yii::$app->urlManager->createUrl([
                    '/'.$this->context->module->id.'/pubblicazione/depubblica',
                    'idSondaggio' => $model->id,
                    'url' => $url,
                ]),
                [
                'title' => AmosSondaggi::t('amossondaggi', '#depublish_poll'),
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => AmosSondaggi::t('amossondaggi', '#depublish_poll_dialog')
                ]
        ]);
    } else {
        $this->params['titleButtons'][] = '<span data-toggle="tooltip" data-placement="bottom" title="'.AmosSondaggi::t('amossondaggi', AmosSondaggi::instance()->enableInvitationList ? '#cannot_publish_no_list' : '#cannot_publish').'">'.Html::a(AmosIcons::show('check-circle').'&nbsp;'.AmosSondaggi::t('amossondaggi',
                    '#publish_poll'), '#',
                [
                'class' => 'btn btn-default disabled'
        ]).'</span>';
    }
}
?>
<div class="sondaggi-dashboard">
    <div class="row">
        <div class="col-lg-7">

            <p><?= $model->descrizione ?></p>

            <div class="mb-0"><?=
                \open20\amos\core\forms\ListTagsWidget::widget([
                    'userProfile' => $model->id,
                    'className' => $model->className(),
                    'viewFilesCounter' => true,
                    'pageSize' => $numberListTag,
                ]);
                ?></div>

        </div>
        <div class="col-lg-5">
            <?php if ($model->file) : ?>
                <div class="preview-landing position-relative <?= $class ?> h-auto">
                    <img src="<?= ($model->file->getWebUrl()) ?>" class="img-fluid w-100">

                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="m-t-20 p-t-20 p-b-20 p-l-20 p-r-20 bg-light">
        <div class="row">
            <div class="col-lg-8">
                <!-- <div class="mr-1">
                    <svg class="icon">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_web"></use>
                    </svg>
                </div> -->
                <p class="mt-0"><strong class="text-uppercase text-success"><?= Amosicons::show('calendar-check') ?>&nbsp;<?=
                        AmosSondaggi::t('amossondaggi', '#publication_date').': '
                        ?></strong><?= \Yii::$app->formatter->asDate($model->publish_date)
                        ?></p>
                <?php if (!empty($model->close_date)) : ?>
                    <p class="mt-0"><strong class="text-uppercase text-danger"><?= Amosicons::show('calendar-close') ?>&nbsp;<?=
                            AmosSondaggi::t('amossondaggi', '#closing_date').': '
                            ?></strong><?= \Yii::$app->formatter->asDate($model->close_date) ?></p>
                <?php endif; ?>
                <!-- <div class="mr-1">
                    <svg class="icon">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_web"></use>
                    </svg>
                </div>
                <p class="mt-0"><?=
                AmosSondaggi::t(
                    'amossondaggi', '<strong>Questionario {type}</strong>',
                    ['type' => \open20\amos\sondaggi\models\base\SondaggiTypes::getLabels()[$model->sondaggio_type]]
                )
                ?></p> -->
            </div>
            <div class="col-lg-4">
                <a class="btn btn-secondary btn-block m-t-5" href="/sondaggi/sondaggi/risultati?id=<?= $model->id ?>" target="_blank"><?=
                    Amosicons::show('dashboard', [], 'dash')
                    ?>&nbsp;<?= AmosSondaggi::t('amossondaggi', '#monitoring') ?></a>
                <?php
                    if ($sondaggioPubblicabile) {
                        echo Html::a(Amosicons::show('eye') . '&nbsp;' . AmosSondaggi::t('amossondaggi', '#preview'),
                            Yii::$app->urlManager->createUrl([
                                '/' . $this->context->module->id . '/pubblicazione/preview',
                                'id' => $model->id,
                                'url' => \Yii::$app->request->url
                            ]),
                            [
                                'title' => AmosSondaggi::t('amossondaggi', '#preview'),
                                'class' => 'btn btn-secondary btn-block m-t-5'
                            ]);
                    } else {
                        echo Html::a(Amosicons::show('eye') . '&nbsp;' . AmosSondaggi::t('amossondaggi', '#preview'),
                            '#',
                            [
                                'title' => AmosSondaggi::t('amossondaggi', '#no_preview_messagge'),
                                'class' => 'btn btn-secondary btn-block m-t-5',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'disabled' => ''
                            ]);
                    }
                    ?>
                <?php if ($user->can('AMMINISTRAZIONE_SONDAGGI'))
                echo Html::a(Amosicons::show('copy').'&nbsp;'.AmosSondaggi::t('amossondaggi', '#duplicate'),
                    Yii::$app->urlManager->createUrl([
                        '/'.$this->context->module->id.'/dashboard/clone',
                        'id' => $model->id,
                        'url' => $url,
                    ]),
                    [
                    'title' => AmosSondaggi::t('amossondaggi', '#duplicate'),
                    'class' => 'btn btn-secondary btn-block m-t-5',
                    'data' => [
                        'confirm' => AmosSondaggi::t('amossondaggi', '#duplicate_poll_dialog')
                    ]
                ]);
                ?>
                <?php
                if (AmosSondaggi::instance()->enableInvitationList) {
                    echo Html::a(Amosicons::show('group', [], 'dash') . '&nbsp;' . AmosSondaggi::t('amossondaggi', '#view_compilations'),
                        Yii::$app->urlManager->createUrl([
                            '/' . $this->context->module->id . '/dashboard/compilations',
                            'id' => $model->id,
                            'url' => $url,
                        ]),
                        [
                            'title' => AmosSondaggi::t('amossondaggi', '#view_compilations'),
                            'class' => 'btn btn-secondary btn-block m-t-5'
                        ]);
                }
                ?>
                <?php echo Html::tag('a', Amosicons::show('table', [], 'dash').'&nbsp;'.AmosSondaggi::t('amossondaggi', '#download_participants'), [
                   'id' => 'downloadMenuButton',
                   'title' => AmosSondaggi::t('amossondaggi', '#download_participants'),
                   'class' => 'btn btn-secondary btn-block dropdown-toggle m-t-5',
                   'data-toggle' => 'dropdown',
                   'aria-haspopup' => 'true',
                   'aria-expanded' => 'false'
                ]);
                echo DropdownX::widget([
                    'items' => [
                        [
                            'label' => 'Excel',
                                'url' => Yii::$app->urlManager->createUrl([
                                '/'.$this->context->module->id.'/sondaggi/extract-sondaggi',
                                'type' => 'xls',
                                'id' => $model->id,
                                'url' => $url,
                            ])
                        ],
                        [
                            'label' => 'PDF',
                                'url' => Yii::$app->urlManager->createUrl([
                                '/'.$this->context->module->id.'/sondaggi/extract-sondaggi',
                                'type' => 'pdf',
                                'id' => $model->id,
                                'url' => $url,
                            ])
                        ]
                    ]
                ]);
            ?>

            </div>
        </div>

    </div>
    <!-- <div class="row">
        <div class="col-12">
            <h2 style="display: inline-block;"><?= AmosSondaggi::t('amossondaggi', '#invitation_status') ?></h2><hr style="display: inline-block;">
        </div>
    </div> -->
    <div class="sondaggi-report-container">
        <div class="row">
            <div class="col-md-6">
                <?php
                if (\Yii::$app->controller->sondaggiModule->forceOnlyFrontend) {
                    $partecipazioni = $model->getNumeroPartecipazioni();
                } else {
                //partecipazioni totali senza quelli senza stato
                $partecipazioni = $model->getCompilazioniStatus([SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA, SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO,
                    SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO], [0, 1]);
                }
                ?>
                <h4 class="m-t-20 p-t-20"><?= AmosSondaggi::t('amossondaggi', 'Report') ?></h4>
                <?php
                if (AmosSondaggi::instance()->hasInvitation) :
                    if (AmosSondaggi::instance()->compilationToOrganization) :
                        ?>
                        <p><strong><?= AmosSondaggi::t('amossondaggi', 'Numero enti invitati') ?> </strong><?= $model->getEntiInvitati()->count() ?></p>
                        <?php
                    else:
                        ?>
                        <p><strong><?= AmosSondaggi::t('amossondaggi', 'Numero persone invitate') ?> </strong><?= $model->getElementsInvitated()->count() ?></p>
                        <?php
                    endif;
                endif;
                ?>

                <?php
                if (AmosSondaggi::instance()->compilationToOrganization) :
                    ?>
                    <p><strong><?= AmosSondaggi::t('amossondaggi', 'Numero enti che hanno compilato') ?> </strong><?= $partecipazioni ?></p>
                    <?php
                else:
                    ?>
                    <p><strong><?= AmosSondaggi::t('amossondaggi', 'Numero di persone che hanno compilato') ?> </strong><?= $partecipazioni ?></p>
                    <?php
                endif;
                ?>

                <?php
                if (AmosSondaggi::instance()->enableCompilationWorkflow) :
                ?>
                <h5 class="m-t-30"><?= AmosSondaggi::t('amossondaggi', 'Compilazioni inviate') ?> </h5>
                <p class="m-t-0"> <strong> <?= AmosSondaggi::t('amossondaggi', 'Enti:') . '</strong>'.' '.$model->getCompilazioniStatus(SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO, 1).' '.AmosSondaggi::t('amossondaggi', 'su').' '.$partecipazioni ?></p>
                <h5 class="m-t-30"><?= AmosSondaggi::t('amossondaggi', 'Compilazioni non inviate') ?> </h5>
                <p class="m-t-0"><strong> <?= AmosSondaggi::t('amossondaggi', 'Utenti:') . '</strong>'.' '.$model->getCompilazioniStatus([SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA,SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO]).' '.AmosSondaggi::t('amossondaggi', 'su').' '.$partecipazioni ?></p>
                <?php
                endif;
                ?>
            </div>
        </div>
    </div>
</div>
