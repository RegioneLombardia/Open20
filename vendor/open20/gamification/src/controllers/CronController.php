<?php

namespace open20\gamification\controllers;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\commands
 * @category   CategoryName
 */

use DateTime;
use open20\gamification\models\Gamification;
use yii\console\Controller;

class CronController extends Controller {

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    public function actionUpdateStatusGamification() {
        $oggi = new DateTime();
        $dataOggi = $oggi->format('Y-m-d H:i:s');

        $allOpeningGamification = Gamification::find()
            ->andWhere(['status' => Gamification::GAMIFICATION_WORKFLOW_STATUS_OPENING])
            ->andWhere(['<=', 'finish_date', $dataOggi])
            ->all();

        foreach($allOpeningGamification as $gamification) {
            $gamification->status = Gamification::GAMIFICATION_WORKFLOW_STATUS_CONCLUDED;
            $gamification->save(false);
        }
    }
}