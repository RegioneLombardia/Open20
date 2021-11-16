<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\utilities
 * @category   CategoryName
 */

namespace open20\amos\community\utilities;

use open20\amos\admin\models\UserProfile;
use open20\amos\community\AmosCommunity;
use open20\amos\community\exceptions\CommunityException;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityReport;
use open20\amos\core\user\User;
use moonland\phpexcel\Excel;
use yii\base\Model;
use yii\base\BaseObject;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class ReportsUtility
 * @package open20\amos\community\utilities
 */
class ReportsUtility extends BaseObject
{
    /**
     * @var null|\amos\statistics\Module $statisticsModule
     */
    private $statisticsModule;
    
    /**
     * @var null|\open20\amos\cwh\AmosCwh $cwhModule
     */
    private $cwhModule;
    
    /**
     * @var null|\open20\amos\documenti\AmosDocumenti $documentsModule
     */
    private $documentsModule;
    
    /**
     * This method checks if there is the minimum modules used in some reports.
     * Fore each one of the modules there is a class private variable that contains the module object.
     * The checked modules are statistics, cwh and documenti.
     * @throws CommunityException
     */
    private function fileSizeAndTypesModulesChecks()
    {
        $this->statisticsModule = \Yii::$app->getModule('statistics');
        if (is_null($this->statisticsModule)) {
            throw new CommunityException(AmosCommunity::t('amoscommunity', 'statistics module not found'));
        }
        
        $this->cwhModule = \Yii::$app->getModule('cwh');
        if (is_null($this->cwhModule)) {
            throw new CommunityException(AmosCommunity::t('amoscommunity', 'CWH module not found'));
        }
        
        $this->documentsModule = \Yii::$app->getModule('documenti');
        if (is_null($this->documentsModule)) {
            throw new CommunityException(AmosCommunity::t('amoscommunity', 'documenti module not found'));
        }
    }
    
    /**
     * @param Community $community
     * @return string
     * @throws CommunityException
     */
    public function uploadedFileSizesByCommunity($community)
    {
        $this->fileSizeAndTypesModulesChecks();
        
        $documentsClassName = \open20\amos\documenti\models\Documenti::className();
        if (!in_array($documentsClassName, $this->cwhModule->modelsEnabled)) {
            throw new CommunityException(AmosCommunity::t('amoscommunity', '{className} classname not enabled in CWH module', ['className' => $documentsClassName]));
        }
        
        $cwhConfigContentDocuments = \open20\amos\cwh\models\CwhConfigContents::findOne(['classname' => $documentsClassName]);
        if (is_null($cwhConfigContentDocuments)) {
            throw new CommunityException(AmosCommunity::t('amoscommunity', 'CwhConfigContents not found by classname {className}', ['className' => $documentsClassName]));
        }
        
        $statType = \amos\statistics\statistics\attachments\AttachmentsStatsOpt::ATTACHMENTS_STAT_TYPE_FILE_SIZE;
        $communityAndSubcommunitiesIds = $this->getCommunityAndSubcommunitiesIds($community);
        $cwhConfigContentIds = [$cwhConfigContentDocuments->id];
        $excelColumns = $this->getCommunityAndSubcommunitiesDocSizeColumns();
        $excel = $this->statisticsModule->makeAttachmentsStatisticsExcel(
            $statType,
            Community::getCwhConfigId(),
            $community->title,
            $communityAndSubcommunitiesIds,
            $cwhConfigContentIds,
            $excelColumns
        );
        
        return $excel;
    }
    
    /**
     * @return string
     * @throws CommunityException
     */
    public function uploadedFileSizes()
    {
        $this->fileSizeAndTypesModulesChecks();
        
        $documentsClassName = \open20\amos\documenti\models\Documenti::className();
        if (!in_array($documentsClassName, $this->cwhModule->modelsEnabled)) {
            throw new CommunityException(AmosCommunity::t('amoscommunity', '{className} classname not enabled in CWH module', ['className' => $documentsClassName]));
        }
        
        $cwhConfigContentDocuments = \open20\amos\cwh\models\CwhConfigContents::findOne(['classname' => $documentsClassName]);
        if (is_null($cwhConfigContentDocuments)) {
            throw new CommunityException(AmosCommunity::t('amoscommunity', 'CwhConfigContents not found by classname {className}', ['className' => $documentsClassName]));
        }
        
        $statType = \amos\statistics\statistics\attachments\AttachmentsStatsOpt::ATTACHMENTS_STAT_TYPE_FILE_SIZE;
        $cwhConfigContentIds = [$cwhConfigContentDocuments->id];
        $excelColumns = $this->getCommunityAndSubcommunitiesDocSizeColumns();
        $allCommunities = CommunityUtil::getAllValidatedCommunity();
        
        $models = [];
        foreach ($allCommunities as $community) {
            $communityAndSubcommunitiesIds = $this->getCommunityAndSubcommunitiesIds($community);
            $modelsPartial = $this->statisticsModule->getAttachmentsStatisticsData(
                $statType,
                Community::getCwhConfigId(),
                $community->title,
                $communityAndSubcommunitiesIds,
                $cwhConfigContentIds,
                $excelColumns
            );
            $models = ArrayHelper::merge($models, $modelsPartial);
        }
        
        $excel = $this->makeExcel($models, $excelColumns);
        
        return $excel;
    }
    
    /**
     * @param Community $community
     * @return string
     * @throws CommunityException
     */
    public function uploadedFileTypesByCommunity($community)
    {
        $this->fileSizeAndTypesModulesChecks();
        
        $documentsClassName = \open20\amos\documenti\models\Documenti::className();
        if (!in_array($documentsClassName, $this->cwhModule->modelsEnabled)) {
            throw new CommunityException(AmosCommunity::t('amoscommunity', '{className} classname not enabled in CWH module', ['className' => $documentsClassName]));
        }
        
        $cwhConfigContentDocuments = \open20\amos\cwh\models\CwhConfigContents::findOne(['classname' => $documentsClassName]);
        if (is_null($cwhConfigContentDocuments)) {
            throw new CommunityException(AmosCommunity::t('amoscommunity', 'CwhConfigContents not found by classname {className}', ['className' => $documentsClassName]));
        }
        
        $statType = \amos\statistics\statistics\attachments\AttachmentsStatsOpt::ATTACHMENTS_STAT_TYPE_FILE_TYPES;
        $communityAndSubcommunitiesIds = $this->getCommunityAndSubcommunitiesIds($community);
        $cwhConfigContentIds = [$cwhConfigContentDocuments->id];
        $excelColumns = $this->getCommunityAndSubcommunitiesDocTypesColumns();
        
        $excel = $this->statisticsModule->makeAttachmentsStatisticsExcel(
            $statType,
            Community::getCwhConfigId(),
            $community->title,
            $communityAndSubcommunitiesIds,
            $cwhConfigContentIds,
            $excelColumns
        );
        
        return $excel;
    }
    
    /**
     * @return string
     * @throws CommunityException
     */
    public function uploadedFileTypes()
    {
        $this->fileSizeAndTypesModulesChecks();
        
        $documentsClassName = \open20\amos\documenti\models\Documenti::className();
        if (!in_array($documentsClassName, $this->cwhModule->modelsEnabled)) {
            throw new CommunityException(AmosCommunity::t('amoscommunity', '{className} classname not enabled in CWH module', ['className' => $documentsClassName]));
        }
        
        $cwhConfigContentDocuments = \open20\amos\cwh\models\CwhConfigContents::findOne(['classname' => $documentsClassName]);
        if (is_null($cwhConfigContentDocuments)) {
            throw new CommunityException(AmosCommunity::t('amoscommunity', 'CwhConfigContents not found by classname {className}', ['className' => $documentsClassName]));
        }
        
        $statType = \amos\statistics\statistics\attachments\AttachmentsStatsOpt::ATTACHMENTS_STAT_TYPE_FILE_TYPES;
        $cwhConfigContentIds = [$cwhConfigContentDocuments->id];
        $excelColumns = $this->getCommunityAndSubcommunitiesDocSizeColumns();
        $allCommunities = CommunityUtil::getAllValidatedCommunity();
        
        $models = [];
        foreach ($allCommunities as $community) {
            $communityAndSubcommunitiesIds = $this->getCommunityAndSubcommunitiesIds($community);
            $modelsPartial = $this->statisticsModule->getAttachmentsStatisticsData(
                $statType,
                Community::getCwhConfigId(),
                $community->title,
                $communityAndSubcommunitiesIds,
                $cwhConfigContentIds,
                $excelColumns
            );
            $models = ArrayHelper::merge($models, $modelsPartial);
        }
        
        $excel = $this->makeExcel($models, $excelColumns);
        
        return $excel;
    }
    
    /**
     * @param Model[] $models
     * @param array $columns
     * @return string
     */
    public function makeExcel($models, $columns)
    {
        return Excel::export([
            'models' => $models,
            'columns' => $columns
        ]);
    }
    
    /**
     * @return Community[]
     */
    private function getAllCommunity()
    {
        return Community::find()->andWhere(['parent_id' => null])->andWhere(['context' => Community::className()])->all();
    }
    
    /**
     * @param Community $community
     * @return int[]
     */
    public function getCommunityAndSubcommunitiesIds($community)
    {
        return array_unique($this->getCommunityAndSubcommunitiesIdsRecursive($community));
    }
    
    /**
     * @param Community $community
     * @return int[]
     */
    private function getCommunityAndSubcommunitiesIdsRecursive($community)
    {
        $subCommunities = $community->subcommunities;
        $communityIds = [$community->id];
        
        if (!empty($subCommunities)) {
            foreach ($subCommunities as $subCommunity) {
                $communityIds[] = $subCommunity->id;
            }
            foreach ($subCommunities as $subCommunity) {
                $communityIds = ArrayHelper::merge($communityIds, $this->getCommunityAndSubcommunitiesIdsRecursive($subCommunity));
            }
        }
        
        return $communityIds;
    }

//    /**
//     * @param int[] $communityAndSubcommunitiesIds
//     */
//    private function searchDocuments($communityAndSubcommunitiesIds)
//    {
//        /** @var ActiveQuery $query */
//        $query = Documenti::find();
//        $classname = Documenti::className();
//        $moduleCwh = \Yii::$app->getModule('cwh');
//        $cwhActiveQuery = null;
//        if (!is_null($moduleCwh)) {
//            /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
//            $moduleCwh->setCwhScopeFromSession();
//            $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery(
//                $classname, [
//                'queryBase' => $query,
//                'networkIds' => $communityAndSubcommunitiesIds
//            ]);
//        }
//        $isSetCwh = $this->isSetCwh($moduleCwh, $classname);
//        if ($isSetCwh) {
//            $cwhActiveQuery->andWhere(['is_folder' => Documenti::IS_DOCUMENT]);
//            $query = $cwhActiveQuery->filterByPublicationNetwork(Community::getCwhConfigId(), $communityAndSubcommunitiesIds);
//        } else {
//            $query->andWhere(['is_folder' => Documenti::IS_DOCUMENT]);
//            $query->andWhere([
//                'status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO,
//            ]);
//        }
//
//        return $query->all();
//    }
//
//    /**
//     * @param AmosModule $moduleCwh
//     * @param string $classname
//     * @return bool
//     */
//    private function isSetCwh($moduleCwh, $classname)
//    {
//        if (isset($moduleCwh) && in_array($classname, $moduleCwh->modelsEnabled)) {
//            return true;
//        } else {
//            return false;
//        }
//    }
//
//    /**
//     * @param int $size Size is in bytes
//     * @return string
//     */
//    private function getSizeValueToView($size)
//    {
//        $sizeConverted = 0;
//        $unit = '';
//        if ($size > (1024 * 1024 * 1024)) {
//            $sizeConverted = $size / 1024 / 1024 / 1024;
//            $unit = 'G';
//        } elseif ($size > (1024 * 1024)) {
//            $sizeConverted = $size / 1024 / 1024;
//            $unit = 'M';
//        } elseif ($size > 1024) {
//            $sizeConverted = $size / 1024;
//            $unit = 'K';
//        }
//        $roundedSize = round($sizeConverted, 2);
//        return $roundedSize . ' ' . $unit . 'B';
//    }
    
    /**
     * @return array
     */
    public function getCommunityAndSubcommunitiesDocSizeColumns()
    {
        return [
            [
                'attribute' => 'title',
                'header' => AmosCommunity::t('amoscommunity', '#community_and_subcommunities_doc_size_report_column_1'),
            ],
            [
                'attribute' => 'reportValue',
                'header' => AmosCommunity::t('amoscommunity', '#community_and_subcommunities_doc_size_report_column_2'),
            ]
        ];
    }
    
    /**
     * @return array
     */
    public function getCommunityAndSubcommunitiesDocTypesColumns()
    {
        return [
            [
                'attribute' => 'title',
                'header' => AmosCommunity::t('amoscommunity', '#community_and_subcommunities_doc_types_report_column_1'),
            ],
            [
                'attribute' => 'reportValue',
                'header' => AmosCommunity::t('amoscommunity', '#community_and_subcommunities_doc_types_report_column_2'),
            ]
        ];
    }
    
    /**
     * @param Community $community
     * @return array
     */
    public function getCommunityAndSubcommunitiesUsersData($community)
    {
        $communityUsers = $this->getCommunityAndSubcommunitiesUsersDataRecursive($community);
        $userIds = array_keys($communityUsers);
        /** @var ActiveQuery $query */
        $query = UserProfile::find();
        $query->andWhere(['id' => $userIds]);
        $query->orderBy([
            'cognome' => SORT_ASC,
            'nome' => SORT_ASC
        ]);
        $users = $query->all();
        return $users;
    }
    
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAllCommunityAndSubcommunitiesUsersData()
    {
        $query_cm = \open20\amos\community\models\CommunityUserMm::find()->select('user_id');
        /** @var ActiveQuery $query */
        $query = UserProfile::find();
        $query->andWhere(['id' => $query_cm]);
        $query->orderBy([
            'cognome' => SORT_ASC,
            'nome' => SORT_ASC
        ]);
        $users = $query->all();
        return $users;
    }
    
    /**
     * @param Community $community
     * @return mixed
     */
    private function getCommunityAndSubcommunitiesUsersDataRecursive($community)
    {
        $communityUsers = $community->communityUsers;
        $users = [];
        foreach ($communityUsers as $user) {
            /** @var User $user */
            $users[$user->id] = $user;
        }
        $subCommunities = $community->subcommunities;
        
        if (!empty($subCommunities)) {
            foreach ($subCommunities as $subCommunity) {
                $users = ArrayHelper::merge($users, $this->getCommunityAndSubcommunitiesUsersDataRecursive($subCommunity));
            }
        }
        
        return $users;
    }
    
    /**
     * @return array
     */
    public function getCommunityAndSubcommunitiesUsersColumns()
    {
        return [
            [
                'attribute' => 'nome',
                'header' => AmosCommunity::t('amoscommunity', '#community_and_subcommunities_users_report_column_1'),
            ],
            [
                'attribute' => 'cognome',
                'header' => AmosCommunity::t('amoscommunity', '#community_and_subcommunities_users_report_column_2'),
            ],
            [
                'attribute' => 'user.email',
                'header' => AmosCommunity::t('amoscommunity', '#community_and_subcommunities_users_report_column_3'),
            ]
        ];
    }
    
    /**
     * @param Community $community
     * @return mixed
     */
    public function getCommunityFrequencyData($community)
    {
        $models = [];
        $reportModel = new CommunityReport();
        $reportModel->id = $community->id;
        $reportModel->title = $community->name;
        $reportModel->reportValue = $community->hits;
        $models[] = $reportModel;
        return $models;
    }
    
    /**
     * @return array
     */
    public function getAllCommunityFrequencyData()
    {
        $models = [];
        $communities = $this->getAllCommunity();
        foreach ($communities as $community) {
            sleep(1);
            $reportModel = new CommunityReport();
            $reportModel->id = $community->id;
            $reportModel->title = $community->name;
            $reportModel->reportValue = $community->hits;
            $models[] = $reportModel;
        }
        return $models;
    }
    
    /**
     * @return array
     */
    public function getCommunityFrequencyColumns()
    {
        return [
            [
                'attribute' => 'title',
                'header' => AmosCommunity::t('amoscommunity', '#access_frequency_report_column_1'),
            ],
            [
                'attribute' => 'reportValue',
                'header' => AmosCommunity::t('amoscommunity', '#access_frequency_report_column_2'),
            ]
        ];
    }
}
