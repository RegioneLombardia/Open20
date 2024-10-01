<?php 
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
?>
<?php if ($canSeeBlock): ?>
    <div>
        <nav class="navbar navbar-expand-lg has-megamenu">
            <button class="custom-navbar-toggler" type="button" aria-controls="nav1" aria-expanded="false" aria-label="Mostra/Nascondi la navigazione" data-target="#nav1">
                <svg class="icon">
                <use href="/bootstrap-italia/1.x/dist/svg/sprite.svg#it-burger"></use>
                </svg>
            </button>
            <div class="navbar-collapsable" id="nav1">
                <div class="overlay"></div>
                <div class="close-div">
                    <button class="btn close-menu" type="button">
                        <span class="sr-only">Nascondi la navigazione</span>
                        <svg class="icon">
                        <use href="/bootstrap-italia/1.x/dist/svg/sprite.svg#it-close-big"></use>
                        </svg>
                    </button>
                </div>
                <div class="menu-wrapper">
                    <ul class="navbar-nav">
                        <?= $html ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
<?php endif; ?>
