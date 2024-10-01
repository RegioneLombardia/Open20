<?php
/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace open20\elasticsearch\models;

/**
 * Description of ItemConditionRange
 *
 */
class ItemConditionRange extends BaseItemCondition
{
    const DATE_FORMAT = 'c'; //ISO 8601 Date Format

    private $field;
    private $start_date     = null;
    private $end_date       = null;
    private $from_condition = 'gte';
    private $to_condition   = 'lte';
    private $useDate        = true;

    public function getUseDate()
    {
        return $this->useDate;
    }

    public function setUseDate($useDate)
    {
        $this->useDate = $useDate;
    }

    public function getField()
    {
        return $this->field;
    }

    public function setField($field)
    {
        $this->field = $field;
    }

    public function from($from)
    {
        $this->start_date = $from;
    }

    public function to($to)
    {
        $this->end_date = $to;
    }

    public function formGt($from)
    {
        $this->from_condition = 'gt';
        $this->from($from);
    }

    public function toLe($to)
    {
        $this->to_condition = 'lt';
        $this->to($to);
    }

    public function fromGte($from)
    {
        $this->from_condition = 'gte';
        $this->from($from);
    }

    public function toLte($to)
    {
        $this->to_condition = 'lte';
        $this->to($to);
    }

    public function toArray()
    {
        $condition [$this->field] = [];
        if (!is_null($this->start_date)) {
            if ($this->getUseDate()) {
                $condition [$this->field] [$this->from_condition] = date(self::DATE_FORMAT, strtotime($this->start_date));
            } else {
                $condition [$this->field] [$this->from_condition] = $this->start_date;
            }
        }
        if (!is_null($this->end_date)) {
            if ($this->getUseDate()) {
                $condition [$this->field] [$this->to_condition] = date(self::DATE_FORMAT, strtotime($this->end_date));
            } else {
                $condition [$this->field] [$this->to_condition] = $this->end_date;
            }
        }
        if (!empty($this->analyzer)) {
            $condition["analyzer"] = $this->analyzer;
        }
        return ['range' => $condition];
    }
}