<?php

use open20\amos\news\utility\NewsUtility;
use open20\amos\core\utilities\CurrentUser;

$url = $model->link_document;
//$titolo = $model->titolo;
$longName =  $model->titolo;

if (!empty($model->documentMainFile)){
    $url = $model->documentMainFile->getWebUrl();
    $longName = $model->documentMainFile->name . '.' . $model->documentMainFile->type;
}


?>


<div class="shadow-sm p-3 h-100">

<h6 class="doc-allegato-name mb-0 pb-0">
    <a href="<?= $url ?>" class="el-link" title="<?= $longName ?>"><?= $model->title ?></a>
</h6>

  <p class="mb-0"><span class="news-allegato-kb small text-muted"><?= $longName ?></span></p>
</div>
