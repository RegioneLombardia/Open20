<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile\help
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 * @var bool $spediscicredenzialienable
 */

$modalId = 'send-recovery-password-modal-id';

$spedisciCredenzialiLink = [
    '/'.AmosAdmin::getModuleName().'/security/spedisci-credenziali',
    'id' => $model->id
];

$baseModalContent = Html::tag('div',
    AmosAdmin::t('amosadmin', 'Sei sicuro di voler inviare le credenziali? Sarà inviata una mail contenente un link per modificare le credenziali. Vuoi continuare?'),
    ['class' => 'send-recovery-password pull-right m-15-0']
);

$footerText = Html::tag('div',
    Html::a(
        Html::tag('span', null,
            ['class' => 'glyphicon glyphicon-ban-circle']
        ) .
        AmosAdmin::t('amosadmin', 'Annulla'),
        null,
        [
            'id' => 'undo',
            'class' => 'btn btn-secondary',
            'data-dismiss' => 'modal'
        ]) .
    Html::a(
        Html::tag('span', null,
            ['class' => 'glyphicon glyphicon-ok']
        ) .
        AmosAdmin::t('amosadmin', 'Ok'),
        $spedisciCredenzialiLink,
        [
            'id' => 'confirm',
            'class' => 'btn btn-navigation-primary'
        ]
    )
);

ModalUtility::amosModal([
    'id' => $modalId,
    'headerText' => AmosAdmin::t('amosadmin', "Conferma"),
    'modalBodyContent' => $baseModalContent,
    'footerText' => $footerText,
    'containerOptions' => ['class' => 'modal-utility bootstrap-dialog type-warning fade']
]);

?>

<?= Html::a(
    AmosIcons::show('email') . AmosAdmin::t('amosadmin', 'Spedisci credenziali'),
    $spedisciCredenzialiLink,
    [
        'class' => 'btn btn-navigation-primary btn-spedisci-credenziali ',
        'data-toggle' => 'modal',
        'data-target' => '#' . $modalId
    ]
); ?>
