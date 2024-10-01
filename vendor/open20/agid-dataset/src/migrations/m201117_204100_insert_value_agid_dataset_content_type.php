<?php

use yii\db\Migration;

class m201117_204100_insert_value_agid_dataset_content_type extends Migration
{

    public $rows_agid_dataset_type = [
        ["name" => "Agricoltura, pesca, silvicoltura e prodotti alimentari"],
        ["name" => "Economia e Finanze"],
        ["name" => "Istruzione, cultura e sport"],
        ["name" => "Energia"],
        ["name" => "Ambiente"],
        ["name" => "Governo e settore pubblico"],
        ["name" => "Salute"],
        ["name" => "Tematiche internazionali"],
        ["name" => "Giustizia, sistema giuridico e sicurezza pubblica"],
        ["name" => "Regioni e città"],
        ["name" => "Popolazione e società"],
        ["name" => "Scienza e tecnologia"],
        ["name" => "Trasporti"]
    ];

    public $rows_agid_dataset_content_type = [
        ["name" => "Dataset"]
    ];

    public function safeUp()
    {

        /**
         * public void batchInsert ( $table, $columns, $rows )
         * 
         * $table | string | The table that new rows will be inserted into.
         * $columns | array | The column names.
         * $rows | array| The rows to be batch inserted into the table
         */
        $this->batchInsert('agid_dataset_type', ['name'], $this->rows_agid_dataset_type);
        $this->batchInsert('agid_dataset_content_type', ['name'], $this->rows_agid_dataset_content_type);

    }


    public function safeDown()
    {
       
            
        /**
         * public void delete ( $table, $condition = '', $params = [] )
         * 
         * $table | string | The table where the data will be deleted from.
         * $condition | array | string | The conditions that will be put in the WHERE part.
         *                              Please refer to yii\db\Query::where() on how to specify conditions.
         * $params | array | The parameters to be bound to the query.
         * 
         * @return void
         */

        $this->delete('agid_dataset_type', ['name' => $this->rows_agid_dataset_type]);
        $this->delete('agid_dataset_content_type', ['name' => $this->rows_agid_dataset_content_type]);
    }
}