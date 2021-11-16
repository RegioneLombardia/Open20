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
    <? foreach ($new_data as $k => $array_update ): ?>
    $this->update(
            "<?= $table_name; ?>",
                [<? foreach ($array_update['columns'] as $nome_campo => $valore_campo ){?> "<?=$nome_campo;?>" => "<?=$valore_campo;?>", <?}?> ],
                [<? foreach ($array_update['conditions'] as $nome_campo_cond => $valore_campo_cond ){?> "<?=$nome_campo_cond;?>" => "<?=$valore_campo_cond;?>", <?}?> ]
            );
        <? endforeach; ?>
    }

    public function safeDown() {
    <? foreach ($restore_data as $k => $array_restore ): ?>
        $this->update(
            "<?= $table_name; ?>",
            [<? foreach ($array_restore['columns'] as $nome_campo => $valore_campo ){?> "<?=$nome_campo;?>" => "<?=$valore_campo;?>", <?}?> ],
            [<? foreach ($array_restore['conditions'] as $nome_campo_cond => $valore_campo_cond ){?> "<?=$nome_campo_cond;?>" => "<?=$valore_campo_cond;?>", <?}?> ]
        );
    <? endforeach; ?>
    }
}