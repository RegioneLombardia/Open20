<?php

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\utility\Module;
use open20\amos\utility\utility\UtilityUtility;
use open20\amos\utility\utility\CreateUsersUtility;
use yii\helpers\Html;
use open20\amos\admin\AmosAdmin;

/**
 * @var $this \yii\web\View
 * @var $moduleNames array
 * @var $update array
 */

$this->title = Module::t('amosutility', 'Upgrade');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="default-index">
    <div>
        <?php if ($update['status'] != 'ready') : ?>
            <h4>Stato</h4>
            <h6 data-state="<?= $update['status']; ?>">L'aggiornamento è stato richiesto e sarà disponibile a breve</h6>

            <script type="text/javascript">
                setTimeout(function () {
                    window.location.reload();
                }, 15000);
            </script>
        <?php else : ?>
            <h4>Stato</h4>
            <h6>Aggiornamento Pronto, creato in data: <?= $update['date']; ?></h6>
            <br/>
            <a class="btn btn-info" href="start">Avvia Aggiornamento</a>
            <a class="btn btn-danger" href="index?force=true">Richiedi Nuovo Aggiornamento</a>
        <?php endif; ?>
    </div>
</div>