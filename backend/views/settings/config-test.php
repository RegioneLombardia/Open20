<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use yii\bootstrap\Button;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use backend\models\Settings;

/**
 * @var yii\web\View $this
 */

$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosplatform', 'Admin'), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosplatform', 'Platform Configurator'), 'url' => ['/admin/settings']];
//$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = AmosAdmin::t('amosplatform', 'Configuration Test');
?>


<hr>

<h3><?= AmosAdmin::t('amosplatform', $result ? 'Success' : 'Failed'); ?></h3>

<hr>