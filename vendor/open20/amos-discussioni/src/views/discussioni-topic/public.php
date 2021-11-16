<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\views\discussioni-topic
 * @category   CategoryName
 */

use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\attachments\components\AttachmentsTableWithPreview;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\forms\ShowUserTagsWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\helpers\Html;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\core\views\toolbars\StatsToolbar;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\icons\AmosIcons;
use open20\amos\attachments\components\AttachmentsList;
use open20\amos\core\forms\InteractionMenuWidget;
use \open20\amos\discussioni\models\DiscussioniTopic;

/**
 * @var yii\web\View $this
 * @var open20\amos\discussioni\models\DiscussioniTopic $model
 * @var yii\widgets\ActiveForm $form
 */

$this->title = $model->titolo;

?>

<div class="discussioni-topic-view col-xs-12 nop">
    <div class="clearfix"></div>
    <div class="col-xs-12">
        <div class="header col-xs-12 nop">
            <?php
            $url = '/img/img_default.jpg';
            if (!is_null($model->discussionsTopicImage)) {
                $url = $model->discussionsTopicImage->getWebUrl('original', false, true);
                ?>
                <?= Html::img($url, [
                    'class' => 'img-responsive',
                    'alt' => AmosDiscussioni::t('amosdiscussioni', 'Immagine della discussione')
                ]); ?>
                <?php
            }
            ?>
            <div class="title col-xs-12">
                <h2 class="title-text"><?= $model->titolo ?></h2>
            </div>
        </div>
        <div class="text-content col-xs-12 nop">
            <?= $model->testo ?>
        </div>
        <div class="col-xs-12 text-center">
            <hr>
            <?= Html::a(
                AmosDiscussioni::t('amosdiscussioni', 'Contribuisci'),
                ['partecipa', 'id' => $model->id, '#' => 'comments_contribute'],
                [
                    'class' => 'btn btn-navigation-primary',
                    'title' => AmosDiscussioni::t('amosdiscussioni', 'commenta')
                ]
            ) ?>
        </div>

    </div>

</div>
