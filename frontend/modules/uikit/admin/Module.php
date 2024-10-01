<?php

namespace  app\modules\uikit\admin;

use Yii;
use luya\base\CoreModuleInterface;


final class Module extends \luya\admin\base\Module implements CoreModuleInterface
{
    /**
     * @inheritdoc
     */
    public function getAdminAssets()
    {
        return  [
            'app\modules\uikit\assets\MainAsset',
        ];
    }
}
