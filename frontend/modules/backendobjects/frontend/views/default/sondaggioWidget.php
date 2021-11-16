<?php

use app\modules\backendobjects\frontend\Module;
use backend\modules\landings\components\LandingSondaggioWidget;
use open20\amos\sondaggi\models\Sondaggi;

?>
<?php if (!is_null($dataProvider)) {
    if ($dataProvider->getTotalCount() == 1) :
        $sondaggio = isset($dataProvider->getModels()[0]) ? $dataProvider->getModels()[0] : null;

        if (!is_null($sondaggio) && ($sondaggio->status == Sondaggi::WORKFLOW_STATUS_VALIDATO)):
            ?>
            <div class="<?= $cssClass ?>">
                <div class="clearfix"></div>

                <div class="news-home container">
                    <h3 class="mb-4 h1"><span class="mdi mdi-poll-box"></span> Sondaggio</h3>
                    <?php
    //                echo \yii\helpers\VarDumper::dump( $sondaggio->attributes, 3, true);
                    // /it/sondaggi/frontend/compila?id=1
                    echo LandingSondaggioWidget::widget([
                        'model' => $sondaggio,
                    ]);
                    ?>
                </div>

            </div>
            <?php
        endif;

   else:

    $js = <<<JS
    $('.section-sondaggio').attr('style',"display:none !important");
JS;
    $this->registerJs($js);

    endif;
}
?>