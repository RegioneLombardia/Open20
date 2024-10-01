<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloSediLegal;
use open20\amos\organizzazioni\models\ProfiloSediOperative;
use open20\amos\organizzazioni\Module;
use yii\db\ActiveQuery;
use yii\db\Migration;

/**
 * Class m180903_140234_migrate_organizzazioni_profilo_to_profilo_sedi
 */
class m180903_140234_migrate_organizzazioni_profilo_to_profilo_sedi extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $allOk = true;
        $organizations = $this->findAllOrganizations();

        foreach ($organizations as $organization) {

            $mainOperativeHeadquarter = $organization->operativeHeadquarter;
            $ok = true;
            if (is_null($mainOperativeHeadquarter)) {
                $mainOperativeHeadquarter = new ProfiloSediOperative();
                $mainOperativeHeadquarter->is_main = 1;
                $mainOperativeHeadquarter->profilo_id = $organization->id;
                $mainOperativeHeadquarter->address = $organization->indirizzo;
                $mainOperativeHeadquarter->name = $organization->name;
                $mainOperativeHeadquarter->website = $organization->sito_web;
                $mainOperativeHeadquarter->phone = $organization->telefono;
                $mainOperativeHeadquarter->fax = $organization->fax;
                $mainOperativeHeadquarter->email = $organization->email;
                $mainOperativeHeadquarter->pec = $organization->pec;
                $ok = $mainOperativeHeadquarter->save();
                if (!$ok) {
                    MigrationCommon::printCheckStructureError($mainOperativeHeadquarter->attributes, 'Errore durante il salvataggio della sede operativa principale');
                    $allOk = false;
                    continue;
                }
            }

            if ($ok) {
                $mainLegalHeadquarter = $organization->legalHeadquarter;
                if (is_null($mainLegalHeadquarter)) {
                    $mainLegalHeadquarter = new ProfiloSediLegal();
                    $mainLegalHeadquarter->is_main = 1;
                    $mainLegalHeadquarter->profilo_id = $organization->id;
                    $mainLegalHeadquarter->name = $organization->name;
                    $mainLegalHeadquarter->website = $organization->sito_web;
                    if ($organization->la_sede_legale_e_la_stessa_del) {
                        $mainLegalHeadquarter->address = $organization->indirizzo;
                        $mainLegalHeadquarter->phone = $organization->telefono;
                        $mainLegalHeadquarter->fax = $organization->fax;
                        $mainLegalHeadquarter->email = $organization->email;
                        $mainLegalHeadquarter->pec = $organization->pec;
                    } else {
                        $mainLegalHeadquarter->address = $organization->sede_legale_indirizzo;
                        $mainLegalHeadquarter->phone = $organization->sede_legale_telefono;
                        $mainLegalHeadquarter->fax = $organization->sede_legale_fax;
                        $mainLegalHeadquarter->email = $organization->sede_legale_email;
                        $mainLegalHeadquarter->pec = $organization->sede_legale_pec;
                    }
                    $ok = $mainLegalHeadquarter->save();
                    if (!$ok) {
                        MigrationCommon::printCheckStructureError($mainLegalHeadquarter->attributes, 'Errore durante il salvataggio della sede legale principale');
                        $allOk = false;
                        continue;
                    }
                }
            }
        }

        if ($allOk) {
            MigrationCommon::printConsoleMessage("Migrazione sedi principali avvenuta con successo");
        }

        return $allOk;
    }

    /**
     * @return Profilo[]
     */
    private function findAllOrganizations()
    {
        /** @var ActiveQuery $query */
        $query = Profilo::find();
        $organizations = $query->all();
        return $organizations;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180903_140234_migrate_organizzazioni_profilo_to_profilo_sedi cannot be reverted.\n";

        return false;
    }
}
