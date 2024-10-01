<?php

use open20\amos\admin\models\UserProfile;
use frontend\modules\metrics\models\Operators;

/**
 * @var $this \yii\web\View
 * @var $model array
 *
 */

$userProfile = UserProfile::findOne(['user_id' => $model['user_id']]);
?>

<div class="item-list-report">
    <div class="row">
        <div class="col-sm-4">
            <div class="user-report">
                <?php
                echo \open20\amos\admin\widgets\UserCardWidget::widget(['model' => $userProfile, 'avatarDimension' => 'table_small', 'avatarXS' => true])
                ?>
                <div>
                    <strong><?= $model['nome'] . ' ' . $model['cognome']; ?></strong>
                    <div><span><?= $model['profile'] ?></span></div>
                </div>
            </div>

        </div>
        <div class="col-sm-6 col-info-report">
            <div class="date-report">
                <?php
                if (!empty($userProfile->start_service_date)) :
                ?>
                    <strong>In servizio dal:</strong>
                    <?php
                    echo Yii::$app->formatter->asDate($userProfile->start_service_date, 'php:d/m/Y');
                    ?>
                <?php
                endif;
                ?>
                <?php
                if (!empty($userProfile->end_service_date)) :
                ?>
                    <strong>al</strong>
                    <?php
                    echo Yii::$app->formatter->asDate($userProfile->end_service_date, 'php:d/m/Y');
                    ?>
                <?php
                endif;
                ?>
            </div>
            <div class="info-contenuti">
                <?php
                foreach (array_keys(Operators::TYPES_LABELING) as $type) :
                    $val = (isset($model[$type]) && !empty($model[$type])) ? intval($model[$type . '_number']) : 0;
                    $label = ($val > 0) ? Operators::TYPES_LABELING[$type]['plural'] : Operators::TYPES_LABELING[$type]['singular'];
                ?>
                    <span>
                        <strong><?= $label ?>:</strong> <?= $val ?>
                    </span>
                <?php
                endforeach;
                ?>
            </div>

        </div>
        <div class="col-sm-2 text-right">
            <?php
            $qParams = Yii::$app->request->getQueryParams();
            $qParams['Operators']['operators'] = [
                0 => $userProfile->user_id
            ];
            $qParams['uid'] = $userProfile->user_id;
            $toRoute[] = '/metrics/operators/operator-detail';
            foreach ($qParams as $key => $val) {
                $toRoute[$key] = $val;
            }
            ?>
            <a class="btn btn-xs btn-primary" href="<?= \yii\helpers\Url::toRoute($toRoute); ?>">Dettagli</a>
        </div>
    </div>
</div>