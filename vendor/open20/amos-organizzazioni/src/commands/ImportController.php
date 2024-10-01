<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\commands
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\commands;

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\organizzazioni\components\ImportManager;
use open20\amos\organizzazioni\components\OrganizationsPlacesComponents;
use open20\amos\organizzazioni\models\OrganizationsPlaces;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloImport;
use open20\amos\organizzazioni\models\ProfiloSediLegal;
use open20\amos\organizzazioni\models\ProfiloSediOperative;
use open20\amos\organizzazioni\Module;
use open20\amos\organizzazioni\utility\OrganizzazioniUtility;
use yii\console\Controller;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class ImportController
 * @package open20\amos\organizzazioni\commands
 */
class ImportController extends Controller
{

    /**
     * @var Module $organizzazioniModule
     */
    protected $organizzazioniModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->organizzazioniModule = Module::instance();
        parent::init();
    }

    public function actionImportOrganizations()
    {
        $notImportedOrganizations = $this->getNotImported();

        $keyForImport = $this->organizzazioniModule->importManager->getImportKey();
        $requiredFields = $this->organizzazioniModule->importManager->getRequiredFields();

        if (!empty($notImportedOrganizations)) {
            foreach ($notImportedOrganizations as $k => $organizationToImport) {
                $checkRequired = true;
                foreach ($requiredFields as $requiredField) {
                    if (
                        !isset($organizationToImport[$requiredField])
                        || empty($organizationToImport[$requiredField])
                    ) {
                        $checkRequired = false;
                    }
                }

                if (!$checkRequired) {
                    MigrationCommon::printCheckStructureError(
                        $organizationToImport, 
                        Module::t('amosorganizzazioni', '#import_organizations_missing_required_field')
                    );
                    continue;
                }

                if (!empty($organizationToImport[$keyForImport])) {
                    $organization = $this->findOrganizationByImportKey($organizationToImport);
                    if (!empty($organization)) {
                        MigrationCommon::printConsoleMessage(
                            Module::t('amosorganizzazioni',
                                '#import_organizations_already_present', [
                                    'fieldName' => $keyForImport,
                                    'fieldValue' => $organizationToImport[$keyForImport]
                                ]
                            )
                        );
                        
                        continue;
                    }

                    /** @var ProfiloImport $organization */
                    $organization = $this->organizzazioniModule->createModel('ProfiloImport');

                    $ok = $this->beforeLoadOrganizationValues(
                        $organization,
                        $organizationToImport
                    );
                    if (!$ok) {
                        MigrationCommon::printCheckStructureError(
                            $organizationToImport,
                            Module::t('amosorganizzazioni', '#import_organizations_before_load_error')
                        );
                        
                        continue;
                    }
                    
                    $ok = $this->loadOrganizationValues(
                        $organization,
                        $organizationToImport
                    );
                    if (!$ok) {
                        MigrationCommon::printCheckStructureError(
                            $organizationToImport,
                            Module::t('amosorganizzazioni', '#import_organizations_load_error')
                        );
                        
                        continue;
                    }

                    $ok = $this->afterLoadOrganizationValues(
                        $organization,
                        $organizationToImport
                    );
                    if (!$ok) {
                        MigrationCommon::printCheckStructureError(
                            $organizationToImport,
                            Module::t('amosorganizzazioni', '#import_organizations_after_load_error')
                        );
                        
                        continue;
                    }

                    if ($this->organizzazioniModule->enableWorkflow) {
                        $organization->setInitialStatus();
                    }

                    if (!$this->organizzazioniModule->forceSameSede) {
                        $organization->la_sede_legale_e_la_stessa_del = 1;
                    }

                    $this->beforeSaveOrganization($organization, $organizationToImport);
                    $organization->indirizzo = null;
                    $organization->imported_at = date('Y-m-d H:i:s');
                    if ($organization->save(false)) {
                        $this->afterSaveOrganization($organization, $organizationToImport);

                        // Find place id
                        $place_id = $this->createPlaceId($organizationToImport);

                        $this->beforeCreateMainHeadquarters(
                            $organization,
                            $organizationToImport
                        );

                        /** @var ProfiloSediOperative $mainOperativeHeadquarter */
                        $mainOperativeHeadquarter = $this->organizzazioniModule->createModel('ProfiloSediOperative');
                        $mainOperativeHeadquarter->is_main = 1;
                        $mainOperativeHeadquarter->name = $organization->name;
                        $mainOperativeHeadquarter->email = $organizationToImport['email'];
                        $mainOperativeHeadquarter->profilo_id = $organization->id;
                        $mainOperativeHeadquarter->address = $place_id;
                        $mainOperativeHeadquarter->save(false);

                        /** @var ProfiloSediLegal $mainLegalHeadquarter */
                        $mainLegalHeadquarter = $this->organizzazioniModule->createModel('ProfiloSediLegal');
                        $mainLegalHeadquarter = OrganizzazioniUtility::copyOperativeToLegalHeadquarterValues($mainOperativeHeadquarter, $mainLegalHeadquarter);
                        $mainLegalHeadquarter->save(false);

                        $this->afterCreateMainHeadquarters($organization, $organizationToImport);
                        $this->updateImportedRow($organization, $organizationToImport);
                    }
                }
            }
        }
    }

    /**
     * @return array
     */
    protected function getNotImported()
    {
        $query = new Query();
        $query->from(ImportManager::IMPORT_TABLE);
        $query->andWhere(['OR',
            ['importato' => 0],
            ['is', 'importato', null],
            ['importato' => '']
        ]);

        return $query->all();
    }

    /**
     * @param array $organizationToImport
     * @return ProfiloImport|null
     * @throws \yii\base\InvalidConfigException
     */
    protected function findOrganizationByImportKey($organizationToImport)
    {
        /** @var ProfiloImport $profiloModel */
        $profiloModel = $this->organizzazioniModule->createModel('ProfiloImport');

        $keyForImport = $this->organizzazioniModule->importManager->getImportKey();

        /** @var ActiveQuery $query */
        $query = $profiloModel::find();

        /** @var ProfiloImport $organization */
        $organization = $query
            ->andWhere([$keyForImport => $organizationToImport[$keyForImport]])
            ->one();

        return $organization;
    }

    /**
     * @param Profilo $organization
     * @param array $organizationToImport
     * @return mixed
     */
    protected function loadOrganizationValues($organization, $organizationToImport)
    {
        $loadValues['ProfiloImport'] = $organizationToImport;
        return $organization->load($loadValues);
    }

    /**
     * @param array $organizationToImport
     * @return bool|string
     * @throws \yii\base\InvalidConfigException
     */
    protected function createPlaceId($organizationToImport)
    {
        $geocodeResponse = $this->organizzazioniModule->createModel('OrganizationsPlaces');
        $geocodeResponse->address = $organizationToImport['indirizzo'];
        
        if (isset($organizationToImport['comune'])) {
            $geocodeResponse->city = $organizationToImport['comune'];
        }
        
        if (isset($organizationToImport['sigla_provincia'])) {
            $geocodeResponse->province = $organizationToImport['sigla_provincia'];
        }
        
        if (isset($organizationToImport['stato'])) {
            $geocodeResponse->country = $organizationToImport['stato'];
        } else {
            $geocodeResponse->country = 'Italy';
        }
        
        $geocodeResponseComplete = OrganizationsPlacesComponents::getGeocodeString($geocodeResponse);

        $place_id = OrganizationsPlacesComponents::getGoogleResponseByGeocodeString($geocodeResponseComplete);

        return $place_id;
    }

    /**
     * @param ProfiloImport $organization
     * @param array $organizationToImport
     */
    protected function updateImportedRow($organization, $organizationToImport)
    {
        $keyForImport = $this->organizzazioniModule->importManager->getImportKey();

        \Yii::$app->db->createCommand()->update(
            ImportManager::IMPORT_TABLE,
            [
                'importato' => 1
            ],
            [
                $keyForImport => $organization->{$keyForImport}
            ]
        )->execute();
    }

    /**
     * @param ProfiloImport $organization
     * @param array $organizationToImport
     * @return bool
     */
    protected function beforeLoadOrganizationValues($organization, $organizationToImport)
    {
        return true;
    }

    /**
     * @param ProfiloImport $organization
     * @param array $organizationToImport
     * @return bool
     */
    protected function afterLoadOrganizationValues($organization, $organizationToImport)
    {
        return true;
    }

    /**
     * @param ProfiloImport $organization
     * @param array $organizationToImport
     */
    protected function beforeSaveOrganization($organization, $organizationToImport)
    {
    }

    /**
     * @param ProfiloImport $organization
     * @param array $organizationToImport
     */
    protected function afterSaveOrganization($organization, $organizationToImport)
    {
    }

    /**
     * @param ProfiloImport $organization
     * @param array $organizationToImport
     */
    protected function beforeCreateMainHeadquarters($organization, $organizationToImport)
    {
    }

    /**
     * @param ProfiloImport $organization
     * @param array $organizationToImport
     */
    protected function afterCreateMainHeadquarters($organization, $organizationToImport)
    {
    }

}