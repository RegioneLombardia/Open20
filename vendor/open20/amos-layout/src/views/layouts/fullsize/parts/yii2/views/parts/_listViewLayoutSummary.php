<?php
use open20\amos\layout\Module;

$modelLabel = (isset($modelLabel)) ? $modelLabel : Module::t('amoslayout','elementi');
?>


<p><?=Module::t('amoslayout','Visualizzati') . ' ' ?>{count}<?=' ' . Module::t('amoslayout','{modelLabel} di',['modelLabel' => $modelLabel]) . ' ' ?>{totalCount}<?=' ' .Module::t('amoslayout','totali')?></p>
<p><?=Module::t('amoslayout','Pagina') . ' ' ?>{page}<?=' ' . Module::t('amoslayout','di') . ' ' ?>{pageCount}</p>

