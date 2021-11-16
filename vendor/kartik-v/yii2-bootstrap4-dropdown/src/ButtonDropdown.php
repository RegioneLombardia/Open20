<?php
/**
 * @package   yii2-bootstrap4-dropdown
 * @version   1.0.0
 */

namespace kartik\bs4dropdown;

use yii\bootstrap4\ButtonDropdown as Yii2ButtonDropdown;

/**
 * ButtonDropdown renders a group or split button dropdown bootstrap component
 * with multi level submenu dropdown capability.
 *
 * For example,
 *
 * ```php
 * // a button group using Dropdown widget
 * echo ButtonDropdown::widget([
 *     'label' => 'Action',
 *     'dropdown' => [
 *         'items' => [
 *             ['label' => 'DropdownA', 'url' => '/'],
 *             ['label' => 'DropdownB', 'url' => '#'],
 *         ],
 *     ],
 * ]);
 * ```
 */
class ButtonDropdown extends Yii2ButtonDropdown
{
    /**
     * @var string name of a class to use for rendering dropdowns withing this widget. Defaults to [[Dropdown]].
     */
    public $dropdownClass = 'kartik\bs4dropdown\Dropdown';
}
