<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\models
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\models;

use open20\amos\core\interfaces\BaseContentModelInterface;
use open20\amos\core\interfaces\CrudModelInterface;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\core\interfaces\OrganizationsModelInterface;
use open20\amos\organizzazioni\components\OrganizationsPlacesComponents;
use open20\amos\organizzazioni\i18n\grammar\ProfiloSediGrammar;
use open20\amos\organizzazioni\Module;
use open20\amos\organizzazioni\widgets\UserNetworkWidgetSedi;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class ProfiloSedi
 * This is the model class for table "profilo_sedi".
 *
 * @property \open20\amos\organizzazioni\models\OrganizationsPlaces $sedeIndirizzo
 * @property string $addressField
 *
 * @package open20\amos\organizzazioni\models
 */
class ProfiloSedi
    extends \open20\amos\organizzazioni\models\base\ProfiloSedi
    implements BaseContentModelInterface, ModelLabelsInterface, CrudModelInterface,
        OrganizationsModelInterface
{
    /**
     * 
     */
    const SCENARIO_CREATE = 'scenario_create';

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'name'
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->organizzazioniModule->oldStyleAddressEnabled) {
            $this->on(self::EVENT_BEFORE_VALIDATE, [$this, 'organizationsBeforeValidate']);
        }

        if ($this->isNewRecord) {
            $this->active = 1;
            // TODO COUNTRIES DISABLED inizializzato con id dell'Italia.
            // Rimuovere tutto l'if se viene abilitata la tendina nella form.
            if (empty($this->country_id)) {
                $this->country_id = 1;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $skipColumns = [
            'name',
            'profilo_id',
        ];
        $modelColumns = $this->attributes();
        $scenarioCreateFields = [];
        foreach ($modelColumns as $modelColumn) {
            if (!in_array($modelColumn, $skipColumns)) {
                $scenarioCreateFields[] = $modelColumn;
            }
        }
        return ArrayHelper::merge(parent::scenarios(), [
                self::SCENARIO_CREATE => $scenarioCreateFields
        ]);
    }

    /**
     * 
     */
    public function organizationsBeforeValidate()
    {
        if (!$this->organizzazioniModule->oldStyleAddressEnabled) {
            $place_id = $this->address;
            OrganizationsPlacesComponents::checkPlace($place_id);
        }
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$this->organizzazioniModule->oldStyleAddressEnabled) {
            $place_id = $this->address;
            OrganizationsPlacesComponents::checkPlace($place_id);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
                'addressField' => Module::t('amosorganizzazioni', 'Address'),
        ]);
    }

    /**
     * Return the columns to show as default in GridViewWidget
     * @param bool $showActionColumns
     * @return array
     */
    public function getBaseGridViewColumns()
    {
        return [
            'name',
            [
                'attribute' => 'profiloSediType.name',
                'label' => $this->getAttributeLabel('profiloSediType')
            ],
            'addressField:raw',
            'phone',
            'fax',
            'email:email'
        ];
    }

    /**
     * Return the columns to show as default in GridViewWidget
     * @param bool $showActionColumns
     * @return array
     */
    public function getGridViewColumns($showActionColumns = true)
    {
        $columns = $this->getBaseGridViewColumns();

        if ($showActionColumns) {
            $columns [] = [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'viewOptions' => [
                    'class' => 'btn btn-tools-secondary view-headquarter-btn',
                    'url' => ['/organizzazioni/profilo-sedi/view'],
                    'defaultUrlIdParam' => true
                ],
                'updateOptions' => [
                    'class' => 'btn btn-tools-secondary update-headquarter-btn',
                    'url' => ['/organizzazioni/profilo-sedi/update'],
                    'defaultUrlIdParam' => true,
                ],
                'deleteOptions' => [
                    'class' => 'btn btn-danger-inverse delete-headquarter-btn',
                    'url' => ['/organizzazioni/profilo-sedi/delete'],
                    'defaultUrlIdParam' => true,
                ]
            ];
        }

        return $columns;
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserNetworkWidgetColumns()
    {
        /** @var ProfiloSedi $profiloSedi */
        $profiloSedi = $this->organizzazioniModule->createModel('ProfiloSedi');
        return [
            'profiloSedi.profilo_sedi_type_id' => [
                'attribute' => 'profiloSedi.profilo_sedi_type_id',
                'value' => 'profiloSedi.profiloSediType.name'
            ],
            'profiloSedi.name',
            [
                'attribute' => 'profiloSedi.addressField',
                'format' => 'raw',
                'label' => $profiloSedi->getAttributeLabel('addressField')
            ],
            [
                'label' => $profiloSedi->getAttributeLabel('profilo'),
                'value' => 'profiloSedi.profilo.name'
            ]
        ];
    }

    /**
     * @return string
     */
    public static function getUserNetworkWidgetSediClassName()
    {
        return UserNetworkWidgetSedi::className();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSedeIndirizzo()
    {
        if ($this->organizzazioniModule->oldStyleAddressEnabled) {
            return null;
        }
        return $this->hasOne($this->organizzazioniModule->createModel('OrganizationsPlaces')->className(), ['place_id' => 'address']);
    }

    /**
     * @inheritdoc
     */
    public function getNameField()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAddressField()
    {
        if (!$this->organizzazioniModule->oldStyleAddressEnabled) {
            if (is_null($this->sedeIndirizzo)) {
                return '-';
            }
            return ($this->sedeIndirizzo->postal_code ? '(' . $this->sedeIndirizzo->postal_code . ')' : '') .
                ($this->sedeIndirizzo->region ? ' ' . $this->sedeIndirizzo->region : '') .
                ($this->sedeIndirizzo->city ? ' ' . $this->sedeIndirizzo->city : '') .
                ($this->sedeIndirizzo->address ? ' ' . $this->sedeIndirizzo->address : '') .
                ($this->sedeIndirizzo->street_number ? ' ' . $this->sedeIndirizzo->street_number : '');
        } else {
            return $this->getOldStyleAddress();
        }
    }

    /**
     * This method returns the "old style" address only in case there is the parameter configured in the module.
     * @return string
     */
    public function getOldStyleAddress()
    {
        $address = '';
        if ($this->organizzazioniModule->oldStyleAddressEnabled) {
            if ($this->address_text) {
                $address .= $this->address_text . '<br />';
            }
            if ($this->cap_text) {
                $address .= $this->cap_text;
            }
            if (!is_null($this->city)) {
                if ($this->cap_text) {
                    $address .= ' ';
                }
                $address .= $this->city->nome . '<br />';
            }
            if (!is_null($this->province)) {
                $address .= $this->province->nome . '<br />';
            }
            if (!strlen($address)) {
                $address = '-';
            }
        }
        return $address;
    }

    /**
     * @return array
     */
    public function getAddressFieldAsArray()
    {
        if ($this->organizzazioniModule->oldStyleAddressEnabled) {
            return [
                'postal_code' => ($this->cap_text ? $this->cap_text : ''),
                'region' => (!is_null($this->province) && !is_null($this->province->istatRegioni) ? $this->province->istatRegioni->nome : ''),
                'city' => (!is_null($this->city) ? $this->city->nome : ''),
                'address' => ($this->address_text ? $this->address_text : ''),
                'street_number' => '',
            ];
        } elseif (!empty($this->sedeIndirizzo)) {
            return [
                'postal_code' => $this->sedeIndirizzo->postal_code,
                'region' => $this->sedeIndirizzo->region,
                'city' => $this->sedeIndirizzo->city,
                'address' => $this->sedeIndirizzo->address,
                'street_number' => $this->sedeIndirizzo->street_number
            ];
        } else {
            return null;
        }
    }

    /**
     * @param int $userId
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getAssociateHeadquarterQuery($userId)
    {
        /** @var Profilo $profilo */
        $profilo = $this->organizzazioniModule->createModel('Profilo');
        /** @var ProfiloSediUserMm $profiloSediUserMm */
        $profiloSediUserMm = $this->organizzazioniModule->createModel('ProfiloSediUserMm');
        /** @var ActiveQuery $queryUserMm */
        $queryUserMm = $profiloSediUserMm::find();
        $queryUserMm->select(['profilo_sedi_id'])->distinct();
        $queryUserMm->andWhere(['user_id' => $userId]);
        $userHeadquarterIds = $queryUserMm->column();
        /** @var ActiveQuery $query */
        $query = static::find();
        $query->innerJoinWith('profilo');
        if ($this->organizzazioniModule->enableWorkflow) {
            $query->andWhere([$profilo::tableName() . '.status' => $profilo->getValidatedStatus()]);
        }
        $query->andWhere([static::tableName() . '.active' => 1]);
        $query->andWhere([static::tableName() . '.is_main' => 0]);
        $query->andWhere(['not in', static::tableName() . '.id', $userHeadquarterIds]);
        return $query;
    }

    /**
     * @return ProfiloSediGrammar
     */
    public function getGrammar()
    {
        return new ProfiloSediGrammar();
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->__shortText($this->description, 100);
    }

    /**
     * @inheritdoc
     */
    public function getDescription($truncate)
    {
        $ret = $this->description;
        if ($truncate) {
            $ret = $this->__shortText($this->description, 200);
        }
        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function getModelModuleName()
    {
        return Module::getModuleName();
    }

    /**
     * @inheritdoc
     */
    public function getModelControllerName()
    {
        return 'profilo-sedi';
    }

    /**
     * Returns the full url to the action with the model id.
     * @param $url
     * @return null|string
     */
    private function getFullUrl($url)
    {
        if (!empty($url)) {
            return Url::toRoute(["/" . $url, "id" => $this->id]);
        }
        return null;
    }

    /**
     * @return string
     */
    private function getBaseUrl()
    {
        return $this->getModelModuleName() . '/' . $this->getModelControllerName() . '/';
    }

    /**
     * @inheritdoc
     */
    public function getCreateUrl()
    {
        return $this->getBaseUrl() . 'create';
    }

    /**
     * @inheritdoc
     */
    public function getFullCreateUrl()
    {
        return $this->getCreateUrl();
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return $this->getBaseUrl() . 'view';
    }

    /**
     * @inheritdoc
     */
    public function getFullViewUrl()
    {
        return $this->getFullUrl($this->getViewUrl());
    }

    /**
     * @inheritdoc
     */
    public function getUpdateUrl()
    {
        return $this->getBaseUrl() . 'update';
    }

    /**
     * @inheritdoc
     */
    public function getFullUpdateUrl()
    {
        return $this->getFullUrl($this->getUpdateUrl());
    }

    /**
     * @inheritdoc
     */
    public function getDeleteUrl()
    {
        return $this->getBaseUrl() . 'delete';
    }

    /**
     * @inheritdoc
     */
    public function getFullDeleteUrl()
    {
        return $this->getFullUrl($this->getDeleteUrl());
    }

}