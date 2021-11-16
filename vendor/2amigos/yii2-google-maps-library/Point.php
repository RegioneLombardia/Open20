<?php
/**
 */
namespace dosamigos\google\maps;

use yii\base\InvalidConfigException;
use yii\base\BaseObject;

/**
 * Point
 *
 * Google maps point
 *
 * @package dosamigos\google\maps
 */
class Point extends BaseObject
{
    /**
     *
     * The x coordinate
     * @var int x
     */
    private $_x;
    /**
     *
     * The y coordinate.
     * @var int y
     */
    private $_y;

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if (empty($this->_x) || empty($this->_y)) {
            throw new InvalidConfigException('"x" and "y" cannot be null.');
        }
        if (!is_numeric($this->_x) || !is_numeric($this->_y)) {
            throw new InvalidConfigException('"x" and "y" must be a numeric string or a number!');
        }
        parent::init();
    }

    /**
     * Sets x coordinate ght of the Point
     *
     * @param $value
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function setX($value)
    {
        if (!is_numeric($value)) {
            throw new InvalidConfigException('"x" must be a numeric string or a number!');
        }

        $this->_x = $value;
    }

    /**
     * Sets the y coordinate of the Point
     *
     * @param $value
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function setY($value)
    {
        if (!is_numeric($value)) {
            throw new InvalidConfigException('"y" must be a numeric string or a number!');
        }

        $this->_y = $value;
    }

    /**
     *
     * returns array representation of the size
     */
    public function asArray()
    {
        return ['x' => $this->_x, 'y' => $this->_y];
    }

    /**
     * @return string Javascript code to return the Point
     */
    public function getJs()
    {
        return "new google.maps.Point({$this->_x}, {$this->_y})";
    }
}
