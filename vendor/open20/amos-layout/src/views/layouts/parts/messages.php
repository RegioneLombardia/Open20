<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use yii\bootstrap\Alert;

$FlashMsg = Yii::$app->session->getAllFlashes();
?>

<?php if(!empty($FlashMsg)): ?>
<div class="container-messages container">
<?php endif; ?>
    <?php
    foreach ($FlashMsg as $type => $message):

        if (!is_array($message)) :
            echo Alert::widget([
                'options' => [
                    'class' => 'alert-' . $type,
                    'role' => 'alert'
                ],
                'body' => $message,
            ]);
        else:
            foreach ($message as $ty => $msg):
                echo Alert::widget([
                    'options' => [
                        'class' => 'alert-' . $type,
                        'role' => 'alert'
                    ],
                    'body' => $msg,
                ]);
            endforeach;
        endif;
    endforeach;
    ?>
<?php if(!empty($FlashMsg)): ?>
</div>
<?php endif; ?>
