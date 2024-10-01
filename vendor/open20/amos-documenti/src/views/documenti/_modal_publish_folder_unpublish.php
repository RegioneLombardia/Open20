<?php
/**
 * @var $model \open20\amos\documenti\models\Documenti
 */

use \open20\amos\documenti\models\Documenti;
use \open20\amos\core\views\AmosGridView;
use open20\amos\documenti\AmosDocumenti;

?>

<?php $count = $model->publishAndUnpublishFolder(['publishFolderType' => 'unpublish'], true); ?>


<?php if ($count > 0) { ?>
    <p> <?= AmosDocumenti::t('amosdocumenti', "Insieme a questa cartella verranno riportati in bozza altri <strong>{count}</strong> contenuti.
                    <br>Procedere con l'operazione?", ['count' => $count]) ?></p>
<?php } else { ?>
    <p> <?= AmosDocumenti::t('amosdocumenti', "Sei sicuro di voler cambiare stato?") ?></p>
<?php } ?>

