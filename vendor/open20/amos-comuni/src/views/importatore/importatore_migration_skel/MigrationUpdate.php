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
    <?php foreach ($new_data as $k => $array_update ): ?>
    $this->update(
            "<?= $table_name; ?>",
                [<?php foreach ($array_update['columns'] as $nome_campo => $valore_campo ){?> "<?=$nome_campo;?>" => "<?=$valore_campo;?>", <?php }?> ],
                [<?php foreach ($array_update['conditions'] as $nome_campo_cond => $valore_campo_cond ){?> "<?=$nome_campo_cond;?>" => "<?=$valore_campo_cond;?>", <?php }?> ]
            );
        <?php endforeach; ?>
    }

    public function safeDown() {
    <?php foreach ($restore_data as $k => $array_restore ): ?>
        $this->update(
            "<?= $table_name; ?>",
            [<?php foreach ($array_restore['columns'] as $nome_campo => $valore_campo ){?> "<?=$nome_campo;?>" => "<?=$valore_campo;?>", <?php }?> ],
            [<?php foreach ($array_restore['conditions'] as $nome_campo_cond => $valore_campo_cond ){?> "<?=$nome_campo_cond;?>" => "<?=$valore_campo_cond;?>", <?php }?> ]
        );
    <?php endforeach; ?>
    }
}