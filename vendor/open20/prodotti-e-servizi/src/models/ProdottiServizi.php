<?php

namespace open20\prodottiservizi\models;

use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\attachments\models\File;
use open20\amos\core\interfaces\ContentPublicationInteraface;
use open20\amos\core\interfaces\ModelLabelsInterface;
use yii\helpers\ArrayHelper;
use open20\prodottiservizi\Module;
use open20\prodottiservizi\i18n\grammar\ProdottiServiziGrammar;

/**
 * This is the model class for table "prodotti_servizi".
 */
class ProdottiServizi extends \open20\prodottiservizi\models\base\ProdottiServizi
    implements ContentPublicationInteraface, ModelLabelsInterface
{
    public $prodotti_servizi_destinatari_mm;
    public $prodotti_servizi_finalita_mm;
    public $attachments;
    public $icon;

    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'id_product_root',
                'label' => $labels['id_product_root'],
                'type' => 'integer'
            ],
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'subtitle',
                'label' => $labels['subtitle'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
            ],
            [
                'slug' => 'financial_description',
                'label' => $labels['financial_description'],
                'type' => 'text'
            ],
            [
                'slug' => 'facilitation_features',
                'label' => $labels['facilitation_features'],
                'type' => 'text'
            ],
            [
                'slug' => 'projects_features',
                'label' => $labels['projects_features'],
                'type' => 'text'
            ],
            [
                'slug' => 'expenses_eligible',
                'label' => $labels['expenses_eligible'],
                'type' => 'text'
            ],
            [
                'slug' => 'status',
                'label' => $labels['status'],
                'type' => 'string'
            ],
            [
                'slug' => 'scope',
                'label' => $labels['scope'],
                'type' => 'integer'
            ],
            [
                'slug' => 'solution',
                'label' => $labels['solution'],
                'type' => 'integer'
            ],
        ];
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                    'prodotti_servizi_destinatari_mm' => Module::t('prodottiservizi', 'Destinations'),
                    'prodotti_servizi_finalita_mm' => Module::t('prodottiservizi', 'Aim'),
                    'attachments' => Module::t('prodottiservizi', 'Attachments'),
                    'icon' => Module::t('prodottiservizi', 'Icon')
                ]);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'fileBehavior' => [
                    'class' => FileBehavior::className()
                ],
            ]);
    }

    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function attributeHints()
    {
        return [
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['prodotti_servizi_destinatari_mm', 'required'],
            ['prodotti_servizi_finalita_mm', 'required'],
            [['attachments'], 'file', 'maxFiles' => 100],
            [['icon'], 'file', 'maxFiles' => 1, 'extensions' => 'jpeg, jpg, png, gif'],
            [['icon'], 'testConfirm', 'skipOnEmpty' => false]
        ]);
    }

    public function testConfirm($attribute){
        $reflectionClass = new \ReflectionClass($this);
        $classname = $reflectionClass->getShortName();
        foreach ((array)$_FILES[$classname]['name'] as $attributeName => $filename) {
            if ($attribute == $attributeName) {
                if (empty($filename)) {
                    if (empty($this->getIcon())) {
                        $this->addError($attribute, Module::t('prodottiservizi', "Il campo immagine è obbligatorio"));
                    }
                }
            }
        }
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }

    /**
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->updateDestinatariMm();
        $this->updateFinalitaMm();
    }

    /**
     * @return void
     */
    public function updateDestinatariMm()
    {
        $this->deleteDestinatariMm();
        $this->createDestinatariMm();
    }

    /**
     * @return void
     */
    public function deleteDestinatariMm()
    {
        $destinatari = ProdottiServiziDestinatariMm::find()->andWhere(['prodotto_id' => $this->id])->all();
        foreach ($destinatari as $destinatario) {
            $destinatario->delete();
        }
    }

    /**
     * @return void
     */
    public function createDestinatariMm()
    {
        if (!empty($this->prodotti_servizi_destinatari_mm)) {
            foreach ($this->prodotti_servizi_destinatari_mm as $value) {
                $destinatariMm = new ProdottiServiziDestinatariMm();
                $destinatariMm->prodotto_id = $this->id;
                $destinatariMm->destinatario_id = $value;
                $destinatariMm->save();
            }
        }
    }

    /**
     * @return void
     */
    public function updateFinalitaMm()
    {
        $this->deleteFinalitaMm();
        $this->createFinalitaMm();
    }

    /**
     * @return void
     */
    public function deleteFinalitaMm()
    {
        $finalita = ProdottiServiziFinalitaMm::find()->andWhere(['prodotto_id' => $this->id])->all();
        foreach ($finalita as $fin) {
            $fin->delete();
        }
    }

    /**
     * @return void
     */
    public function createFinalitaMm()
    {
        if (!empty($this->prodotti_servizi_finalita_mm)) {
            foreach ($this->prodotti_servizi_finalita_mm as $value) {
                $finalitaMm = new ProdottiServiziFinalitaMm();
                $finalitaMm->prodotto_id = $this->id;
                $finalitaMm->finalita_id = $value;
                $finalitaMm->save();
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getfinalitaMm()
    {
        return $this->hasMany(ProdottiServiziFinalitaMm::className(), ['prodotto_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDestinatariMm()
    {
        return $this->hasMany(ProdottiServiziDestinatariMm::className(), ['prodotto_id' => 'id']);
    }

    public function getsoluzione()
    {
        return $this->hasOne(ProdottiSoluzioni::class, ['id' => 'solution']);
    }

    public function getSportelli()
    {
        return self::find()->andWhere(['id_product_root' => $this->id])->all();
    }

    /**
     * Ritorna l'url dell'avatar.
     *
     * @param string $size Dimensione. Default = original.
     * @return string Ritorna l'url.
     */
    public function getAvatarUrl($size = 'original')
    {
        return $this->getIconUrl($size);
    }

    /**
     * Getter for $this->categoryIcon;
     * @return File
     */
    public function getIcon()
    {
        if (empty($this->icon)) {
            $this->icon = $this->hasOneFile('icon')->one();
        }
        return $this->icon;
    }

    /**
     * @param $categoryIcon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getIconUrl($size = 'original', $protected = true, $url = '/img/img_default.jpg')
    {
        $icon = $this->getIcon();
        if (!is_null($icon)) {
            if ($protected) {
                $url = $icon->getUrl($size);
            } else {
                $url = $icon->getWebUrl($size);
            }
        }
        return $url;
    }

    public function afterFind()
    {
        $this->prodotti_servizi_destinatari_mm = ArrayHelper::map(\open20\prodottiservizi\models\ProdottiServiziDestinatariMm::find()->andWhere(['prodotto_id' => $this->id])->asArray()->all(), 'destinatario_id', 'destinatario_id');
        $this->prodotti_servizi_finalita_mm = ArrayHelper::map(\open20\prodottiservizi\models\ProdottiServiziFinalitaMm::find()->andWhere(['prodotto_id' => $this->id])->asArray()->all(), 'finalita_id', 'finalita_id');

        //Icon
        $this->icon = $this->getIcon();

        return parent::afterFind();
    }

    /**
     * Show if the content is visible
     * used in particular to know if attachments file are visible
     * @return boolean
     */
    public function isContentPublic(){
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getGrammar()
    {
        return new ProdottiServiziGrammar();
    }
}