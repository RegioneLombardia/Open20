<?php
use yii\bootstrap4\Breadcrumbs;

?>

<?php if (!empty($this->params['breadcrumbs'])) : ?>

  <?php
  $homeLink = '<svg class="icon icon-sm icon-secondary align-bottom" aria-hidden="true"><use xlink:href="' . $currentAsset->baseUrl . '/sprite/material-sprite.svg#ic_home"></use></svg>';
  $containerClass = 'breadcrumb mb-1';
  $itemTemplate = '<li class="breadcrumb-item">{link}<span class="separator">/</span></li>';
  $activeItemTemplate = '<li class="breadcrumb-item active">{link}</li>';
  $tag = 'ol';
  ?>

<nav class="breadcrumb-container" aria-label="breadcrumb">
  <!-- < ?= TODO CHANGE WITHOUT BS4 WIDGET
    Breadcrumbs::widget([
      'encodeLabels' => false,
      'tag' => $tag,
      'itemTemplate' => $itemTemplate,
      'activeItemTemplate' => $activeItemTemplate,
      'homeLink' => [
        'label' => (!empty(\Yii::$app->params['homeName']) ? \Yii::$app->params['homeName'] : $homeLink),
        'url' => Yii::$app->homeUrl,
        'encode' => false,
        'title' => 'home'
      ],
      'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
      'options' => [
        'class' => $containerClass
      ]
    ])
  ?> -->
  </nav>


<?php endif; ?>