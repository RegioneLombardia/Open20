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
 * @var $command string
 */

$this->title = Module::t('amosutility', 'Upgrade');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="default-index">
    <div>
        Per completare l'aggiornamento sarà necessario eseguire il seguente comando:
        <pre><?= $command; ?></pre>

        <p>
            Puoi provare ad eseguirlo con <a href="<?= \yii\helpers\Url::to(['/utility/console', 'cmd' => $command]); ?>">l'utility di console</a>
        </p>
        <i>* in base ai permessi su filesystem è possibile che il comando funzioni solo da riga di comando con utente privilegiato</i>
    </div>
</div>