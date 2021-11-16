<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\models\base
 * @category   CategoryName
 */

namespace open20\amos\documenti\models\base;

use open20\amos\core\record\Record;
use open20\amos\documenti\AmosDocumenti;

/**
 * This is the base-model class for table "documenti_categorie".
 *
 * @property    integer $id
 * @property    string $titolo
 * @property    string $sottotitolo
 * @property    string $descrizione_breve
 * @property    string $descrizione
 * @property    integer $filemanager_mediafile_id
 * @property    string $created_at
 * @property    string $updated_at
 * @property    string $deleted_at
 * @property    integer $created_by
 * @property    integer $updated_by
 * @property    integer $deleted_by
 *
 * @property \open20\amos\documenti\models\Documenti $documenti
 * @property \open20\amos\documenti\models\DocumentiCategoryCommunityMm[] $documentiCategoryCommunityMms
 * @property \open20\amos\documenti\models\DocumentiCategoryRolesMm[] $documentiCategoryRolesMms
 */
class DocumentiCategorie extends Record
{
    /**
     * @var AmosDocumenti $documentsModule
     */
    protected $documentsModule = null;

    /**
     */
    public static function tableName()
    {
        return 'documenti_categorie';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->documentsModule = \Yii::$app->getModule(AmosDocumenti::getModuleName());
    }

    /**
     */
    public function rules()
    {
        return [
            [['titolo'], 'required'],
            [['descrizione'], 'string'],
            [['filemanager_mediafile_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['titolo', 'sottotitolo', 'descrizione_breve'], 'string', 'max' => 255]
        ];
    }

    /**
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosDocumenti::t('amosdocumenti', 'Id'),
            'titolo' => AmosDocumenti::t('amosdocumenti', 'Titolo'),
            'sottotitolo' => AmosDocumenti::t('amosdocumenti', 'Sottotitolo'),
            'descrizione_breve' => AmosDocumenti::t('amosdocumenti', 'Descrizione breve'),
            'descrizione' => AmosDocumenti::t('amosdocumenti', 'Descrizione'),
            'filemanager_mediafile_id' => AmosDocumenti::t('amosdocumenti', 'Immagine'),
            'created_at' => AmosDocumenti::t('amosdocumenti', 'Creato il'),
            'updated_at' => AmosDocumenti::t('amosdocumenti', 'Aggiornato il'),
            'deleted_at' => AmosDocumenti::t('amosdocumenti', 'Cancellato il'),
            'created_by' => AmosDocumenti::t('amosdocumenti', 'Creato da'),
            'updated_by' => AmosDocumenti::t('amosdocumenti', 'Aggiornato da'),
            'deleted_by' => AmosDocumenti::t('amosdocumenti', 'Cancellato da')
        ];
    }

    /**
     * Metodo che mette in relazione la categoria con le notizie ad essa associata.
     * Ritorna un ActiveQuery relativo al model Documenti.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumenti()
    {
        return $this->hasMany($this->documentsModule->model('Documenti'), ['documenti_categorie_id' => 'id']);
    }

    /**
     * Relation between category and category-roles mm table.
     * Returns an ActiveQuery related to model NewsCategoryRolesMm.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentiCategoryRolesMms()
    {
        return $this->hasMany($this->documentsModule->model('DocumentiCategoryRolesMm'), ['documenti_categorie_id' => 'id']);
    }

    /**
     * Relation between category and category-roles mm table.
     * Returns an ActiveQuery related to model NewsCategoryCommunityMm.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentiCategoryCommunityMms()
    {
        return $this->hasMany($this->documentsModule->model('DocumentiCategoryCommunityMm'), ['documenti_categorie_id' => 'id']);
    }
}
