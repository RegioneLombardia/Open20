<?php

namespace trk\uikit\blockgroups;

/**
 * Miscellaneous Block Group.
 *
 */
class MiscGroup extends \luya\cms\base\BlockGroup
{
    public function identifier()
    {
        return 'misc';
    }

    public function label()
    {
        return 'Miscellaneous';
    }

    public function getPosition()
    {
        return 70;
    }
}