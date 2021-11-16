<?php

/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MoodleHelper
 *
 */

namespace open20\amos\moodle\helpers;

use Yii;

class MoodleHelper {
    public static function getMoodleOAuthLink($url) {
        return Yii::$app->getModule('moodle')->moodleOAuthUrl . "?wantsurl=".rawurlencode($url);
    }
}
