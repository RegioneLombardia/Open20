<?php
/**
 * @var \open20\amos\core\user\User $user
 * @var string $confirmUrl
 * @var string $previousUrl
 * @var bool $autoRemove
 */
?>

<h3><?= $user->getUserProfile()->one()->getNomeCognome(); ?></h3>
<?php if($autoRemove) : ?>
<h3><?= \open20\amos\events\AmosEvents::txt('Vuoi davvero rimuovere la partecipazione all\'evento per te e i tuoi accompagnatori?') ?></h3>
<?php else : ?>
<h3><?= \open20\amos\events\AmosEvents::txt('Vuoi davvero rimuovere la partecipazione all\'evento per il partecipante e i suoi accompagnatori?') ?></h3>
<?php endif; ?>
<br /><br />
<?= \yii\helpers\Html::tag(
    'div',
    \yii\helpers\Html::a(
        \Yii::t('amoscore', 'No'),
        $previousUrl,
        [
            'class' => 'btn btn-secondary'
        ]
    ) . ' ' . \yii\helpers\Html::a(
        \Yii::t('amoscore', 'Yes'),
        $confirmUrl,
        [
            'class' => 'btn btn-primary'
        ]
    ),
    [
        'class' => 'pull-right'
    ]
    );
?>