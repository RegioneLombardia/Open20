<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\models
 * @category   CategoryName
 */

namespace open20\amos\documenti\models;

use yii\helpers\ArrayHelper;
use open20\amos\attachments\behaviors\FileBehavior;

/**
 * This is the model class for table "documenti_categorie".
 */
class DocumentiCategorie extends \open20\amos\documenti\models\base\DocumentiCategorie
{

    /**
     * @var mixed $file File.
     */
    public $file;

    /**
     * @var $documentMainFile
     */
    public $documentCategoryImage;
    public $documentiCategoryCommunities;
    public $documentiCategoryRoles;
    public $visibleToCommunityRole;

    /**
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['documentCategoryImage'], 'file', 'extensions' => 'jpeg, jpg, png, gif','maxFiles' => 1],
            [['documentiCategoryRoles' ,'documentiCategoryCommunities','visibleToCommunityRole'], 'safe']
        ]);
    }

    /**
     * Ritorna l'url dell'avatar.
     *
     * @param string $dimension Dimensione. Default = small.
     * @return string Ritorna l'url.
     */
    public function getAvatarUrl($dimension = 'small')
    {
        $url = '/img/img_default.jpg';
        if (!is_null($this->documentCategoryImage)) {
            $url = $this->documentCategoryImage->getUrl($dimension, false, true);
        }
        return $url;
    }

    /**
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->documentCategoryImage = $this->getDocumentCategoryImage()->one();
    }

    /**
     * Getter for $this->documentCategoryImage;
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentCategoryImage()
    {
        return $this->hasOneFile('documentCategoryImage');
    }


    /**
     *
     */
    public function saveDocumentiCategorieCommunityMm(){
        /** @var DocumentiCategoryCommunityMm $documentiCategoryCommunityMmModel */
        $documentiCategoryCommunityMmModel = $this->documentsModule->createModel('DocumentiCategoryCommunityMm');
        $documentiCategoryCommunityMmModel::deleteAll(['documenti_categorie_id' => $this->id]);
        foreach ((Array) $this->documentiCategoryCommunities as $community_id){
            /** @var DocumentiCategoryCommunityMm $documentiCommunityMm */
            $documentiCommunityMm = $this->documentsModule->createModel('DocumentiCategoryCommunityMm');
            $documentiCommunityMm->documenti_categorie_id = $this->id;
            $documentiCommunityMm->community_id = $community_id;

            $documentiCommunityMm->visible_to_cm = 0;
            if(array_search('COMMUNITY_MANAGER', $this->visibleToCommunityRole) !== false){
                $documentiCommunityMm->visible_to_cm = 1;
            }
            $documentiCommunityMm->visible_to_participant = 0;
            if(array_search('PARTICIPANT', $this->visibleToCommunityRole) !== false){
                $documentiCommunityMm->visible_to_participant = 1;

            }
            $documentiCommunityMm->save();
        }
    }

    /**
     *  load documentiCategoryCommunities for Select2
     */
    public function loadDocumentiCategoryCommunities(){
        $community_ids = [];
        foreach ( (Array) $this->documentiCategoryCommunityMms as $categoryCommunityMms){
            $community_ids []= $categoryCommunityMms->community_id;
            if($categoryCommunityMms->visible_to_cm){
                $this->visibleToCommunityRole []= 'COMMUNITY_MANAGER';
            }
            if($categoryCommunityMms->visible_to_participant){
                $this->visibleToCommunityRole []= 'PARTICIPANT';
            }
        };
        $this->documentiCategoryCommunities = $community_ids;

    }

    /**
     *
     */
    public function saveDocumentiCategorieRolesMm(){
        /** @var DocumentiCategoryRolesMm $documentiCategoryRolesMmModel */
        $documentiCategoryRolesMmModel = $this->documentsModule->createModel('DocumentiCategoryRolesMm');
        $documentiCategoryRolesMmModel::deleteAll(['documenti_categorie_id' => $this->id]);
        foreach ((Array) $this->documentiCategoryRoles as $role){
            /** @var DocumentiCategoryRolesMm $newsCommunityMm */
            $newsCommunityMm = $this->documentsModule->createModel('DocumentiCategoryRolesMm');
            $newsCommunityMm->documenti_categorie_id = $this->id;
            $newsCommunityMm->role = $role;
            $newsCommunityMm->save();
        }
    }

    /**
     *  load documentiCategoryRoles for Select2
     */
    public function loadDocumentiCategoryRoles(){
        $roles = [];
        foreach ($this->documentiCategoryRolesMms as $categoryRolesMms){
            $roles []= $categoryRolesMms->role;
        };
        $this->documentiCategoryRoles = $roles;
    }
}
