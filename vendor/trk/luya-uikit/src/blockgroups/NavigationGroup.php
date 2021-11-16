<?php

namespace trk\uikit\blockgroups;

/**
 * Navigation Block Group.
 *
 */
class NavigationGroup extends \luya\cms\base\BlockGroup
{
    public function identifier()
    {
        return 'navigation';
    }

    public function label()
    {
        return 'Navigation';
    }

    public function getPosition()
    {
        return 70;
    }
}