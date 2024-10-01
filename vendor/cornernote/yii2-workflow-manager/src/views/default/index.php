<?php
/*
 * @var yii\web\View $this
 */

use cornernote\workflow\manager\models\Workflow;
use yii\bootstrap\Nav;
use yii\helpers\Html;
//use cornernote\workflow\manager\assets\WorkflowManagerAsset;

$this->title = Yii::t('workflow', 'Workflow Manager');
$this->params['breadcrumbs'][] = $this->title;

//WorkflowManagerAsset::register($this);
?>

<style>
    .fullsizeMainLayout {
        height: auto;

    }

    .workflow-manager-list {
        display: flex;
        flex-direction: column;
    }

    .workflow-manager-list .workflow-manager-item {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 15px 0;
        border-bottom: 1px solid #000;
    }

    .workflow-manager-list .workflow-manager-item:hover {
        background-color: #ededed;
    }
</style>

<div class="page-header fake-vendor-wm-page-header">

    <div class="bi-plugin-header">

        <style>
            .page-header {
                display: none;
            }

            .page-header.fake-vendor-wm-page-header {
                display: flex;
            }
        </style>

        <div class="flexbox title-heading-plugin">
            <div class="m-r-10">
                <div class="h2 text-uppercase ">Workflow Manager</div>
            </div>
        </div>

        <div class="cta-wrapper">
            <div class="flexbox manage-cta-container">
                <a href="/workflow-manager/default/create" class="cta link-create-news flexbox align-items-center btn btn-xs btn-primary" title="Nuovo workflow manager">
                    <span class="am am-plus-circle-o"></span>
                    <span>Nuovo</span>
                </a>
            </div>
        </div>
    </div>
    <div class="subtitle-news ">
        <p>Gestione workflow della piattaforma Open2.0</p>
    </div>

</div>

<div class="workflow-default-index">

    <?php
    // $items = [
    //     [
    //         'label' => '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('workflow', 'Create'),
    //         'url' => ['create'],
    //         'encode' => false,
    //     ],
    // ];
    foreach (Workflow::find()->orderBy(['id' => SORT_ASC])->all() as $workflow) {
        /** @var Workflow $workflow */
        $items[] = [
            'label' => $workflow->id,
            'url' => ['view', 'id' => $workflow->id],
            'linkOptions' => ['style' => 'color:#fff;background:' . $workflow->getColor()],
        ];
    }
    // echo Nav::widget([
    //     'items' => $items,
    //     'options' => ['class' => 'nav-pills'],
    // ]);
    ?>


    <div class="workflow-manager-list">

        <?php
        $numItems = count($items);
        $removeitem = array_shift($items);
        foreach ($items as $singleItem) {
        ?>
            <div class="workflow-manager-item">
                <p><?php echo $singleItem['label'] ?></p>
                <?php echo Html::a('<span class="am am-edit"><span class="sr-only">Modifica</span></span>', ['default/view', 'id' => $singleItem['label']], ['class' => 'btn btn-secondary']); ?>
            </div>
        <?php
        }
        ?>

    </div>


</div>