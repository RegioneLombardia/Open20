<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\utility
 * @category   CategoryName
 */

namespace open20\amos\documenti\utility;

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\core\views\grid\ActionColumn;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiCategorie;
use open20\amos\documenti\models\DocumentiCategoryRolesMm;
use yii\base\BaseObject;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class DocumentsUtility
 * @package open20\amos\documenti\utility
 */
class DocumentsUtility extends BaseObject
{
    /**
     * @param Documenti $model
     * @param bool $onlyIconName If true return only the icon name ready to use in AmosIcon::show method.
     * @return string
     */
    public static function getDocumentIcon($model, $onlyIconName = false)
    {
        if ($model->is_folder) {
            $folderIconName = 'folder-open';
            if ($onlyIconName) {
                return $folderIconName;
            }
            if ($model->drive_file_id) {
                return AmosIcons::show($folderIconName, ['class' => 'icon_widget_graph'], 'dash') . AmosIcons::show('google-drive', ['class' => 'google-sync'], 'am');
            }
            return AmosIcons::show($folderIconName, ['class' => 'icon_widget_graph'], 'dash');
        }
        
        if (!(empty($model->link_document))) {
            $linkIcon = 'doc-www';
            if ($onlyIconName === false) {
                $linkIcon = AmosIcons::show($linkIcon, ['class' => 'icon_widget_graph'], 'dash');
            }
            
            return $linkIcon;
        }
        
        $iconName = 'file-o';
        $documentFile = $model->getDocumentMainFile();
        if (!is_null($documentFile)) {
            $docExtension = strtolower($documentFile->type);
            
            $extensions = [
                'doc'  => 'file-doc-o',
                'docx' => 'file-docx-o',
                'rtf'  => 'file-rtf-o',
                'xls'  => 'file-xls-o',
                'xlsx' => 'file-xlsx-o',
                'txt'  => 'file-txt-o',
                'pdf'  => 'file-pdf-o2',
            ];
            
            if (isset($extensions[$docExtension])) {
                $iconName = $extensions[$docExtension];
            }
        }
        
        if ($onlyIconName) {
            return $iconName;
        }

        if ($model->drive_file_id) {
            return AmosIcons::show($iconName, ['class' => 'icon_widget_graph'], 'dash') . AmosIcons::show('google-drive', ['class' => 'google-sync'], 'am');
        }
        else {
            return AmosIcons::show($iconName, ['class' => 'icon_widget_graph'], 'dash');
        }
    }

    /**
     * @param $idUserIdList
     * @param $subject
     * @param $text
     * @param array $files
     * @param bool $queue
     */
    public static function sendEmail($idUserIdList, $subject, $text, $files = [], $queue = false)
    {
        $emailList = [];
        foreach ($idUserIdList as $id) {
            $user = User::findOne($id);
            if ($user) {
                $emailList [] = $user->email;
            }
        }

        $emaiFrom = \Yii::$app->params['supportEmail'];

        /** @var  $email Email */
        try {
            $emailModule = \Yii::$app->getModule('email');
            $email = new Email();
//                if(!empty($groupsModule->layoutEmail)) {
//                    $emailModule->defaultLayout = $groupsModule->layoutEmail;
//                }
            $sent = $email->sendMail(
                $emaiFrom,
                $emailList,
                $subject,
                $text,
                $files,
                [],
                [],
                0,
                $queue
            );
            return $sent;
        } catch (\Exception $e) {
            return false;
//                pr($e->getTrace());
        }
    }

    /**
     * This method returns an array with document categories ids used in the documents present in the platform.
     * @return int[]
     */
    public static function getDocumentsCategoriesIds()
    {
        $query = new Query();
        $query->select(['documenti_categorie_id'])->distinct();
        $query->from(Documenti::tableName());
        $query->where(['deleted_at' => null]);
        $documentsCategoriesIds = $query->column();
        return $documentsCategoriesIds;
    }

    /**
     * This method returns an array with document categories ready for select used in the documents present in the platform.
     * @return array
     */
    public static function getSearchCategoriesReadyForSelect()
    {
        $documentsCategoriesIds = self::getDocumentsCategoriesIds();
        $documentsCategoriesForSelect = [];
        /** @var AmosDocumenti $documentsModule */
        $documentsModule = AmosDocumenti::instance();
        foreach ($documentsCategoriesIds as $documentCategoryId) {
            /** @var DocumentiCategorie $documentiCategorieModel */
            $documentiCategorieModel = $documentsModule->createModel('DocumentiCategorie');
            $documentCategory = $documentiCategorieModel::findOne($documentCategoryId);
            if (!is_null($documentCategory)) {
                $documentsCategoriesForSelect[$documentCategory->id] = $documentCategory->titolo;
            }
        }
        return $documentsCategoriesForSelect;
    }

    /**
     * Return the options for the specified action column button.
     * @param string $type
     * @return array
     */
    public static function getGridActionColumnsButtonsOptions($type)
    {
        $options = [];
        $actionColumnsObject = new ActionColumn();

        switch ($type) {
            case 'view':
                $options = $actionColumnsObject->viewOptions;
                break;
            case 'update':
                $options = $actionColumnsObject->updateOptions;
                break;
            case 'delete':
                $options = $actionColumnsObject->deleteOptions;
                break;
        }

        return $options;
    }

    public static function resetRoutesDocumentsExplorer() {
        \Yii::$app->session->set('stanzePath', []);
        \Yii::$app->session->set('foldersPath', []);
    }

    /**
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDocumentiCategorie()
    {
        /** @var DocumentiCategorie $documentiCategorieModel */
        $documentiCategorieModel = AmosDocumenti::instance()->createModel('DocumentiCategorie');

        /** @var ActiveQuery $query */
        $query = $documentiCategorieModel::find();
        if(\Yii::$app->getModule('documenti')->filterCategoriesByRole){
            //check enabled role for category active - user can publish under a category if there's at least one match betwwn category and user roles
            $query->joinWith('documentiCategoryRolesMms')->innerJoin('auth_assignment', 'item_name='. DocumentiCategoryRolesMm::tableName().'.role and user_id ='. \Yii::$app->user->id);
        }
        if(\Yii::$app->getModule('documenti')->enableCategoriesForCommunity){
            $moduleCwh = \Yii::$app->getModule('cwh');
            $moduleCommunity = \Yii::$app->getModule('community');

            if($moduleCwh && $moduleCommunity) {
                $scope = $moduleCwh->getCwhScope();
                if (!empty($scope) && isset($scope['community'])) {
                    $isCommunityManager = DocumentsUtility::isCommunityManager($scope['community']);
                    if(\Yii::$app->getModule('documenti')->showAllCategoriesForCommunity) {
                        $query->joinWith('documentiCategoryCommunityMms')->andWhere([
                            'OR',
                            ['IS', 'community_id', null],
                            ['community_id' => $scope['community']]
                        ]);

                    } else {
                        $query2 = clone $query;
                        $count = $query2->joinWith('documentiCategoryCommunityMms')
                            ->andWhere(['community_id' => $scope['community']])->count();

                        // if you have at least a category for this community show only them
                        if($count > 0) {
                            $query->joinWith('documentiCategoryCommunityMms')
                                ->andWhere(['community_id' => $scope['community']]);
                            if(!$isCommunityManager){
                                $query->andWhere(['visible_to_participant' => true]);
                            }
                        } else {
                            // If you don't have categories for this specific community, show all the categories the the aren't assigned to some community
                            $query->joinWith('documentiCategoryCommunityMms')
                                ->andWhere(['IS', 'community_id', NULL]);
                        }
                    }
                }
                else {
                    // if you are on dashboard
                    $query->joinWith('documentiCategoryCommunityMms')->andWhere(['IS', 'community_id', null]);
                }
            }
            //check enabled role for category active - user can publish under a category if there's at least one match betwwn category and user roles
        }
        return $query;
    }


    /**
     * @param $community_id
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function isCommunityManager($community_id){
        $count = \open20\amos\community\models\CommunityUserMm::find()
            ->andWhere(['community_id' => $community_id])
            ->andWhere(['user_id' => \Yii::$app->user->id])
            ->andWhere(['role' => \open20\amos\community\models\Community::ROLE_COMMUNITY_MANAGER])->count();
        
        return ($count > 0);

    }
}
