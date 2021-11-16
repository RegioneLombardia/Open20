<?php

//use yii\bootstrap4\Breadcrumbs;
use yii\widgets\Breadcrumbs;

$isVisible = isset(Yii::$app->params['layoutConfigurations']['hideBreadCrumb']) ? !Yii::$app->params['layoutConfigurations']['hideBreadCrumb']
        : true;
?>

<?php if (!empty($this->params['breadcrumbs']) && $isVisible) : ?>

    <?php
    $homeLink           = '<span class="am am-home"></span>';
    $containerClass     = 'breadcrumb mb-1';
    $itemTemplate       = '<li class="breadcrumb-item">{link}<span class="separator">/</span></li>'; //BS3 inserisce before in automatico
    // $itemTemplate = '<li class="breadcrumb-item">{link}</li>';
    $activeItemTemplate = '<li class="breadcrumb-item active">{link}</li>';
    $tag                = 'ol';
    ?>

    <nav class="breadcrumb-container" aria-label="breadcrumb">

        <?=
        Breadcrumbs::widget([
            'encodeLabels' => false,
            'tag' => $tag,
            'itemTemplate' => $itemTemplate,
            'activeItemTemplate' => $activeItemTemplate,
            'homeLink' => [
                'label' => (!empty(\Yii::$app->params['homeName']) ? \Yii::$app->params['homeName'] : $homeLink),
                'url' => ((!empty(\Yii::$app->params['befe']) && \Yii::$app->params['befe'] == true) ? '/site/to-menu-url?url='.Yii::$app->homeUrl
                        : Yii::$app->homeUrl),
                'encode' => false,
                'title' => 'home'
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'options' => [
                'class' => $containerClass
            ]
        ])
        ?>

    </nav>


<?php endif; ?>