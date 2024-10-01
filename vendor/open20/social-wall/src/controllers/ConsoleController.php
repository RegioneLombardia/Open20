<?php

namespace open20\socialwall\controllers;

use open20\socialwall\models\Socialwall;
use open20\socialwall\utility\Social;

/**
 * Class ConsoleController
 *
 *
 * @package open20\socialwall\controllers
 */
class ConsoleController extends \yii\console\Controller
{
    public function actionUpdateInstagramCache() {
        echo "Now caching instagram posts for accounts set...";
        Social::cacheAllTokensInstagramPosts();
    }

    public function actionUpdateSocialwallLiveCache() {
        $moduleParams = null;

        // GET MODULE PARAMS CONFIGURED OUTSIDE CONSOLE APP (check frontend first, then backend - giving priority to backend module config)
        // CHECKING FRONTEND
        if(file_exists(\Yii::$app->basePath . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "frontend" . DIRECTORY_SEPARATOR . "configs" . DIRECTORY_SEPARATOR . "modules.php")) {
            $modulesConfig = require(\Yii::$app->basePath . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "frontend" . DIRECTORY_SEPARATOR . "configs" . DIRECTORY_SEPARATOR . "modules.php");
            if(array_key_exists('socialwall', $modulesConfig)) {
                $moduleParams = $modulesConfig['socialwall'];
            }
        }
        // CHECKING BACKEND
        if(file_exists(\Yii::$app->basePath . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "backend" . DIRECTORY_SEPARATOR . "configs" . DIRECTORY_SEPARATOR . "modules.php")) {
            $modulesConfig = require(\Yii::$app->basePath . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "backend" . DIRECTORY_SEPARATOR . "configs" . DIRECTORY_SEPARATOR . "modules.php");
            if(array_key_exists('socialwall', $modulesConfig)) {
                $moduleParams = $modulesConfig['socialwall'];
            }
        }

        foreach (Socialwall::find()
                     ->andWhere(['type' => Social::LIVE_WALL])
                     ->andWhere(['update' => true])
                     ->all() as $socialwall) {
            echo "Updating contents for socialwall id " . $socialwall->id . ": ";
            if(Social::updateSocialwallLiveContents(intval($socialwall->id), $moduleParams)) {
                echo "done\n";
            } else {
                echo "skip\n";
            }
            sleep(5);
        }
    }
}