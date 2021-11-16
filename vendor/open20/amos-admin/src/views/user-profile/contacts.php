<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */


echo \open20\amos\admin\widgets\UserContacsWidget::widget([
    'userId' => $model->user_id,
    'isUpdate' => $isUpdate
]);


?>