<?php

use yii\bootstrap\Modal;
use open20\amos\core\helpers\Html;
use open20\amos\admin\AmosAdmin;
use open20\amos\tag\models\Tag;
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Proscriptions/license-default.txt to change this proscription
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

$tag_utente = \open20\amos\cwh\models\CwhTagOwnerInterestMm::find()
                ->innerJoin('tag', 'tag.id = tag_id')
                ->andWhere([
                    'record_id' => \Yii::$app->user->id,
                ])
                ->orderBy([
                    'tag.nome' => SORT_DESC
                ])->all();

$array_tag_utente = [];

foreach ($tag_utente as $tu) {
    $array_tag_utente[] = $tu->tag_id;
}
$tag_news = \open20\amos\tag\models\EntitysTagsMm::find()
                ->joinWith('tag')
                ->andWhere([
                    'classname' => $model->className(),
                    'record_id' => $model->id,
                ])
                ->orderBy([
                    'tag.nome' => SORT_DESC
                ])->all();

$array_tag_news = [];

foreach ($tag_news as $tn) {
    $array_tag_news[] = $tn->tag_id;
}
$array_diff = array_diff($array_tag_news, $array_tag_utente);
?>

<a href='#mymodal' data-toggle='modal' data-target='#mymodal'>Tag ancora non nei tuoi interessi</a>

<?php
Modal::begin([
    'header' => 'Aggiungi tag',
    'id' => 'mymodal',
        //'options' => ['class' => 'modal'] //in case if you dont want animation, by default class is 'modal fade'
]);

if ($array_diff) {
    $tagsMissing = Tag::Find()->andWhere(['id' => $array_diff])->all();
    echo '<div class="tags-list-all m-t-30">';
    foreach ($tagsMissing as $tag) {
        ?>
        <div class="tags-bi">

            <div class="d-flex align-items-center">
                <div>
                    <span class="mdi mdi-tag-multiple"></span>
                </div>
                <div>
                    <span class="tag-label"><?= $tag->nome ?></span>
                </div>

            </div>

        </div>
        <?php
    }
    echo "</div>";
    echo Html::a(AmosAdmin::t('amosadmin', 'Annulla'), null, ['data-dismiss' => 'modal', 'class' => 'btn btn-secondary']) .
    Html::a(AmosAdmin::t('amosadmin', 'Ok'), ['/news/news/save-user-tags', 'id' => $model->id], ['class' => 'btn btn-navigation-primary']);
} else {
    echo "<p>La notizia è perfettamente in linea con i tuoi interessi</p>";
}





Modal::end();
