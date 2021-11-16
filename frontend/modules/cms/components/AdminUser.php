<?php

namespace app\modules\cms\components;


class AdminUser extends \luya\admin\components\AdminUser
{
    public function init()
    {
        parent::init();
        $this->idParam = '__luyaAdmin_id';

    }
}