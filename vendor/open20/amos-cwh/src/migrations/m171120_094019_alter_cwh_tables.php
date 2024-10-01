<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh\migrations
 * @category   CategoryName
 */

use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\models\CwhConfigContents;
use open20\amos\cwh\models\CwhPubblicazioni;
use open20\amos\cwh\models\CwhPubblicazioniCwhNodiEditoriMm;
use open20\amos\cwh\models\CwhPubblicazioniCwhNodiValidatoriMm;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m171120_094019_alter_cwh_tables
 */
class m171120_094019_alter_cwh_tables extends Migration
{

    const CONFIG_CONTENTS = 'cwh_config_contents';
    const CONFIG = 'cwh_config';
    const PUBBLICAZIONI = 'cwh_pubblicazioni';
    const PUBBLICAZIONI2 = 'cwh_publication';
    const VALIDATORI = 'cwh_pubblicazioni_cwh_nodi_validatori_mm';
    const VALIDATORI2 = 'cwh_validators';
    const EDITORI = 'cwh_pubblicazioni_cwh_nodi_editori_mm';
    const EDITORI2 = 'cwh_editors';
    const CWH_AUTH = 'cwh_auth_assignment';
    const TAG_OWNER = 'cwh_tag_owner_interest_mm';

    public function up()
    {

        $table = Yii::$app->db->schema->getTableSchema(self::CONFIG_CONTENTS);
        // Column doesn't exist then create column
        if (!isset($table->columns['tablename'])) {
            $this->addColumn(
                self::CONFIG_CONTENTS,
                'tablename',
                $this->char(255)->comment('Name of the content table')->after('id')
            );
        }

        if (Yii::$app->db->schema->getTableSchema(self::PUBBLICAZIONI2) === null) {
            $this->createTable(self::PUBBLICAZIONI2, [
                'id' => $this->primaryKey(),
                'cwh_config_contents_id' => $this->integer()->notNull()->comment('Content type configuration record id'),
                'content_id' => $this->integer()->notNull()->comment('Record id in the content table eg. news, documents...'),
                'cwh_regole_pubblicazione_id' => $this->integer()->notNull(),
                'created_at' => $this->dateTime()->null()->defaultValue(null)->comment('Created at'),
                'updated_at' => $this->dateTime()->null()->defaultValue(null)->comment('Updated at'),
                'deleted_at' => $this->dateTime()->null()->defaultValue(null)->comment('Deleted at'),
                'created_by' => $this->integer()->null()->defaultValue(null)->comment('Created by'),
                'updated_by' => $this->integer()->null()->defaultValue(null)->comment('Updated by'),
                'deleted_by' => $this->integer()->null()->defaultValue(null)->comment('Deleted by')
            ], $this->db->driverName === 'mysql'
                ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1'
                : null
            );
        }

        if (Yii::$app->db->schema->getTableSchema(self::VALIDATORI2) === null) {
            $this->createTable(self::VALIDATORI2, [
                'id' => $this->primaryKey(),
                'cwh_pubblicazioni_id' => $this->integer()->notNull()->comment('id of cwh_pubblicazioni'),
                'cwh_config_id' => $this->integer()->notNull()->comment('Network type configuration record id'),
                'cwh_network_id' => $this->integer()->notNull()->comment('Record id in the network table eg. community, organizations...'),
                'cwh_nodi_id' => $this->string(255)->comment('Old key of cwh_nodi_view kept to be backward compatible, remove once unused'),
                'created_at' => $this->dateTime()->null()->defaultValue(null)->comment('Created at'),
                'updated_at' => $this->dateTime()->null()->defaultValue(null)->comment('Updated at'),
                'deleted_at' => $this->dateTime()->null()->defaultValue(null)->comment('Deleted at'),
                'created_by' => $this->integer()->null()->defaultValue(null)->comment('Created by'),
                'updated_by' => $this->integer()->null()->defaultValue(null)->comment('Updated by'),
                'deleted_by' => $this->integer()->null()->defaultValue(null)->comment('Deleted by')
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        }

        if (Yii::$app->db->schema->getTableSchema(self::EDITORI2) === null) {
            $this->createTable(self::EDITORI2, [
                'id' => $this->primaryKey(),
                'cwh_pubblicazioni_id' => $this->integer()->notNull()->comment('id of cwh_pubblicazioni'),
                'cwh_config_id' => $this->integer()->notNull()->comment('Network type configuration record id'),
                'cwh_network_id' => $this->integer()->notNull()->comment('Record id in the network table eg. community, organizations...'),
                'cwh_nodi_id' => $this->string(255)->comment('Old key of cwh_nodi_view kept to be backward compatible, remove once unused'),
                'created_at' => $this->dateTime()->null()->defaultValue(null)->comment('Created at'),
                'updated_at' => $this->dateTime()->null()->defaultValue(null)->comment('Updated at'),
                'deleted_at' => $this->dateTime()->null()->defaultValue(null)->comment('Deleted at'),
                'created_by' => $this->integer()->null()->defaultValue(null)->comment('Created by'),
                'updated_by' => $this->integer()->null()->defaultValue(null)->comment('Updated by'),
                'deleted_by' => $this->integer()->null()->defaultValue(null)->comment('Deleted by')
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        }

        try {
            $configContents = CwhConfigContents::find()->all();
            $configContentArray = [];
            /** @var CwhConfigContents $configContent */
            foreach ($configContents as $configContent) {
                $configContent->detachBehaviors();
                $objectClassname = $configContent->classname;
                $tablename = $objectClassname::tableName();
                $configContentArray[$tablename] = $configContent->id;
                $configContent->tablename = $tablename;
                $configContent->save(false);
            }

            $configNetworks = (new Query())->from(self::CONFIG)->all();
            $configNetworkArray = [];
            /** @var CwhConfig $configNetwork */
            foreach ($configNetworks as $configNetwork) {
                $configNetworkArray[$configNetwork['tablename']] = $configNetwork['id'];
            }

            $publicationQuery = (new Query())->from(self::PUBBLICAZIONI);
            $publications = $publicationQuery->all();
            /** @var CwhPubblicazioni $publication */
            foreach ($publications as $publication) {
                $oldKey = $publication['id'];
                $arrayId = explode('-', $oldKey);
                $contentId = $arrayId[1];
                $configContentId = $configContentArray[$arrayId[0]];
                $this->insert(self::PUBBLICAZIONI2, [
                    'content_id' => $contentId,
                    'cwh_config_contents_id' => $configContentId,
                    'cwh_regole_pubblicazione_id' => $publication['cwh_regole_pubblicazione_id'],
                    'created_at' => $publication['created_at'],
                    'created_by' => $publication['created_by'],
                    'updated_at' => $publication['updated_at'],
                    'updated_by' => $publication['updated_by'],
                    'deleted_at' => $publication['deleted_at'],
                    'deleted_by' => $publication['deleted_by'],
                ]);
            }
            $this->createIndex('cwh_publication_regole_pubblicazione_idx', self::PUBBLICAZIONI2, 'cwh_regole_pubblicazione_id');
            $this->createIndex('cwh_publication_content_id_idx', self::PUBBLICAZIONI2, 'content_id');
            $this->createIndex('cwh_publication_cwh_config_contents_idx', self::PUBBLICAZIONI2, 'cwh_config_contents_id');
            $this->createIndex('cwh_publication_content_id_config_contents_idx', self::PUBBLICAZIONI2, ['content_id', 'cwh_config_contents_id']);
            $this->createIndex('cwh_publication_regole_content_config_contents_idx', self::PUBBLICAZIONI2, ['cwh_regole_pubblicazione_id', 'content_id', 'cwh_config_contents_id']);

            $this->addForeignKey('fk_cwh_publication_cwh_config_contents_id', self::PUBBLICAZIONI2, 'cwh_config_contents_id', self::CONFIG_CONTENTS, 'id');
            $this->addForeignKey('fk_cwh_publication_regole_pubblicazione', self::PUBBLICAZIONI2, 'cwh_regole_pubblicazione_id', 'cwh_regole_pubblicazione', 'id');

            $this->renameTable(self::PUBBLICAZIONI, self::PUBBLICAZIONI . '_old');
            $this->renameTable(self::PUBBLICAZIONI2, self::PUBBLICAZIONI);


            $cwhValidators = (new Query())->from(self::VALIDATORI)->all();
            /** @var CwhPubblicazioniCwhNodiValidatoriMm $validator */
            foreach ($cwhValidators as $validator) {
                $oldKey = $validator['cwh_pubblicazioni_id'];
                $arrayContentId = explode('-', $oldKey);
                $contentId = $arrayContentId[1];
                $configContentId = $configContentArray[$arrayContentId[0]];
                $networkKey = $validator['cwh_nodi_id'];
                $networkArrayId = explode('-', $networkKey);
                $configNetworkId = $configNetworkArray[$networkArrayId[0]];
                $networkId = $networkArrayId[1];
                $cwhPublication = (new Query())->from(self::PUBBLICAZIONI)->andWhere(['cwh_config_contents_id' => $configContentId, 'content_id' => $contentId])->one();
                if (!is_null($cwhPublication['id'])) {
                    $this->insert(self::VALIDATORI2, [
                        'cwh_pubblicazioni_id' => $cwhPublication['id'],
                        'cwh_config_id' => $configNetworkId,
                        'cwh_network_id' => $networkId,
                        'cwh_nodi_id' => $networkKey,
                        'created_at' => $validator['created_at'],
                        'created_by' => $validator['created_by'],
                        'updated_at' => $validator['updated_at'],
                        'updated_by' => $validator['updated_by'],
                        'deleted_at' => $validator['deleted_at'],
                        'deleted_by' => $validator['deleted_by'],
                    ]);
                }
            }
            $this->createIndex('cwh_validators_cwh_pubblicazioni_idx', self::VALIDATORI2, 'cwh_pubblicazioni_id');
            $this->createIndex('cwh_validators_cwh_network_config_idx', self::VALIDATORI2, 'cwh_config_id');
            $this->createIndex('cwh_validators_cwh_network_idx', self::VALIDATORI2, 'cwh_network_id');
            $this->createIndex('cwh_validators_pubblicazioni_config_network_deleted_at_idx', self::VALIDATORI2, ['cwh_pubblicazioni_id', 'cwh_config_id', 'cwh_network_id', 'deleted_at']);
            $this->addForeignKey('fk_cwh_validators_cwh_network_config_id', self::VALIDATORI2, 'cwh_config_id', self::CONFIG, 'id');
            $this->addForeignKey('fk_cwh_validators_cwh_pubblicazioni_id', self::VALIDATORI2, 'cwh_pubblicazioni_id', 'cwh_pubblicazioni', 'id');
            $this->renameTable(self::VALIDATORI, self::VALIDATORI . '_old');
            $this->renameTable(self::VALIDATORI2, self::VALIDATORI);

            $cwhEditors = (new Query())->from(self::EDITORI)->all();
            /** @var CwhPubblicazioniCwhNodiEditoriMm $editor */
            foreach ($cwhEditors as $editor) {
                $oldKey = $editor['cwh_pubblicazioni_id'];
                $arrayContentId = explode('-', $oldKey);
                $contentId = $arrayContentId[1];
                $configContentId = $configContentArray[$arrayContentId[0]];
                $networkKey = $editor['cwh_nodi_id'];
                $networkArrayId = explode('-', $networkKey);
                $configNetworkId = $configNetworkArray[$networkArrayId[0]];
                $networkId = $networkArrayId[1];
                $cwhPublication = (new Query())->from(self::PUBBLICAZIONI)->andWhere(['cwh_config_contents_id' => $configContentId, 'content_id' => $contentId])->one();
                if (!is_null($cwhPublication['id'])) {
                    $this->insert(self::EDITORI2, [
                        'cwh_pubblicazioni_id' => $cwhPublication['id'],
                        'cwh_config_id' => $configNetworkId,
                        'cwh_network_id' => $networkId,
                        'cwh_nodi_id' => $networkKey,
                        'created_at' => $editor['created_at'],
                        'created_by' => $editor['created_by'],
                        'updated_at' => $editor['updated_at'],
                        'updated_by' => $editor['updated_by'],
                        'deleted_at' => $editor['deleted_at'],
                        'deleted_by' => $editor['deleted_by'],
                    ]);
                }
            }
            $this->createIndex('cwh_editors_cwh_pubblicazioni_idx', self::EDITORI2, 'cwh_pubblicazioni_id');
            $this->createIndex('cwh_editors_cwh_network_config_idx', self::EDITORI2, 'cwh_config_id');
            $this->createIndex('cwh_editors_cwh_network_idx', self::EDITORI2, 'cwh_network_id');
            $this->createIndex('cwh_editors_pubblicazioni_config_network_deleted_at_idx', self::EDITORI2, ['cwh_pubblicazioni_id', 'cwh_config_id', 'cwh_network_id', 'deleted_at']);
            $this->addForeignKey('fk_cwh_editors_cwh_network_config_id', self::EDITORI2, 'cwh_config_id', self::CONFIG, 'id');
            $this->addForeignKey('fk_cwh_editors_cwh_pubblicazioni_id', self::EDITORI2, 'cwh_pubblicazioni_id', 'cwh_pubblicazioni', 'id');

            $this->renameTable(self::EDITORI, self::EDITORI . '_old');
            $this->renameTable(self::EDITORI2, self::EDITORI);

            $this->addColumn(self::CWH_AUTH, 'cwh_config_id', $this->integer()->notNull()->comment('Network type configuration record id')->after('user_id'));
            $this->addColumn(self::CWH_AUTH, 'cwh_network_id', $this->integer()->notNull()->comment('Record id in the network table eg. community, organizations...')->after('cwh_config_id'));

            $cwhAuths = (new Query())->from(self::CWH_AUTH)->groupBy('cwh_nodi_id')->select('cwh_nodi_id')->column();
            /** @var string $networkKey eg. 'community-13' */
            foreach ($cwhAuths as $networkKey) {
                $networkArrayId = explode('-', $networkKey);
                $configNetworkId = isset($configNetworkArray[$networkArrayId[0]]) ? $configNetworkArray[$networkArrayId[0]] : 0;
                $networkId = $networkArrayId[1];
                $this->update(self::CWH_AUTH, ['cwh_config_id' => $configNetworkId, 'cwh_network_id' => $networkId], ['cwh_nodi_id' => $networkKey]);
            }

            $this->createIndex('cwh_tag_owner_interest_mm_tag_record_deleted_at_idx', self::TAG_OWNER, ['tag_id', 'record_id', 'deleted_at']);

            return true;
        } catch (\yii\base\Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
    }

    public function down()
    {
        try {
            $this->dropIndex('cwh_tag_owner_interest_mm_tag_record_deleted_at_idx', self::TAG_OWNER);

            $this->dropColumn(self::CWH_AUTH, 'cwh_config_id');
            $this->dropColumn(self::CWH_AUTH, 'cwh_network_id');

            $this->dropTable(self::EDITORI);
            $this->renameTable(self::EDITORI . '_old', self::EDITORI);

            $this->dropTable(self::VALIDATORI);
            $this->renameTable(self::VALIDATORI . '_old', self::VALIDATORI);

            $this->dropTable(self::PUBBLICAZIONI);
            $this->renameTable(self::PUBBLICAZIONI . '_old', self::PUBBLICAZIONI);

            $this->dropColumn(self::CONFIG_CONTENTS, 'tablename');

            return true;
        } catch (\yii\base\Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
    }

}
