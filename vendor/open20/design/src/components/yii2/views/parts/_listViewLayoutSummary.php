<?php
use open20\design\Module;

$modelLabel = (isset($modelLabel)) ? $modelLabel : Module::t('amosdesign','elementi');
?>


<p><?=Module::t('amosdesign','Visualizzati') . ' ' ?>{count}<?=' ' . Module::t('amosdesign','{modelLabel} di',['modelLabel' => $modelLabel]) . ' ' ?>{totalCount}<?=' ' .Module::t('amosdesign','totali')?></p>
<p><?=Module::t('amosdesign','Pagina') . ' ' ?>{page}<?=' ' . Module::t('amosdesign','di') . ' ' ?>{pageCount}</p>

