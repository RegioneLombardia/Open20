<?php

namespace luya\bootstrap3\blockgroups;

use luya\cms\base\BlockGroup;

/**
 * Bootstrap 3 Group.
 *
 * @since 1.0.0
 */
class Bootstrap3Group extends BlockGroup
{
    public function identifier()
    {
        return 'bootstrap3';
    }
    
    public function label()
    {
        return 'Bootstrap 3';
    }
    
    public function getPosition()
    {
        return 60;
    }
}
