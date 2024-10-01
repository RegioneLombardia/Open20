<?php
   
    if($item->hasChildren)
    {
        echo "<li><a href=\"$item->link\">$item->title</a></li>";
        echo "<ul>";
        foreach (Yii::$app->menu->getLevelContainer($item->depth
                                + 1, $item) as $child) {
            echo $this->render('item', ['item' => $child, 'data' => $data]); 
        }
        echo "</ul>";
    }else
    {
        echo "<li><a href=\"$item->link\">$item->title</a></li>";
    }
?>


