<?php

use yii\web\View;
use app\modules\uikit\Uikit;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignIcon;

$currentAsset = BootstrapItaliaDesignAsset::register($this);
use app\modules\uikit\BaseUikitBlock;

$canSeeBlock = true;
$visibility = $this->varValue('visibility');

switch($visibility){
    case 'guest':
        $canSeeBlock = Yii::$app->user->isGuest;          
    break;
    case 'logged':
        $canSeeBlock = !Yii::$app->user->isGuest; 
		$n_class = $this->varValue('addclass');
		if($canSeeBlock && !empty($n_class)){
			$canSeeBlock = BaseUikitBlock::visivility($n_class);
		}
    break;
}





/**
 * @var $this
 * @var $data
 */
$id = $data['id'];
$class = $data['class'][0];

$attrs_image = [];
$attrs_link = [];
$attrs_link_modal = [];

$allegati = $this->extraValue('allegati');

$js = <<<JS
    $('#{$id}').modal('show');
        

JS;
            
// Link
if ($data['linkModal']) {
    $attrs_link_modal['href'] = $data['linkModal'];
    $attrs_link_modal['target'] = $data['link_target'] ? '_blank' : '';
    $attrs_link_modal['uk-scroll'] = strpos($data['linkModal'], '#') === 0;
    $attrs_link_modal['class'][] = 'el-link';
    switch ($data['link_style']) {
        case '':
            break;
        case 'link-muted':
        case 'link-text':
            $attrs_link_modal['class'][] = "uk-{$data['link_style']}";
            break;
        default:
            $attrs_link_modal['class'][] = "uk-button uk-button-{$data['link_style']}";
            $attrs_link_modal['class'][] = $data['link_size'] ? "uk-button-{$data['link_size']}" : '';
    }
}





?>
<?php if ($canSeeBlock): ?>
    <!--MODALE-->
    <div id="<?=$id?>" class="<?= $class ?> modal fade" tabindex="-1" role="dialog" id="<?= $modalID ?>">
        <div class="modal-dialog modal-<?= $data['modal_size'] ?>" role="document">
            <div class="modal-content modal-<?= $data['modal_size'] ?>" style="background-size: cover;background-image: url(<?= $data['backgroundImg'] ?>)">
                <div class="modal-header">
                    <p class="modal-title font-weight-bold h5"><?= $data['modal_title'] ?></p>
                
                    <?php if($data['can_close']): ?>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <?= DesignIcon::show('it-close', DesignIcon::ICON_BI, 'icon ', $currentAsset)?>
                        </button>
                    <?php endif; ?>
                </div>
                <div class="modal-body pb-4">
            
                    <p><?= $data['modal_text'] ?></p>
                    <?php if($allegati): ?>
                        <div class="it-list-wrapper mt-3">
                            <p class="font-weight-bold"><?=Yii::t('backendobjects', 'Allegati scaricabili:')?></p>
                            <ul class="it-list">
                            <?php foreach($allegati as $allegato): ?>
                                <li class="attachments-detail__item">
                                    <a class="d-flex small py-2" style="margin-left:-10px;" href="<?= $allegato['url'] ?>" target="<?= $allegato['target'] ?>" title="">
                                        <svg class="icon icon-primary">
                                            <use xlink:href=" <?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-clip"></use>
                                        </svg>
                                        <div class="news-allegato-name"><?= $allegato['name'] ?></div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                </div>
                <?php if(
                    (!empty($data['can_close_button'])) &&
                    (!empty($data['second_button']))
                ): ?>

                    <div class="modal-footer">
                        <?php if(!empty($data['can_close_button'])): ?>
                            <button class="btn btn-outline-primary btn-sm" type="button" data-dismiss="modal">Annulla</button>
                        <?php endif; ?>
                        <?php if(!empty($data['second_button'])): ?>
                            <a href="<?= $data['second_button']?>" target="<?= $data['link_target'] ?>" class="btn btn-primary btn-sm" title="<?= ($data['second_button_text'])? $data['second_button_text'] : 'Accetta'; ?>"><?= ($data['second_button_text'])? $data['second_button_text'] : 'Accetta'; ?></a>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <?php 

    switch($data['open_modal']):

        case 'load':
            $this->registerJs($js, View::POS_READY);
        break;

        case '':
        case 'default': ?>
            <button 
                class="btn btn-<?= $data['modal_button_style'] ?> <?= $data['button_class']?> open20-cms-button" 
                data-toggle="modal" 
                data-target="#<?=$id?>"
            >
                <?php switch($data['icon_type'])
                        {
                            case 1:
                                echo DesignIcon::show($data['icon_name'], DesignIcon::ICON_BI, 'icon', $currentAsset);
                            break;
                            case 2:
                                echo DesignIcon::show($data['icon_name'], DesignIcon::ICON_MD, 'icon', $currentAsset);
                            break;
                        }
                ?>

                <?= $data['modal_button_text']?>
            </button>
        <?php break; ?>
        <?php case 'text': ?>
            <?= \yii\helpers\Html::a($data['modal_button_text'],'#',['data-toggle'=>'modal','data-target'=>"#$id"]) ?>
        <?php break; ?>
        <?php case 'image': ?>
            <?= \yii\helpers\Html::a(\yii\helpers\Html::img($data['link_image'],['class'=>'modal-image w-100']),'#',['data-toggle'=>'modal','data-target'=>"#$id"]) ?>    
        <?php break; ?>

    <?php endswitch; ?>
<?php endif;
