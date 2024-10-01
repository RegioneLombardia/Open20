<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\modules\formazione/views
 */

use yii\helpers\Html;
use open20\video\Module;

/*
 * Personalizzare a piacimento la vista
 * $model è il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 */
?>

<div class="formazione-card">
    <div class="card-wrapper card-space">
        <div class="card card-bg">
            <div class="card-body">
                <?= Html::a("<h3 class='card-title'>$model->title</h3>", ['/video/video/view', 'id' => $model->id]) ?>
                <div class="card-description"> 
                    <?= $model->getDescription(true) ?>
                </div>
                <a class="btn btn-link" href="/video/video/view?id=<?= $model->id ?>" title="Vai alla pagina dell' evento informativo ">
                    <p class="mb-0"><?= Module::t('amosvideo', 'Vai al video') ?></p>
                </a>       
            </div>
        </div>
    </div>   
</div>