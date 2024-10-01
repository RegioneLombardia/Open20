<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */

use yii\db\Migration;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiCartellePath;


class m220713_144900_fill_create_documenti_cartelle_path extends Migration
{

    public function up()
    {
        Yii::$app->db->createCommand()->truncateTable(DocumentiCartellePath::tableName())->execute();
        $documenti = Documenti::find()->andWhere(['deleted_at'=>null])->all();
        if(!empty($documenti)){
            foreach($documenti as $doc){
                $result = DocumentiCartellePath::generatePath($doc,1,[]);
                DocumentiCartellePath ::savePath($result,$doc->id);
            }
        }
     
    }


    public function down()
    {
        Yii::$app->db->createCommand()->truncateTable(DocumentiCartellePath::tableName())->execute();

    }
}