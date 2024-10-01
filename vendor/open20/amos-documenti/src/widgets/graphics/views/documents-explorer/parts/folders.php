<?php
use \open20\amos\documenti\AmosDocumenti;
?>
<script id="documents-explorer-folders" type="text/template">
    <div class="col-xs-12 header">
        <h3><?= AmosDocumenti::t('amosdocumenti', 'Cartelle ') ?>({{count}})</h3> <!--TODO change with folder count-->
        {{#canCreate}}
        <button id="create-new-folder-modal" class="btn btn-administration-primary"><span class="am am-plus"></span>
            <?= AmosDocumenti::t('amosdocumenti', 'Nuova Cartella'); ?>
        </button>
        {{/canCreate}}
    </div>
    {{#available}}
    <div class="col-xs-12 nop">
        {{#folders}}
        <div class="col-sm-4 col-xs-12 folder-item folder-container">
            <div class="folder">
                <div class="folder-container-single" data-parent-id="{{model-id}}">
                    <div class="folder-name" data-model-id="{{model-id}}"> <!--TODO change tag a with div -->
                        <span class="dash dash-folder icon-folder"></span>
                        <span class="link-name" title="{{name}}">{{name}}</span> <!--TODO change tag span with a for title link-->
                    </div>
                </div>
                <span class="am am-menu context-menu-folder" data-model-id="{{model-id}}"></span>
            </div>
        </div>
        {{/folders}}
    </div>
    {{/available}}
</script>