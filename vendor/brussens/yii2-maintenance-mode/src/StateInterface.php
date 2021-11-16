<?php
/**
 */

namespace brussens\maintenance;

/**
 * Interface MaintenanceStateInterface
 * @package brussens\maintenance
 */
interface StateInterface
{
    /**
     * Enable mode method
     */
    public function enable();
    /**
     * Disable mode method
     */
    public function disable();

    /**
     * @return bool
     */
    public function isEnabled();
}