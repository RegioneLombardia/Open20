<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

/**
 * Class m170201_101806_insert_community_types
 */
class m170201_101806_insert_community_types extends \yii\db\Migration
{

    const COMMUNITY_TYPES_TABLE = 'community_types';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $communityTypesColumns = Array(
            'id',
            'name',
            'description'
        );

        $rowsToInsert = Array(
            [1, 'Aperta', 'Una community aperta è visibile a tutti e vi ci si può iscrivere liberamente senza richieste di autorizzazione.'],
            [2, 'Riservata', 'Una community riservata è visibile a tutti ma vi ci si può accedere solo in seguito all\'acettazione della richiesta di iscrizione da parte del Community Manager.'],
            [3, 'Chiusa', 'Una community chiusa non è visibile a tutti e vi ci si può accedere solo su invito del Community Manager quando l\'invito viene accettato dall\'utente).']
        );

        $this->execute("SET foreign_key_checks = 0;");
        $this->truncateTable(self::COMMUNITY_TYPES_TABLE);
        $this->batchInsert(self::COMMUNITY_TYPES_TABLE, $communityTypesColumns, $rowsToInsert);
        $this->execute("SET foreign_key_checks = 1;");
        return true;
    }

    public function safeDown()
    {
        $this->execute("SET foreign_key_checks = 0;");
        $this->truncateTable(self::COMMUNITY_TYPES_TABLE);
        $this->execute("SET foreign_key_checks = 1;");
        return true;
    }
}
