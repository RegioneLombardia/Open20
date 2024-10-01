<?php
echo "<?php\n";
?>

/**
 * Aria S.p.A.
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
    <?php foreach ($new_data as $k => $array_insert ): ?>
    $this->batchInsert(
            "<?= $table_name; ?>",
                [<?php foreach ($array_insert['columns'] as $k=> $nome_campo ){?> '<?=$nome_campo;?>', <?php }?> ],
                [[<?php foreach ($array_insert['values'] as $k1 => $valore_campo_cond ){?> '<?=$valore_campo_cond;?>', <?php }?>] ]
            );
        <?php endforeach; ?>
    }

    public function safeDown() {
    <?php foreach ($restore_data as $k => $array_delete ): ?>
        $this->delete(
            "<?= $table_name; ?>",
            [<?php foreach ($array_delete['conditions'] as $nome_campo_cond => $valore_campo_cond ){?> "<?=$nome_campo_cond;?>" => "<?=$valore_campo_cond;?>", <?php }?> ]
        );
    <?php endforeach; ?>
    }
}