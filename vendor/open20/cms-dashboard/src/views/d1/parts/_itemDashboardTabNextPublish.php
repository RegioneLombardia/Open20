<?php

use app\modules\cms\models\Nav;
use open20\cms\dashboard\Module;
use open20\cms\dashboard\utilities\Utility;
use open20\design\utility\DateUtility;
use yii\helpers\Url;

$today = DateUtility::getDate();

if (!empty($model)) {
    if ($model instanceof \open20\amos\news\models\News) {
        $title = $model->titolo;
        $url = $model->getFullViewUrl();
        $modelLabel = strtolower($model->getGrammar()->getModelLabel());
        $status = $model->getWorkflowStatus()->getLabel();

        $dataCreazione = DateUtility::getDate($model->created_at);
        $oraCreazione = DateUtility::getHour($model->created_at, 'php:H:i');
        $creatore = $model->createdUserProfile->nomeCognome;

        $dataPubblicazione = DateUtility::getDate($model->data_pubblicazione);

        if($dataPubblicazione == $today){
            $dataPubblicazione = '<strong>OGGI</strong>';
        } else {
            $dataPubblicazione = '<strong>' . $dataPubblicazione . '</strong>';
        }

        $oraPubblicazione = DateUtility::getHour($model->data_pubblicazione, 'php:H:i');

    }
    else {

        $title = $model['title'];

        $link = Utility::getLinkTarget($model);
        $onlineVersion = Utility::getOnlineVersion($model['id']);
        $url = Url::to(['/cms-page-preview', 'itemId' => $model['id'], 'version' => (!empty($onlineVersion['id']) ? $onlineVersion['id'] : 1)]);
        if (!empty($link)) {
            $url = $link;
        }
        $modelLabel = strtolower(Module::txt('pagina'));
        $status = ($model['nav']['is_offline'] ? Module::txt('Non pubblicata') : Module::txt('Pubblicata'));

        $dataOraPubblicazione = '';
        if (empty($model['nav']['publish_from'])) {
            $dataOraPubblicazione = Module::txt('IMMEDIATA');
        } else {
            $dataPubblicazione = DateUtility::getDate($model['nav']['publish_from']);
            $oraPubblicazione = DateUtility::getHour($model['nav']['publish_from'], 'php:H:i');
        }

        $user = \open20\amos\core\user\User::find()->andWhere(['id' => $model['create_user_id']])->one();
        $creatore = Module::txt('N.d.');
        if (!empty($user)) {
            $user_profile = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => $user->id])->one();
            $creatore = $user_profile->nome . ' ' . $user_profile->cognome;
        }
    }
}


?>
<div class="row itemRowDashboardTab">
    <div class="col">
        <span class="title">
            <span class="badge badge-secondary"><?=$modelLabel?></span>
            <a class="" href="<?= $url ?>" title="<?= 'Visualizza' . ' ' . $title . ' ' . '[Apre in nuova finestra]' ?>" target="_blank"><?= $title ?></a>
            <small class="status">
                <em><?= '(' . $status . ')' ?></em>
            </small>
        </span>
    </div>
    <div class="col-md-6 text-md-right">
        <small>
            <?= 'Creato da' . ' ' . '<strong>' . $creatore . '</strong>' . ', programmata in data <strong>' . $dataPubblicazione . '</strong> alle ' .  '<strong>' . $oraPubblicazione . '</strong>' ?>
        </small>
    </div>
</div>