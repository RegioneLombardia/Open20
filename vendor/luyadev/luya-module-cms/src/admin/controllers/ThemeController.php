<?php

namespace luya\cms\admin\controllers;

use luya\admin\ngrest\base\Controller;

/**
 * Theme for LUYA CMS.
 *
 * This module / component allow user to manage actual display themes.
 *
 * @since 3.0.0
 */
class ThemeController extends Controller
{
    public $modelClass = 'luya\cms\models\Theme';
}
