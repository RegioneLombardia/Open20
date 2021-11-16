<?php
use \open20\amos\documenti\AmosDocumenti;
?>
<script id="documents-explorer-navbar" type="text/template">
    <!--<div class="container-change-view">
        <div class="btn-tools-container">
            <div class="tools-right">
                <button id="view-as-list" class="btn btn-primary"><span class="am am-view-list"></span>
                </button>
                <button id="view-as-icon" class="btn btn-primary"><span class="am am-view-module"></span>
                </button>
                <!--<button id="download-all-files-in-scope" class="btn btn-primary"><span class="am am-download"></span>-->
                <!--</button>-->
                <!--<button id="find-files-in-scope" class="btn btn-primary"><span class="am am-search"></span></button>-->
            </div>
        </div>
    <!--</div>-->
    <div class="col-xs-12 description">
        <p><?= AmosDocumenti::t('amosdocumenti',
            'A <strong>sinistra</strong> vedi le <strong>aree</strong> e le <strong>stanze</strong> a cui sei stato invitato
            e puoi entrare in quella che ti interessa cliccando sul suo nome. Fai clic su <span class="am am-menu"></span> di un\'area
            o stanza per vedere le operazioni che puoi fare. Con il tasto <span class="am am-arrow-left" style="top:0;font-size:1em;"><span class="sr-only">Torna indietro</span></span>
            puoi tornare all\'area o alla stanza precedente. Nella parte <strong>destra</strong> vedi le <strong>cartelle</strong>
            e i <strong>documenti</strong> dell\'area o della stanza in cui ti trovi. Per aprire una cartella o aprire la scheda
            di un documento fai click sul nome. Nel menu <span class="am am-menu"></span> vedi le operazioni che puoi fare sulla cartella o sul documento.'
        ) ?>
        </p>
    </div>
</script>