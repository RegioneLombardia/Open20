<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\views\comment\email
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;

?>
<div style="border:1px solid #cccccc;padding:10px;margin-bottom: 10px;background-color: #ffffff;margin-top:20px">
    <div style="color:#000000;">
        <h2 style="font-size:2em;line-height: 1;margin:0;padding:10px 0;">
            <?= Html::a(\open20\amos\documenti\AmosDocumenti::t('amosdocumenti', "É stato caricato il documento '"). $modelDocument->title . "'", Yii::$app->getUrlManager()->createAbsoluteUrl(['/documenti/documenti/view', 'id' => $modelDocument->id]), ['style' => 'color: green;']) ?>
        </h2>
    </div>

    <div style="box-sizing:border-box;font-size:13px;font-weight:normal;">
        <?php
        echo $modelDocument->descrizione;
        ?>
    </div>
    <div style="box-sizing:border-box;padding-bottom: 5px;color:#000000;">
        <div style="margin-top:20px;">
            <div style="display: flex;width: 100%;">
                <div style="width: 50px; height: 50px; overflow: hidden;-webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%;float: left;">
                    <?php
                    $layout = '{publisher}';
                    ?>
                    <?php if ($modelCreator != null): ?>
                        <?= \open20\amos\admin\widgets\UserCardWidget::widget([
                            'model' => $modelCreator,
                            'onlyAvatar' => true,
                            'absoluteUrl' => true
                        ])
                        ?>
                    <?php endif; ?>
                </div>

                <div style="margin-left: 20px;">
                    <?= \open20\amos\core\forms\PublishedByWidget::widget([
                        'model' => $modelCreator,
                        'layout' => $layout,
                    ]) ?>
                    <span style="font-weight:normal;"><?php echo $modelDocument->titolo ?></span>

                </div>
            </div>
        </div>
    </div>
</div>
