<?php

namespace app\modules\cmsapi\cart;

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
        ['pattern' => 'api/v1/cart-add', 'route' => 'cmscartapi/cmsapi/add'],
        ['pattern' => 'api/v1/cart-get', 'route' => 'cmscartapi/cmsapi/get'],
    ];

}
