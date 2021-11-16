<?php

use yii\web\View;
use app\modules\uikit\Uikit;

/**
 * @var $this
 * @var $data
 */
$id = $data['id'];
//$class = $data['class'];
$class = (isset($data['class'][0]) ? $data['class'][0] : '');
$tabID = 'tab-' . substr(uniqid(), -3);

// Tabs
//$attrs['uk-subnav uk-subnav-pill'] = Uikit::json(Uikit::pick($data, []));
//console.log($(this).data('tabcustom') +' this');

if ($data['tab_all_close']) {
    $class = $class . ' tab-all-close';

    $js = <<< JS
        var current_tab = null;
        var previous_tab = null;

        $('.tab-all-close [data-toggle=tab]').click(function(event){
          if ($(this).data('tabcustom') == 'active'){
                $($(this).attr("href")).removeClass('active');
                    $($(this).parent()).removeClass('active');
                    $(this).data('tabcustom', '');
                    if(current_tab == this){
                        event.preventDefault();
                        return false;
                    }

          } else {
             $(this).data('tabcustom', 'active');
         }
        });

        $('.nav-tabs a').on('shown.bs.tab', function (e) {
             current_tab = e.target;
             previous_tab = e.relatedTarget;
         });
JS;

    $this->registerJs($js, View::POS_READY);
}
?>

<div class="<?= $class ?>">
    <ul class="nav nav-tabs">
        <?php foreach ($data['items'] as $key => $item) : ?>
            <li>
                <?php
                if ($item['tab_image']) {

                    $attrs_image['class'][] = 'el-image';

                    if (Uikit::isImage($item['tab_image']) == 'gif') {
                        $attrs_image['uk-gif'] = true;
                    }
                    $item['tab_image'] = Uikit::image($item['tab_image'], $attrs_image);
                }
                ?>
                <a class="<?= $item['tab_image_class'] ?>" href="#tab<?= $key ?>" data-toggle="tab">
                    <?= $item['tab_image'] ?>
                    <p><?= $item['title'] ?></p>
                </a>
            </li>
        <?php endforeach ?>
    </ul>

    <div class="tab-content">
        <?php foreach ($data['items'] as $key => $item) : ?>
            <div class="tab-pane" id="tab<?= $key ?>">
                <p><?= $item['content'] ?></p>
            </div>
        <?php endforeach ?>
    </div>
</div>

