<?php
echo "<?php\n";
?>

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */


use yii\db\Schema;

/**
* Class <?= $migrationName; ?>
*/
class <?= $migrationName; ?> extends \yii\db\Migration {

    public function safeUp() {
    <? foreach ($new_data as $k => $array_insert ): ?>
    $this->batchInsert(
            "<?= $table_name; ?>",
                [<? foreach ($array_insert['columns'] as $k=> $nome_campo ){?> '<?=$nome_campo;?>', <?}?> ],
                [[<? foreach ($array_insert['values'] as $k1 => $valore_campo_cond ){?> '<?=$valore_campo_cond;?>', <?}?>] ]
            );
        <? endforeach; ?>
    }

    public function safeDown() {
    <? foreach ($restore_data as $k => $array_delete ): ?>
        $this->delete(
            "<?= $table_name; ?>",
            [<? foreach ($array_delete['conditions'] as $nome_campo_cond => $valore_campo_cond ){?> "<?=$nome_campo_cond;?>" => "<?=$valore_campo_cond;?>", <?}?> ]
        );
    <? endforeach; ?>
    }
}