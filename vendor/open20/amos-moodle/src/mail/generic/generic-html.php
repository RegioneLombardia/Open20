<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin
 * @category   CategoryName
 */

use yii\helpers\Html;
use open20\amos\moodle\AmosMoodle;

/* @var $this yii\web\View */

$appLink = Yii::$app->urlManager->createAbsoluteUrl(['/']);
$appName = Yii::$app->name;

$this->title = $appName;
$this->registerCssFile('http://fonts.googleapis.com/css?family=Roboto');
?>

<table width=" 600" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <div class="corpo"
                 style="border:1px solid #cccccc;padding:10px;margin-bottom:10px;background-color:#ffffff;margin-top:20px">

                <div class="sezione titolo" style="overflow:hidden;color:#000000;">
                    <h2 style="padding:5px 0;	margin:0;"><?=
                        AmosMoodle::t('amosmoodle', '#courses');
                        ?></h2>
                </div>
                <div class="sezione" style="overflow:hidden;color:#000000;">
                    <div class="testo">
                        <?php
                        foreach ($body as $paragraph):
                            ?>
                            <p>
                                <?= $paragraph ?>
                            </p>
                            <?php
                        endforeach;
                        ?>
                    </div>

                </div>
            </div>
        </td>
    </tr>
</table>
<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <p style="text-align:center"><?= AmosMoodle::t('amosmoodle', '#dont_reply'); ?></p>
        </td>
    </tr>

</table>