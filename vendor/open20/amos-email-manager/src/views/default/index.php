<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

use open20\amos\dashboard\widgets\DashboardWidget;
use open20\amos\emailmanager\AmosEmail;

echo DashboardWidget::widget(
    ['title' => AmosEmail::t('amosemail', 'Email manager')]
);
