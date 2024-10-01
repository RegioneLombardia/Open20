<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\widgets\ConnectToUserWidget;
use open20\amos\admin\widgets\SendMessageToUserWidget;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\community\AmosCommunity;


use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\admin\widgets\graphics\WidgetGraphicsUsers;
use open20\amos\organizzazioni\models\ProfiloUserMm;

/**
 * @var yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 */

$model = $model->user->userProfile;
$userId = $model->user_id;
$appController = Yii::$app->controller;
$adminModule = AmosAdmin::instance();
$nameModuleAdmin = AmosAdmin::getModuleName();

echo \open20\amos\admin\widgets\UserCardWidget::widget(['model' => $model]);
echo \luya\yii\helpers\Html::a($model->nomeCognome, ["/$nameModuleAdmin/user-profile/view", 'id' => $model->id],[
    'title' => AmosCommunity::t('amoscommunity','Vai al profilo di {name}', ['name' => $model->nomeCognome])
]);
