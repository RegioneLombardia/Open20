<?php

use open20\amos\news\utility\NewsUtility;
use open20\amos\core\utilities\CurrentUser;

/**
 * @var \backend\modules\landings\models\PreferenceLandingLink $model
 */
?>
<div class="row">
    <div class="col-12 shadow-sm p-3">
        <h6 class="doc-allegato-name mb-0 pb-0">
            <a href="<?= $model->url ?>" rel="nofollow" class="el-link" target="_blank" title="<?= $model->title ?>">
                <?= $model->title ?>
                <span class="mdi mdi-open-in-new ml-2"></span>
            </a>
        </h6>
    </div>
</div>