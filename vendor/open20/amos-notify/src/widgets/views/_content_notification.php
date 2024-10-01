<?php

use open20\amos\admin\models\UserProfile;
use open20\amos\notificationmanager\AmosNotify;
use yii\helpers\Html;

$notifyModule = Yii::$app->getModule('notify');
$modelsEnabled = [];
if ($notifyModule) {
    $modelsEnabled = $notifyModule->notificationConfContentEnabled;
}

if (!empty($modelsEnabled)) {
    $userId = UserProfile::findOne(Yii::$app->request->get('id'))->user_id;
    $notificationConf = \open20\amos\notificationmanager\models\NotificationConf::find()
        ->andWhere(['user_id' => $userId])->one();
    $values = [];
    $n_confs = 0;
    $notifications_enabled = false;
    // Load fields
    if ($notificationConf) {
        $notifications_enabled = $notificationConf->notifications_enabled;
        foreach ($notificationConf->notificationConfContents as $conf) {
            //$values[$conf->models_classname_id]['push'] = $conf->push_notification;
            $values[$conf->models_classname_id]['email'] = $conf->email;
            $n_confs++;
        }
    } ?>
    <div class="col-md-12">
        <?php foreach ($modelsEnabled as $classname) {
            $grammar = null;
            $modelClassname = \open20\amos\core\models\ModelsClassname::find()->andWhere(['classname' => $classname])->one();
            $object = new $classname;
            if ($object) {
                $grammar = $object->getGrammar();
            }

            if ($modelClassname && $grammar) { ?>
                <div class="row">
                    <div class="form-group col-xs-12">
                        <label class="control-label"> <?= \Yii::t('app', "Vuoi ricevere notifiche relative alla pubblicazione di nuove {x} nelle email di riepilogo?", [
                                'x' => $grammar->getModelLabel()
                            ]) ?>
                        </label>
                        <?= Html::radioList(
                            "notificationContent[$modelClassname->id][email]",
                            ($n_confs > 0) ? ((!empty($values[$modelClassname->id]['email'])) ? $values[$modelClassname->id]['email'] : 0) : 1,
                            [
                                1 => AmosNotify::t('amosnotify', 'Si'),
                                0 => AmosNotify::t('amosnotify', 'No')
                            ],
                            [
                                'id' => 'notificationContent',
                            ]
                        ) ?>
                    </div>
                </div>
                <?= \yii\helpers\Html::hiddenInput("notificationContent[$modelClassname->id][enable]", true) ?>
            <?php }
        } ?>
    </div>
<?php } ?>