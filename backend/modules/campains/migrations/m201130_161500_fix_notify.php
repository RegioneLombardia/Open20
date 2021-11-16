<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */

use backend\modules\campains\models\PreferenceCampainChannelMm;
use PhpParser\Node\Stmt\TryCatch;
use yii\db\Migration;
use yii\helpers\Console;

class m201130_161500_fix_notify extends Migration
{

    public function up()
    {
        $allChannel = PreferenceCampainChannelMm::find()->all();    
        foreach ($allChannel as $key => $channel) {
            try {
                $id = $channel->id;
                $this->execute("
                INSERT INTO `notification` (`id`, `user_id`, `channels`, `content_id`, `class_name`, `processed`, `models_classname_id`, `record_id`, `created_at`, `updated_at`) VALUES
                (NULL,	1,	65535,	$id,	'backend\\\\modules\\\\campains\\\\models\\\\PreferenceCampainChannelMm',	0,	NULL,	NULL,	1606748600,	1606748600),
                (NULL,	1,	58000,	$id,	'backend\\\\modules\\\\campains\\\\models\\\\PreferenceCampainChannelMm',	0,	NULL,	NULL,	1606748600,	1606748600),
                (NULL,	1,	3841,	$id,	'backend\\\\modules\\\\campains\\\\models\\\\PreferenceCampainChannelMm',	0,	NULL,	NULL,	1606748600,	1606748600),
                (NULL,	1,	3840,	$id,	'backend\\\\modules\\\\campains\\\\models\\\\PreferenceCampainChannelMm',	0,	NULL,	NULL,	1606748600,	1606748600),
                (NULL,	1,	240,	$id,	'backend\\\\modules\\\\campains\\\\models\\\\PreferenceCampainChannelMm',	0,	NULL,	NULL,	1606748600,	1606748600),
                (NULL,	1,	4,	$id,	'backend\\\\modules\\\\campains\\\\models\\\\PreferenceCampainChannelMm',	0,	NULL,	NULL,	1606748600,	1606748600),
                (NULL,	1,	2,	$id,	'backend\\\\modules\\\\campains\\\\models\\\\PreferenceCampainChannelMm',	0,	NULL,	NULL,	1606748600,	1606748600),
                (NULL,	1,	1,	$id,	'backend\\\\modules\\\\campains\\\\models\\\\PreferenceCampainChannelMm',	0,	NULL,	NULL,	1606748600,	1606748600);
                ");
            } catch(Exception $exception) {
                Console::stdout($exception->getMessage() . "\n");
            }
        }
    }

    public function down()
    {
       return true; // do nothing
    }

}