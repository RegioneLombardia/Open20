<?php
/**
 * @package   yii2-bootstrap4-dropdown
 * @version   1.0.4
 */

namespace kartik\bs4dropdown;

use yii\bootstrap4\ButtonDropdown as Yii2ButtonDropdown;

/**
 * ButtonDropdown renders a bootstrap 4.x button dropdown component. It extends the [[Yii2ButtonDropdown]] to render
 * a button dropdown component individually, or with a button group, or split button dropdown. The Krajee
 * enhancement adds multi level submenu dropdown capability to the bootstrap default component.
 *
 * For example,
 *
 * ```php
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
 *
 */
class ButtonDropdown extends Yii2ButtonDropdown
{
    /**
     * @var string name of a class to use for rendering dropdowns withing this widget. Defaults to [[Dropdown]].
     */
    public $dropdownClass = 'kartik\bs4dropdown\Dropdown';
}
