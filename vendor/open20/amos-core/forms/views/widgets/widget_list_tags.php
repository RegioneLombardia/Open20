<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\forms\views\widgets
 * @category   CategoryName
 */
use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\tag\models\Tag;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

/**
 * @var \yii\web\View $this
 * @var \open20\amos\tag\models\Tag $root
 * @var \yii\base\View $this
 * @var array $allRootTags
 * @var array $allTags
 */
if ($viewFilesCounter) {
    $js = <<<JS
	var filesQuantity = "$filesQuantity";
    
    var section_title = $("#section-tags").find("h2");

    section_title.append(" (" + filesQuantity + ")");
    
    if(filesQuantity == 0){
        section_title.addClass("section-disabled");
    }
JS;

    $this->registerJs($js
            , \yii\web\View::POS_END);
}

$userid = \Yii::$app->user->id;

$js = <<<JS
	removeTagMyProfile = function(id){
	var userId = $userid;
	$.ajax({
		url : '/amosadmin/user-profile-ajax/remove-tag',
		type: 'GET',
		data: {
			'id': id,
			'user_id': userId
		},
		dataType: 'json',
		success: function (data) {
			location.reload();
		},
		error: function (request, error)
		{
			console.log(error);
		}
	});
  }
  
  
addTagMyProfile = function(id){
	var userId = $userid;
	$.ajax({
		url : '/amosadmin/user-profile-ajax/add-tag',
		type: 'GET',
		data: {
			'id': id,
			'user_id': userId
		},
		dataType: 'json',
		success: function (data) {
			location.reload();
		},
		error: function (request, error)
		{
			console.log(error);
		}
	});
  }
  
JS;
$this->registerJs($js
        , \yii\web\View::POS_END);

$dataProvider->pagination->setPageSize($pageSize);
$searchModule = Yii::$app->getModule('search');
$currentModule = Yii::$app->controller->module->getUniqueId();

function getTagOfInterest() {
    $userProfileClass = AmosAdmin::instance()->model('UserProfile');
    $loggedUserProfile = $userProfileClass::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
    $tags = Tag::find()->select([Tag::tableName() . '.id'])
                    ->innerJoin('cwh_tag_owner_interest_mm', 'cwh_tag_owner_interest_mm.tag_id = tag.id and cwh_tag_owner_interest_mm.deleted_at is NULL')
                    ->andWhere(['record_id' => $loggedUserProfile->id])
                    ->andWhere(['classname' => 'open20\amos\admin\models\UserProfile'])
                    ->andWhere(['interest_classname' => 'simple-choice'])->column();

    return $tags;
}

$idsInterestTags = getTagOfInterest();
?>
<div class="m-t-30">
    <label class="text-uppercase"><?= BaseAmosModule::t('amoscore', 'Tag di interesse'); ?></label>
</div>
<?php if ($filesQuantity == 0) { ?>
    <div class="no-items text-muted"><?= BaseAmosModule::t('amoscore', 'Non sono presenti aree di interesse associate a questo contenuto'); ?></div>
    <div class="tags-list-all">
    </div>
<?php } else { ?>

    <div class="tags-list-all m-t-10">
    <?php
    foreach ($dataProvider->models as $tag) {
        if ($onlyText) {
            $tagString .= $tag->nome . ", ";
        } else {
            if (!\Yii::$app->user->isGuest) {
                $is_present = in_array($tag->id, $idsInterestTags);
                $onclick = ($is_present ? 'onclick="removeTagMyProfile(' . $tag->id . ',' . $userid . ')"' : 'onclick="addTagMyProfile(' . $tag->id . ',' . $userid . ')"');
                ?>
                    <a href="" <?= $onclick ?>class="tags-bi" data-tag="<?= $tag->id ?>" data-toggle="tooltip" title="<?= $is_present ? 'Togli il tag ' . $tag->nome . ' dal tuo profilo' : 'Aggiungi il tag ' . $tag->nome . ' al tuo profilo' ?>">
                        <div class="d-flex align-items-center">
                            <div>
                                <span class="mdi <?= $is_present ? "mdi-minus-circle-outline" : "mdi-plus-circle-outline" ?>"></span>
                            </div>
                            <div>
                                <span class="tag-label">
                <?= $tag->nome ?>
                                </span>
                            </div>

                        </div>

                    </a>
            <?php } else { ?>
                    <div href="" class="tags-bi" >
                        <div class="d-flex align-items-center">
                            <div>
                                <span class="mdi mdi-tag-multiple"></span>
                            </div>
                            <span class="tag-label">
                <?= $tag->nome ?>
                            </span>
                        </div>

                    </div>
                            <?php
                            }
                        }
                    }
                    echo rtrim($tagString, ', ');
                    if ($filesQuantity > $pageSize) {
                        echo Html::tag(
                                'small',
                                Html::a(BaseAmosModule::t('amoscore', '#view_all'), 'javascript:void(0);', [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#view-all-tags',
                                ]),
                                ['class' => 'm-l-20']
                        );
                    }
                    ?>
    </div>


        <?php
        // -------------- MODAL VIEW ALL TAGS --------------------
        Modal::begin([
            'id' => 'view-all-tags',
            'header' => "<h3>" . BaseAmosModule::t('amoscore', 'Tag') . "</h3>",
        ]);
        Pjax::begin([
            'id' => 'pjax-container-view-all',
            'timeout' => 2000,
            'enablePushState' => false,
            'enableReplaceState' => false,
            'clientOptions' => ['data-pjax-container' => 'grid-view-all-tags', 'method' => 'POST']
        ]);
        ?>
    <div class="tags-list-all m-t-20 m-b-20">
    <?php foreach ($dataProvider->models as $tag) { ?>
            <div class="tags-bi" data-tag="<?= $tag->id ?>" data-toggle="tooltip" title="<?= $tag->tagRoot->nome . ($tag->path ? " / " . $tag->path : "") ?>">
                <div class="d-flex align-items-center">
                    <div>
                        <span class="mdi mdi-tag-multiple"></span>
                    </div>
                    <div>
                        <span class="tag-label">
        <?= $tag->nome ?>
                        </span>
                    </div>

                </div>

            </div>
    <?php } ?>
    </div>
        <?php
        Pjax::end();

        Modal::end();
    }
    ?>