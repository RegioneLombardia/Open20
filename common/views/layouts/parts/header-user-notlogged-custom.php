<a href="<?= \Yii::$app->params['linkConfigurations']['loginLinkCommon'] ?>" class="btn btn-icon py-2 px-0" title="Accedi con la tua identità digitale">
    <span class="rounded-icon border border-white bg-transparent">
        <svg class="icon icon-light">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-key"></use>
        </svg>
    </span>
    <span class="d-block">Accedi</span>
</a>