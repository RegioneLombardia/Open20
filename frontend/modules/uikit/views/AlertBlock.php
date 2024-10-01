<?php

use trk\uikit\Uikit;

/**
 * @var $this object
 * @var $data array
 */

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
$class = $data['class'][0];

?>
<?php if ($canSeeBlock): ?>
    <?php if((!empty($data['content']) || !empty($data['additional_content']))): ?>
        <div 
            class="alert 
            <?=$data['alert_style'] ? 'alert-'.$data['alert_style']  : 'alert-info';?>
            <?=$data['can_close'] ? 'alert-dismissible fade show'  : '';?>
            <?= $class ?>"
            role="alert"    
        >

            <?php if(!empty($data['content'])): ?>
                <p class="h4"><?= $data['content'] ?></p>
            <?php endif; ?>
            <?php if(!empty($data['content']) && !empty($data['additional_content'])): ?>
                <hr>
            <?php endif; ?>
            <?php if(!empty($data['additional_content'])): ?>
                <?= $data['additional_content'] ?>
            <?php endif; ?>

            <?php if(!empty($data['can_close'] )): ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi avviso">
                    <span aria-hidden="true">&times;</span>
                </button>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info" role="alert">
            Inserisci un testo o un titolo per l'alert
        </div>
    <?php endif; ?>

<?php endif; ?>