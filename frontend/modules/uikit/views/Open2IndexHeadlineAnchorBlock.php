<?php

use app\modules\cms\utility\StorageUtility;
use app\modules\uikit\Uikit;
use open20\amos\documenti\models\Documenti;
use open20\design\assets\BootstrapItaliaDesignAsset;
use app\assets\IndexHeadlineAnchorAsset;
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





IndexHeadlineAnchorAsset::register($this);

/**
 * @var $this
 * @var $data
 */
$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);


$id = $data['id'];
$class = $data['class'];
$attrs = $data['attrs'];

// Accordion
//$attrs['uk-accordion'] = Uikit::json(Uikit::pickBool($data, ['multiple', 'collapsible']));
?>

<?php if ($canSeeBlock): ?>
        <aside class="<?= ($data['affix'])? 'position-sticky z-index-1' : '';?>" id="menu-sinistro">
            <nav class="navbar it-navscroll-wrapper navbar-expand-lg w-100">
                <div id="navbarNav" class="w-100">
                    <div class="menu-wrapper mr-md-5">
                        <div class="link-list-wrapper menu-link-list">
                            <a data-toggle="collapse" href="#lista-paragrafi" role="button" aria-expanded="true" aria-controls="lista-paragrafi" class="no_toc text-decoration-none">
                            <h3 id="titolo-indice" class="primary-color position-relative d-flex align-items-center justify-content-between"><?= (!empty($data['section_title']))? $data['section_title'] : 'Indice della pagina ' ?>
                                    <svg class="icon icon-primary">
                                        <use xlink:href=" <?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#chevron-down"></use>
                                    </svg>
                                </h3> 
                            </a>
                            <ul id="lista-paragrafi" class="link-list collapse show">                                
                            <?php foreach ($items as $item) : ?>
                                <li class="nav-item">
                                    <?= $item ?>
                                </li>
                            <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </aside>
    
<?php endif; ?>
                         
