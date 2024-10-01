<?php

use open20\design\utility\DateUtility;
use open20\cms\dashboard\utilities\Utility;
use open20\cms\dashboard\Module;
use app\modules\cms\models\Nav;
use yii\helpers\Url;

$today = DateUtility::getDate();

if (!empty($model[0])) {
    $model = $model[0];
    if (strpos($key, 'news') !== false) {
        $obj = \open20\amos\news\models\News::findOne($model['id']);
        $title = $obj->titolo;

        $url = $obj->getFullViewUrl();
        $modelLabel = strtolower(Module::txt('notizia'));
        $status = $obj->getWorkflowStatus()->getLabel();

        if (strtolower($status) != 'bozza') {
            $status = false;
        }

        $dataUltimaModifica = DateUtility::getDate($obj->updated_at);

        if ($dataUltimaModifica == $today) {
            $dataUltimaModifica = '<strong>' . Module::txt('OGGI') . '</strong>';
        } else {
            $dataUltimaModifica = Module::txt('il') . ' ' . '<strong>' . $dataUltimaModifica . '</strong>';
        }

        $oraUltimaModifica = DateUtility::getHour($obj->updated_at, 'php:H:i');
        $user_profile = $obj->getUserProfileByUserId($obj->updated_by);
        $operatore = $user_profile->nome . ' ' . $user_profile->cognome;
    } else if (strpos($key, 'navitem') !== false) {
        $title = $model['title'];

        $link = Utility::getLinkTarget($model);
        $onlineVersion = Utility::getOnlineVersion($model['id']);
        $url = Url::to(['/cms-page-preview', 'itemId' => $model['id'], 'version' => (!empty($onlineVersion['id']) ? $onlineVersion['id'] : 1)]);
        if (!empty($link)) {
            $url = $link;
        }

        $nav = Nav::findOne($model['nav_id']);
        $isTypeModel = $nav->is_draft;
        if ($isTypeModel) {
            $modelLabel = strtolower(Module::txt('modello'));
        } else {
            $modelLabel = strtolower(Module::txt('pagina'));
            $status = ($nav['is_offline'] ? Module::txt('Bozza') : Module::txt(''));
            $status = Utility::getStatusPage($model['nav_id'], $model['id'], $nav['is_offline'] , true, false);/** non stampo la label pubblicata */

        }

        $dataUltimaModifica = DateUtility::getDate($model['timestamp_update']);

        if ($dataUltimaModifica == $today) {
            $dataUltimaModifica = '<strong>' . Module::txt('OGGI') . '</strong>';
        } else {
            $dataUltimaModifica = Module::txt('il') . ' ' . '<strong>' . $dataUltimaModifica . '</strong>';
        }

        $oraUltimaModifica = DateUtility::getHour($model['timestamp_update'], 'php:H:i');
        $userCms = Utility::getUserOpenFromCms($model['update_user_id']);
        $user = \open20\amos\core\user\User::find()->andWhere(['email' => $userCms['email']])->one();
        $operatore = Module::txt('N.d.');
        if (!empty($user)) {
            $user_profile = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => $user->id])->one();
            $operatore = $user_profile->nome . ' ' . $user_profile->cognome;
        }
    }
}
?>
<div class="row itemRowDashboardTab">
    <div class="col">
        <span class="title">
            <span class="badge badge-secondary"><?= $modelLabel ?></span>
            <a class="" href="<?= $url ?>" title="<?= Module::txt('Visualizza') . ' ' . $title . ' ' . '[' . Module::txt('Apre in nuova finestra') . ']' ?>" target="_blank"><?= $title ?></a>
            <?php if ($status) : ?>
                <small class="status">
                    <em><?= '(' . $status . ')' ?></em>
                </small>
            <?php endif; ?>
        </span>
    </div>
    <div class="col-md-6 text-md-right">
        <small>
            <?= 'da' . ' ' . '<strong>' . $operatore . '</strong>' . ', ' . $dataUltimaModifica . ' alle ' . '<strong>' . $oraUltimaModifica . '</strong>' ?>
        </small>
    </div>
</div>