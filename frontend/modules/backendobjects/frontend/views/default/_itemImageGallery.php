<?php

use app\modules\cms\helpers\CmsHelper;
use open20\amos\news\AmosNews;


$truncate = 250;
?>



    <div class="it-grid-item-wrapper">
        <div class="img-responsive-wrapper">
            <div class="img-responsive">
                <div class="img-wrapper">
                    <?php
                    $url = \Yii::$app->params['platform']['backendUrl'] . '/img/img_default.jpg';
                    if ($model->getFileImage()) {
                        $url =  $model->getFileImage()->getWebUrl();
                    }

                    echo $contentImage = CmsHelper::img(
                        $url,
                        [
                            // 'class' => 'el-image',
                            'alt' => AmosNews::t('amosnews', 'immagine')
                        ]
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
