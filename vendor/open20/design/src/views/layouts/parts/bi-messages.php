<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use yii\helpers\VarDumper;

$FlashMsg = Yii::$app->session->getAllFlashes(true);
?>

<?php if (!empty($FlashMsg)) : ?> 

    <div class="container-messages container">
    <?php endif; ?>
    <?php
    foreach ($FlashMsg as $type => $message) :
        if (!is_array($message)) : ?>

            <div class="alert alert-<?= $type ?> alert-dismissible fade show mt-2" role="alert">
                <?= $message ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php else :
            // due gestioni diverse per gli alert, se modale $message diventa un array con quello che serve per attivare una modale
            if ($type == 'modal'): 
                echo $this->render("." . DIRECTORY_SEPARATOR . "bi-modal-alert", [
                    'arrayContent' => $message[0],
                ]); 
            else:
            foreach ($message as $msg) : ?>

                <div class="alert alert-<?= $type ?> alert-dismissible fade show mt-2" role="alert">
                    <?= $msg ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php 
                
            endforeach;
            endif;
        endif;
    endforeach;
    ?>
    <?php if (!empty($FlashMsg)) : ?>
    </div>
<?php endif; ?>