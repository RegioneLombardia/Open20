<?php

use open20\amos\audit\Audit;
use open20\amos\audit\components\Access;

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var open20\amos\audit\models\AuditEntry $entry */

if ($auditEntry = Audit::getInstance()->getEntry()) {
    if (!isset($style)) {
        $style = YII_DEBUG ? '' : 'color:transparent;';
    }
    if (Access::checkAccess()) {
        echo Html::a('audit-' . $auditEntry->id, ['/audit/entry/view', 'id' => $auditEntry->id], ['style' => $style]);
    } else {
        echo Html::tag('span', 'audit-' . $auditEntry->id, ['style' => $style]);
    }
}
