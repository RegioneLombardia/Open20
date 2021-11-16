<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

/**
 * @var View $this
 * @var ActiveDataProvider $listaTopic
 * @var WidgetGraphicsUltimeDiscussioni $widget
 * @var string $toRefreshSectionId
 */
use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\icons\AmosIcons;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni;
use open20\amos\discussioni\assets\ModuleDiscussioniInterfacciaAsset;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

$moduleDiscussioni = \Yii::$app->getModule(AmosDiscussioni::getModuleName());
ModuleDiscussioniInterfacciaAsset::register($this);
?>

<div class="box-widget">
    <div class="box-widget-toolbar row nom">
        <h2 class="box-widget-title col-xs-10 nop"><?= AmosDiscussioni::tHtml('amosdiscussioni', 'Discussioni in evidenza') ?></h2>
<?php if (isset($moduleDiscussioni) && !$moduleDiscussioni->hideWidgetGraphicsActions) { ?>
  <?=
  WidgetGraphicsActions::widget([
    'widget' => $widget,
    'tClassName' => AmosDiscussioni::className(),
    'actionRoute' => '/discussioni/discussioni-topic/create',
    'toRefreshSectionId' => $toRefreshSectionId
  ]);
  ?>
        <?php } ?>
    </div>
    <section>
<?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
        <div role="listbox">
        <?php if (count($listaTopic->getModels()) == 0): ?>
              <?= '<div class="list-items"><h2 class="box-widget-subtitle">' . AmosDiscussioni::tHtml('amosdiscussioni', 'Nessuna Discussione') . '</h2></div>'; ?>
            <?php else: ?>
              <div class="list-items">
              <?php foreach ($listaTopic->getModels() as $topic): ?>
                    <?php
                    /** @var DiscussioniTopic $topic */
                    ?>
                    <div class="widget-listbox-option row" role="option">
                        <article class="col-xs-12 nop">

                            <div class="container-text row">
                                <div class="col-xs-12">
                                    <p><?= Yii::$app->getFormatter()->asDatetime($topic->created_at); ?></p>
                                    <h2 class="box-widget-subtitle">
    <?php
    if (strlen($topic->titolo) > 100) {
      $stringCut = substr($topic->titolo, 0, 100);
      echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
    } else {
      echo $topic->titolo;
    }
    ?>
                                    </h2>
                                </div>
                                <!--        <div class="col-xs-12"> -->
                                <!--        <p class="box-widget-text"> -->
    <?php
    /* if (strlen($topic->testo) > 120) {
      $stringCut = substr($topic->testo, 0, 120);
      echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
      } else {
      echo $topic->testo;
      } */
    ?>
                                <!--        </p> -->
                                <!--        </div> -->
                            </div>

                            <div class="col-xs-12 nop">
                                <p class="pull-left"><?=
                            AmosIcons::show("comment", [
                              'class' => 'am-1'
                            ])
    ?>
                                    <?= ($numeroContributi = $topic->getDiscussionComments()->count()) ? $numeroContributi : AmosDiscussioni::tHtml('amosdiscussioni', 'Nessun contributo'); ?></p>

                                        <!--                                    <span class="">-->
                                <!--                                        < ?= Html::a(AmosDiscussioni::t('amosdiscussioni', 'LEGGI TUTTO'), ['../discussioni/discussioni-topic/index']); ?>-->
                                <!--                                    </span>-->
                                <span class="pull-right">
                                    <?= Html::a(AmosDiscussioni::t('amosdiscussioni', 'CONTRIBUISCI'), ['../discussioni/discussioni-topic/partecipa', 'id' => $topic->id], ['class' => 'btn btn-navigation-primary']); ?>
                                </span>
                            </div>

                            <div class="clearfix"></div>
                        </article>
                    </div>
                  <?php endforeach; ?>
              </div>
              <?= Html::a(AmosDiscussioni::t('amosdiscussioni', 'Visualizza Elenco Discussioni'), ['/discussioni'], ['class' => 'read-all']); ?>
            <?php endif; ?>
        </div>
        <?php Pjax::end(); ?>
    </section>
</div>
