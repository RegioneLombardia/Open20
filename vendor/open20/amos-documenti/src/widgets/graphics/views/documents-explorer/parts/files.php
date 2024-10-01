<?php
use \open20\amos\documenti\AmosDocumenti;
?>
<script id="documents-explorer-files" type="text/template">
    <div class="col-xs-12 header">
        <h3>Files ({{count}})</h3>
        {{#canCreate}}
        <button id="upload-new-files" class="btn btn-administration-primary"><span class="am am-plus"></span>
            <?= AmosDocumenti::t('amosdocumenti', 'Caricamento File'); ?>
        </button>
        <button id="upload-multi-files" class="btn btn-administration-primary"><span class="am am-plus"></span>
            <?= AmosDocumenti::t('amosdocumenti', 'Caricamento Multiplo'); ?>
        </button>
        {{/canCreate}}
    </div>
    {{#available}}
    <div class="col-xs-12 nop">
        {{#files}}
        <div class="col-xs-12 col-sm-3 file-item">
            <div class="file">
                <a href="{{url}}" title="Visualizza {{name}}">
                    <div class="file-preview" data-model-id="{{model-id}}"
                         data-model-hash="{{model-hash}}" data-file-id="{{model-file-id}}">
                        <span class="icon_widget_graph dash dash-{{icon-class}}"> </span>
                        <span class="file-date">{{date}}</span>
                        <span class="file-name">{{name}}</span>
                        <span class="file-link">{{link}}</span>
                        <span class="file-size">{{size}}</span>
                    </div>
                    <span class="pull-right am am-menu context-menu-documents" data-model-id="{{model-id}}"
                          data-model-hash="{{model-hash}}" data-file-id="{{model-file-id}}"></span>
                </a>
            </div>
        </div>
        {{/files}}
    </div>
    {{/available}}
</script>