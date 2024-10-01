
<?= \open20\amos\core\views\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_item_participant',
    'masonry' => false,
    'viewParams' => ['participants' => $participants, 'participants_user_profile_ids' => $participants_user_profile_ids, 'readOnly' => $readOnly,],

]) ?>
