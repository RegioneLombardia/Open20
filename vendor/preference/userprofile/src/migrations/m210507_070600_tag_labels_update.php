<?php

use yii\db\Migration;
use preference\userprofile\models\Tag;

/**
 * Class m210507_070600_tag_labels_update
 */
class m210507_070600_tag_labels_update extends Migration
{

    public function safeUp()
    {
        $this->update('tag', [
            'descrizione' => 'Servizi, incentivi, eventi e opportunità formative messi a disposizione da Regione Lombardia per l\'innovazione delle aziende lombarde.'],
            ['codice' => 'pctopic_impresa_1']);
        return true;
    }

    public function safeDown()
    {
        $this->update('tag',
            ['descrizione' => 'Servizi, incentivi, eventi e opportunità formative messi a disposizione da per l\'innovazione delle aziende lombarde.'],
            ['codice' => 'pctopic_impresa_1']);
        return true;
    }

}
