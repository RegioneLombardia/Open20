<?php

namespace luya\admin\ngrest\render;

use luya\admin\ngrest\ConfigInterface;

/**
 * NgRest Render Interface.
 *
 * All NgRest renderers must implement this interface.
 *
 * @since 1.0.0
 */
interface RenderInterface
{
    /**
     * Setter method for the NgRestConfig.
     *
     * @param ConfigInterface $config
     */
    public function setConfig(ConfigInterface $config);

    /**
     * Renders the current ngrest renderer.
     */
    public function render();
}
