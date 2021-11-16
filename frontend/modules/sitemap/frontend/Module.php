<?php

namespace app\modules\sitemap\frontend;

use luya\base\CoreModuleInterface;

/**
 * Sitemap Admin Module.
 *
 * 
 * @author
 * @since 1.0.0
 */
final class Module extends \luya\base\Module implements CoreModuleInterface {

    public $urlRules = [
        ['pattern' => 'sitemap.xml', 'route' => 'sitemap/sitemap/index']
    ];

}
