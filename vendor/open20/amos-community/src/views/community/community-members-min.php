<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community
 * @category   CategoryName
 */

 echo \open20\amos\community\widgets\mini\CommunityMembersMiniWidget::widget([
    'model' => $model,
    'targetUrlParams' => [
        'viewM2MWidgetGenericSearch' => true
    ],
    'isUpdate' => $isUpdate
]);


?>