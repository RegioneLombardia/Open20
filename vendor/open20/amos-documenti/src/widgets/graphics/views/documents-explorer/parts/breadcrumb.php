<?php
use \open20\amos\documenti\AmosDocumenti;
?>
<script id="documents-explorer-breadcrumb" type="text/template">
    <div class="col-xs-12 nop">
        <div class="col-xs-12 explorer-breadcrumb">
            <span><?= AmosDocumenti::t('amosdocumenti', 'Sei in: ') . ' '; ?></span>
            {{#links}}
            <span class="{{classes}}" data-parent-id="{{model-id}}" data-scope-id="{{scope-id}}">
                {{name}}
            </span>
            {{#isNotLast}}<span> > </span>{{/isNotLast}}
            {{/links}}
        </div>
    </div>
</script>