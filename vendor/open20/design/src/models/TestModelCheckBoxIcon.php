<?php
namespace open20\design\models;

use open20\design\components\bootstrapitalia\interfaces\CheckBoxListTopicsIconInterface;

class TestModelCheckBoxIcon implements CheckBoxListTopicsIconInterface
{
    public $label;
    public $description;
    public $icon;
    public $id;

    public function __construct(string $label, string $description, string $icon, string $id)
    {
        $this->label = $label;
        $this->description = $description;
        $this->icon = $icon;
        $this->id = $id;
        
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
