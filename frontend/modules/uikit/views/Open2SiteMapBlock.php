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
        <ul>
        <?php
            
        foreach (Yii::$app->menu->find()->container($data['container'])->root()->all() as $item) 
        {
            echo $this->render('open2sitemap/item', ['item' => $item, 'data' => $data]);
        }
        ?>
        </ul>
    </div>
<?php endif; ?>