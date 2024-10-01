<?php

use app\modules\utility\Utility;
use open20\amos\admin\models\UserProfile;
use open20\amos\tag\models\Tag;
use yii\data\ActiveDataProvider;

/**
 * @var \open20\amos\admin\models\UserProfile $model
 * @var \open20\amos\admin\AmosAdmin $adminModule
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProviderUsers
 */
?>

<style>
    .alert-modal .modal-dialog .modal-content .modal-body .redactor-box p{
        padding-left: 0 !important;
    }
</style>

<?php
if (!Yii::$app->user->isGuest) {
    $usersCount = 0;
    $myInterestsTagIds = Utility::getUserTagInterests(Yii::$app->user->id)->select('tag.id');
    if (!empty($myInterestsTagIds)) {
        $usersQuery = Utility::getUserProfilesByTags($myInterestsTagIds, 16, new \yii\db\Expression('RAND()'));
        if (!empty($usersQuery)) {
            $dataProviderUsers = new ActiveDataProvider([
                'query' => $usersQuery,
                'pagination' => false
            ]);
            $usersCount = $dataProviderUsers->getTotalCount();
        }
    }

    if ($usersCount > 0) { ?>

        <div class="carousel-users-with-my-interests-container">

            <h3 class="text-uppercase mb-4"><?= Yii::t('amosadmin', 'Utenti con i miei interessi') ?></h3>

            <div id="usersCarousel" class="it-carousel-wrapper it-carousel-landscape-abstract-four-cols">
                <div class="it-carousel-all owl-carousel owl-user">
                    <?php
                    foreach ($dataProviderUsers->getModels() as $model) { ?>
                        <div class="it-single-slide-wrapper">

                            <?= $this->render('_itemCarouselUsersWithMyInterestsDesign', [
                                'model' => $model,
                                'adminModule' => $adminModule
                            ]); ?>

                        </div>
                    <?php } ?>
                </div>
            </div>

        </div>

    <?php } ?>

<?php } ?>

<?php 
$jsUser = <<<JS
    
    var count = '{$usersCount}';
    if (count < 4) {
        $('#usersCarousel').owlCarousel({
           margin: 12,
           items: '{$usersCount}',
           nav: false,
           pagination: false,
           dots: false,
           loop: true
       });
   }
    // Get the modal
    var modal = $('.alert-modal');

    // Remove the modal from the current div
    modal.detach();

    // Append the modal to the new div
    modal.appendTo('.carousel-users-with-my-interests-container');
    
JS;

$this->registerJs($jsUser);