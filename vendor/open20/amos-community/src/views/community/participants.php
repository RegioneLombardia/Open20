<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\community\widgets\CommunityMembersWidget;

$this->title = AmosCommunity::t('amoscommunity', 'Participants');
$this->params['breadcrumbs'][] = ['label' => AmosCommunity::t('amoscommunity', 'Community'), 'url' => ['join', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

echo CommunityMembersWidget::widget([
    'model' => $model,
    'targetUrlParams' => [
        'viewM2MWidgetGenericSearch' => true,
    ],
    'checkManagerRole' => true,
    'targetUrlInvitation' => $targetUrlInvitation,
    'invitationModule' => $invitationModule,
]);
