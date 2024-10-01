<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 */

use open20\amos\attachments\models\AttachFileCategorie;
use yii\db\Migration;

/**
 * Class m230911_151238_populate_table_attach_file_categorie_assets_mm
 */
class m230911_151238_populate_table_attach_file_categorie_assets_mm extends Migration
{
    const NOME_TABELLA = 'attach_file_categorie_assets_mm';

    private function getIds()
    {
        return AttachFileCategorie::find()->select('id')
            ->andWhere(['titolo' => ['Normative', 'Documenti']])->column();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (is_null($this->db->schema->getTableSchema(self::NOME_TABELLA,
            true))) {
            echo "Non trovo la tabella '" . self::NOME_TABELLA . "'";
            return false;
        }
        
        //recupero gli id delle categorie a cui associare gli asset
        $idsCat = $this->getIds();
        if (empty($idsCat)) {
            echo "Categorie non trovate";
            return false;
        }

        //associo gli asset alle categorie
        $adesso = date('Y-m-d H:i:s');
        foreach ($idsCat as $idCat) {
            $this->insert(self::NOME_TABELLA, [
                'attach_file_categorie_id' => $idCat,
                'classe_asset' =>
                    'open20\prodottiservizi\models\ProdottiServizi',
                'created_at' => $adesso,
                'updated_at' => $adesso,
                'created_by' => 1,
                'updated_by' => 1
            ]);
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if (is_null($this->db->schema->getTableSchema(self::NOME_TABELLA,
            true))) {
//            echo "Non trovo la tabella '".self::NOME_TABELLA."'";
            return true;
        }

        //recupero gli id delle categorie di cui eliminare l'associazione
        $idsCat = $this->getIds();
        if (empty($idsCat)) {
//            echo "Categorie non trovate";
//            return false;
            return true;
        }

        $this->delete(self::NOME_TABELLA, ['and',
            ['attach_file_categorie_id' => $idsCat],
            ['classe_asset' => 'open20\prodottiservizi\models\ProdottiServizi']
        ]);
        return true;
    }


}