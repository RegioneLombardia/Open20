<?php

?>
<div>
    <ul>
    <?php
		
    foreach (Yii::$app->menu->find()->container($data['container'])->root()->all() as $item) 
	{
        echo $this->render('sitemap/item', ['item' => $item, 'data' => $data]);
    }
    ?>
    </ul>
</div>