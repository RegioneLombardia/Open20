<?php


?>
<li class="el-item" id='<?= str_replace(' ', '', $item->title) ?>'><a name='<?= str_replace(' ', '', $item->title) ?>'></a><a class="anchor-offset" href="<?= $item->link ?>"><?= $item->title ?></a></li>
<?php
    if($item->with('hidden')->hasChildren)
    {
        $level++;
        if($level < $maxlevel){
?>
            <ul class='uk-subnav mb-5 lvl-<?= $level?>'>
                <?php
                foreach ($item->with('hidden')->children as $lcitem) 
                {
                    $prm = compact('data', 'level', 'maxlevel');
                    $prm ['item'] = $lcitem;
                    echo $this->render('item', $prm);
                }
                ?>
            </ul>
<?php
        }
    }
?>
