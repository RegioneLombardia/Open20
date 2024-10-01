<?php

namespace amos\cmsbridge\utility;

use ReflectionClass;
use ReflectionProperty;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\helpers\Json;

class CmsObject extends BaseObject
{

    public function json_encode()
    {
        return Json::encode($this);
    }

    public function json_decode($json)
    {
        $values = Json::decode($json);
        $this->load($values, '');
    }

    /**
     *
     * @param type $data
     * @param type $formName
     * @return boolean
     */
    public function load($data, $formName = null)
    {
        $scope = $formName === null ? $this->formName() : $formName;
        if ($scope === '' && !empty($data)) {
            $this->setAttributes($data);
            return true;
        } elseif (isset($data[$scope])) {
            $this->setAttributes($data[$scope]);

            return true;
        }

        return false;
    }

    /**
     *
     * @return type
     * @throws InvalidConfigException
     */
    public function formName()
    {
        $reflector = new ReflectionClass($this);
        if (PHP_VERSION_ID >= 70000 && $reflector->isAnonymous()) {
            throw new InvalidConfigException('The "formName()" method should be explicitly defined for anonymous models');
        }
        return $reflector->getShortName();
    }

    /**
     *
     * @return type
     */
    public function attributes()
    {
        $class = new ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }

        return $names;
    }

    /**
     *
     * @param type $values
     */
    public function setAttributes($values)
    {
        if (is_array($values)) {
            $attributes = $this->attributes();
            foreach ($values as $name => $value) {
                $this->$name = $value;
            }
        }
    }
}