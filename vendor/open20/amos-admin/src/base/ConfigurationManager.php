<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */

namespace open20\amos\admin\base;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\exceptions\AdminException;
use yii\base\BaseObject;

/**
 * Class ConfigurationManager
 * @package open20\amos\admin\base
 */
class ConfigurationManager extends BaseObject
{
    // Form and view types
    const VIEW_TYPE_FORM = 'form';
    const VIEW_TYPE_VIEW = 'view';

    // Configuration types
    const CONF_TYPE_BOXES = 'boxes';
    const CONF_TYPE_FIELDS = 'fields';

    /**
     * @var array $fieldsTypesToCheck This is internal configurations useful to check the integrity of the array content.
     */
    private static $fieldsTypes = [
        self::CONF_TYPE_BOXES => 'ARRAY',
        self::CONF_TYPE_FIELDS => 'ARRAY',
        self::VIEW_TYPE_FORM => 'BOOL',
        self::VIEW_TYPE_VIEW => 'BOOL',
        'referToBox' => 'STRING',
    ];

    private static $allowedFieldsConfKeys = [
        self::VIEW_TYPE_FORM,
        self::VIEW_TYPE_VIEW,
        'referToBox'
    ];

    private static $allowedBoxesConfKeys = [
        self::VIEW_TYPE_FORM,
        self::VIEW_TYPE_VIEW
    ];

    private static $defaultFormFields = [
        'status'
    ];

    private static $fieldsAssociated = [
        'user_profile_area_id' => 'user_profile_area_other',
        'user_profile_role_id' => 'user_profile_role_other'
    ];

    /**
     * @var array $fieldsConfigurations This array contains all configurations for boxes and fields.
     */
    public $fieldsConfigurations = [];

    /**
     * @throws AdminException
     */
    public function checkFieldsConfigurationsStructure()
    {
        $ok = true;
        foreach ($this->fieldsConfigurations as $confType => $fieldsConfiguration) {
            if ($confType == self::CONF_TYPE_BOXES) {
                $ok = $this->checkBoxesStructure($fieldsConfiguration);
            } elseif ($confType == self::CONF_TYPE_FIELDS) {
                $ok = $this->checkFieldsStructure($fieldsConfiguration);
            } else {
                // Conf type not allowed
                $ok = false;
            }

            if (!$ok) {
                break;
            }
        }
        if (!$ok) {
            throw new AdminException(AmosAdmin::t('amosadmin', 'ConfigurationManager: fields and boxes configuration check failed. Check the module configuration.'));
        }
    }

    /**
     * @param array $configurations
     * @return bool
     */
    private function checkBoxesStructure($configurations)
    {
        $ok = true;
        foreach ($configurations as $confName => $configuration) {
            foreach ($configuration as $confElementKey => $confElementValue) {
                if (!in_array($confElementKey, self::$allowedBoxesConfKeys)) {
                    $ok = false;
                    break;
                }
                if (!$this->checkFieldType($confElementKey, $confElementValue)) {
                    $ok = false;
                    break;
                }
            }
        }
        return $ok;
    }

    /**
     * @param array $configurations
     * @return bool
     */
    private function checkFieldsStructure($configurations)
    {
        $ok = true;
        foreach ($configurations as $confName => $configuration) {
            foreach ($configuration as $confElementKey => $confElementValue) {
                if (!in_array($confElementKey, self::$allowedFieldsConfKeys)) {
                    $ok = false;
                    break;
                }
                if (!$this->checkFieldType($confElementKey, $confElementValue)) {
                    $ok = false;
                    break;
                }
            }
        }
        return $ok;
    }

    /**
     * Method that checks the correct type of a field value.
     * @param string $confElementKey Name of an internal array field.
     * @param string $confElementValue Value type of an internal array field.
     * @return bool Returns true if everything goes well. False otherwise.
     */
    private function checkFieldType($confElementKey, $confElementValue)
    {
        $fieldType = self::$fieldsTypes[$confElementKey];
        switch ($fieldType) {
            case 'STRING':
                $ok = is_string($confElementValue);
                break;
            case 'BOOL':
                $ok = is_bool($confElementValue);
                break;
            case 'ARRAY':
                $ok = is_array($confElementValue);
                break;
            default:
                $ok = false;
                break;
        }
        return $ok;
    }

    /**
     * This private method check if a box or a field is visible in form or view.
     * @param string $confType
     * @param string $name
     * @param string $viewType
     * @return bool
     */
    private function isVisible($confType, $name, $viewType)
    {
        try {
            return $this->fieldsConfigurations[$confType][$name][$viewType];
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * This method check if a box is visible in form or view.
     * @param string $name
     * @param string $viewType
     * @return bool
     */
    public function isVisibleBox($name, $viewType)
    {
        return $this->isVisible(self::CONF_TYPE_BOXES, $name, $viewType);
    }

    /**
     * This method check if a box is visible in form.
     * @param string $name
     * @param string $viewType
     * @return bool
     */
    public function isVisibleBoxInForm($name)
    {
        return $this->isVisibleBox($name, self::VIEW_TYPE_FORM);
    }

    /**
     * This method check if a box is visible in view.
     * @param string $name
     * @param string $viewType
     * @return bool
     */
    public function isVisibleBoxInView($name)
    {
        return $this->isVisibleBox($name, self::VIEW_TYPE_VIEW);
    }

    /**
     * This method check if a field is visible in form or view.
     * @param string $name
     * @param string $viewType
     * @return bool
     */
    public function isVisibleField($name, $viewType)
    {
        return $this->isVisible(self::CONF_TYPE_FIELDS, $name, $viewType);
    }

    /**
     * This method check if a field is visible in form.
     * @param string $name
     * @param string $viewType
     * @return bool
     */
    public function isVisibleFieldInForm($name)
    {
        return $this->isVisibleField($name, self::VIEW_TYPE_FORM);
    }

    /**
     * This method check if a field is visible in view.
     * @param string $name
     * @param string $viewType
     * @return bool
     */
    public function isVisibleFieldInView($name)
    {
        return $this->isVisibleField($name, self::VIEW_TYPE_VIEW);
    }

    /**
     * This method return all fields visible in the form or in the view. It checks if a field is in a box.
     * If it's of a box, it checks if the box is also visible.
     * In case a field is visible but the box is not visible, the method don't add the field
     * in the form fields array because a field is visible only if is visible also the container box.
     * In case a field is not in a box, the method checks only the field visibility.
     * @param string $type
     * @return array
     */
    private function getFields($type)
    {
        $fields = self::$defaultFormFields;

        foreach ($this->fieldsConfigurations['fields'] as $fieldName => $fieldConfiguration) {
            if (
                isset($fieldConfiguration[$type]) &&
                ($fieldConfiguration[$type] === true) &&
                (
                    (!isset($fieldConfiguration['referToBox'])) ||
                    (
                        isset($fieldConfiguration['referToBox']) &&
                        isset($this->fieldsConfigurations[self::CONF_TYPE_BOXES][$fieldConfiguration['referToBox']]) &&
                        isset($this->fieldsConfigurations[self::CONF_TYPE_BOXES][$fieldConfiguration['referToBox']][$type]) &&
                        ($this->fieldsConfigurations[self::CONF_TYPE_BOXES][$fieldConfiguration['referToBox']][$type] === true)
                    )
                )
            ) {
                $fields[] = $fieldName;
                if (isset(self::$fieldsAssociated[$fieldName])) {
                    $fields[] = self::$fieldsAssociated[$fieldName];
                }
            }
        }

        return $fields;
    }

    /**
     * This method return all fields visible in the form. It checks if a field is in a box.
     * If it's of a box, it checks if the box is also visible.
     * In case a field is visible but the box is not visible, the method don't add the field
     * in the form fields array because a field is visible only if is visible also the container box.
     * In case a field is not in a box, the method checks only the field visibility.
     * @return array
     */
    public function getFormFields()
    {
        return $this->getFields(self::VIEW_TYPE_FORM);
    }

    /**
     * This method return all fields visible in the view. It checks if a field is in a box.
     * If it's of a box, it checks if the box is also visible.
     * In case a field is visible but the box is not visible, the method don't add the field
     * in the view fields array because a field is visible only if is visible also the container box.
     * In case a field is not in a box, the method checks only the field visibility.
     * @return array
     */
    public function getViewFields()
    {
        return $this->getFields(self::VIEW_TYPE_VIEW);
    }
}
