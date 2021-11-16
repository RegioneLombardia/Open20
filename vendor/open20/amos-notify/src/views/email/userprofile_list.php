<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\email
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\record\Record;
use open20\amos\cwh\base\ModelContentInterface;
use open20\amos\core\forms\ItemAndCardHeaderWidget;

/**
 * @var Record|ContentModelInterface|ViewModelInterface $model
 * @var \open20\amos\admin\models\UserProfile $profile
 * @var Record[] $arrayModels
 */
if (!empty($profile)) {
    $this->params['profile'] = $profile;
}

?>


<tr>
    <td colspan="2" style="padding-bottom:10px;">
        <table cellspacing="0" cellpadding="0" border="0" align="center"   class="email-container" width="100%">

<?php foreach ($arrayModels as $model){ ?>
    <tr>
        <td bgcolor="#FFFFFF" style="padding:10px 15px 10px 15px;">
            <table width="100%">
                <tr>
                    <td colspan="2" style="font-size:18px; font-weight:bold; padding: 5px 0 ; font-family: sans-serif;">
                        <?= Html::a($model->getNomeCognome(), Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()), ['style' => 'color: #000; text-decoration:none;']) ?>
                    </td>
                    <td colspan="2" style="font-size:18px; font-weight:bold; padding: 5px 0 ; font-family: sans-serif;">
                        <?= Html::a(Yii::t('amosnotify', "collegati"), Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()), ['style' => 'color: #000; text-decoration:none;']) ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size:13px; color:#7d7d7d; padding:10px 0; font-family: sans-serif;"><?= $model->getNomeCognome(); ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size:13px; color:#7d7d7d; padding:10px 0; font-family: sans-serif;">Per test: profile_id <?= $model->id; ?> user_id <?= $model->user_id; ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="border-bottom:1px solid #D8D8D8; padding:5px 0px"></td>
                </tr>
            </table>
        </td>
    </tr>
<?php } ?>
        </table>
    </td>
</tr>
