<?php

use yii\bootstrap\Progress;
use open20\amos\core\helpers\Html;
use yii\helpers\StringHelper;
use open20\amos\translation\models\TranslationUserLanguageMm;
?>
<?php
if (strpos(\yii\helpers\Url::current(), 'create') === false) {
    ?>
    <div class="container-language m-t-10">
        <?php
        $ind = 1;
        foreach ($statusLangs as $key => $value) {
            ?>
            <div class="col-lg-2 col-md-3 col-xs-6">
                <?php
                $url = "/translation/default/update?id={$getParams['id']}&lang=$key&namespace=$namespace";
                $can = true;
                $module = \Yii::$app->getModule('translation');
                $newModel = $module->modelNs . "\\" . StringHelper::basename($namespace) . "Translation";
                $can = (\Yii::$app->getUser()->can('TRANSLATOR', ['model' => new TranslationUserLanguageMm(), 'language' => $key]) && ((!empty($module->defaultLanguage) && $key != $module->defaultLanguage) || (!isset($module->defaultLanguage))));
                $defLang = $module->defaultLanguage;
                if (!empty($defLang) && $defLang == $key) {
                    $can = false;
                }
                ?>
                <p>
                    <?php if ($can): ?>                
                        <a href="/translation/default/update?id=<?= $getParams['id'] ?>&lang=<?= $key ?>&namespace=<?= $namespace ?>" title="Go to translation in <?= $key ?>" target="_blank">
                            <?= $key ?>                                
                        </a>
                    <?php else: ?>
                        <a href="/translation/default/view-tr?id=<?= $getParams['id'] ?>&lang=<?= $key ?>&namespace=<?= $namespace ?>" title="Go to translation in <?= $key ?>" target="_blank">
                            <?= $key ?>                                
                        </a>
                    <?php endif; ?>
                    <?= $value ?>
                </p>

            </div>    
            <?php
            if ((($ind) % 6) == 0 && $ind > 2) {
                ?>
                <hr class="container-language">
                <?php
            }
            $ind++;
        }
        if (($ind - 1 ) % 6 == 0) {
            ?>
            <hr class="container-language">
            <?php
        } else {
            ?>
            <hr class="container-language">
            <?php
        }
        ?>
    </div>
<?php } ?>
