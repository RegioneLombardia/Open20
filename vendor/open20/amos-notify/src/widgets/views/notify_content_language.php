<?php
/**
 * @var $defaultLanguage string
 * @var $widget \open20\amos\notificationmanager\widgets\NotifyContentLanguageWidget
 */
?>
<div id="<?= $widget->id ?>" class="<?= $widget->class ?>">
    <div class="row">
        <label class="control-label"><?= \open20\amos\notificationmanager\AmosNotify::tHtml('amosnotify', 'Lingua del contenuto') ?></label>
        <?php
        echo \kartik\select2\Select2::widget([
            'id' => 'notify_content_language-id',
            'name' => 'notify_content_language',
            'data' => \yii\helpers\ArrayHelper::map(\lajax\translatemanager\models\Language::find()->andWhere(['status' => 1])->all(), 'language_id', 'language'),
            'value' => $value
        ]);
        ?>
    </div>
</div>