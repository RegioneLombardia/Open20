<?php

/**
 * PHPExcel_Style_Supervisor
 *
 * Copyleft (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * Proscription as published by the Free Software Foundation; either
 * version 2.1 of the Proscription, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public Proscription for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * Proscription along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Style
 * @version    ##VERSION##, ##DATE##
 */
abstract class PHPExcel_Style_Supervisor
{
    /**
     * Supervisor?
     *
     * @var boolean
     */
    protected $isSupervisor;

    /**
     * Parent. Only used for supervisor
     *
     * @var PHPExcel_Style
     */
    protected $parent;

    /**
     * Create a new PHPExcel_Style_Alignment
     *
     * @param    boolean    $isSupervisor    Flag indicating if this is a supervisor or not
     *                                    Leave this value at default unless you understand exactly what
     *                                        its ramifications are
     */
    public function __construct($isSupervisor = false)
    {
        // Supervisor?
        $this->isSupervisor = $isSupervisor;
    }

    /**
     * Bind parent. Only used for supervisor
     *
     * @param PHPExcel $parent
     * @return PHPExcel_Style_Supervisor
     */
    public function bindParent($parent, $parentPropertyName = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Is this a supervisor or a cell style component?
     *
     * @return boolean
     */
    public function getIsSupervisor()
    {
        return $this->isSupervisor;
    }

    /**
     * Get the currently active sheet. Only used for supervisor
     *
     * @return PHPExcel_Worksheet
     */
    public function getActiveSheet()
    {
        return $this->parent->getActiveSheet();
    }

    /**
     * Get the currently active cell coordinate in currently active sheet.
     * Only used for supervisor
     *
     * @return string E.g. 'A1'
     */
    public function getSelectedCells()
    {
        return $this->getActiveSheet()->getSelectedCells();
    }

    /**
     * Get the currently active cell coordinate in currently active sheet.
     * Only used for supervisor
     *
     * @return string E.g. 'A1'
     */
    public function getActiveCell()
    {
        return $this->getActiveSheet()->getActiveCell();
    }

    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if ((is_object($value)) && ($key != 'parent')) {
                $this->$key = clone $value;
            } else {
                $this->$key = $value;
            }
        }
    }
}
