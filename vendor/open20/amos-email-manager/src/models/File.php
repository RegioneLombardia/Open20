<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

namespace open20\amos\emailmanager\models;

use yii\base\BaseObject;


class File extends BaseObject
{
    public $content;
    public $name;
    public $type;


    /**
     *
     * @return type
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     *
     * @return type
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param type $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     *
     * @param type $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     *
     * @param type $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
