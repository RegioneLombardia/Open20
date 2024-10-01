<?php
use yii\grid\GridView;
use yii\helpers\Url;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\onlyoffice\models\OnlyofficeFiles;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Elenco file';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-xs-12">
    <div class="pull-rigth">
        <a href="<?= Url::to(['create']) ?>" class="btn btn-primary">Crea nuovo</a>
        <a href="<?= Url::to(['upload']) ?>" class="btn btn-primary">Carica</a>
    </div>
</div>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'name',
        'type',
        'size' => [
            'attribute' => 'size',
            'value' => function ($model, $key, $index, $column) {
                /** @var open20\amos\attachments\models\File $model */
                return $model->getFormattedSize();
            }
        ],
        [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'template' => '{view}{update}{delete}{associa}{download}',
                'visibleButtons' => [
                    'view' => function ($model, $key, $index) {
                        return true;
                    },
                    'update' => function ($model, $key, $index) {
                        return true;
                    },
                    'delete' => function ($model, $key, $index) {
//                        return true;
                        return false;
                    },
                    'associa' => function ($model, $key, $index) {
                        return true;
                    },
                    'download' => function ($model, $key, $index) {
                        return true;
                    },
                ],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        /** @var open20\amos\attachments\models\File $model */
                        if (strpos($model->model, OnlyofficeFiles::class) !== false)
                        {
                            return Html::a(AmosIcons::show('file', null,
                                'am'), ['view', 'id' => $model->item_id], [
                                    'class' => 'btn btn-tools-secondary',
                                    'title' => 'Visualizza in OnlyOffice',
                                ]);
                        }
                        else
                        {
                            $modelO = OnlyofficeFiles::find()
                                ->andWhere(['attach_file_id' => $model->id])
                                ->one();
                            if (!empty($modelO))
                            {
                                return Html::a(AmosIcons::show('file', null,
                                'am'), ['view', 'id' => $modelO->id], [
                                    'class' => 'btn btn-tools-secondary',
                                    'title' => 'Visualizza in OnlyOffice',
                                ]);
                            }
                        }
                        return '';
                    },
                    'update' => function ($url, $model, $key) {
                        /** @var open20\amos\attachments\models\File $model */
                        if (strpos($model->model, OnlyofficeFiles::class) !== false)
                        {
                            return Html::a(AmosIcons::show('edit', null,
                                'am'), ['edit', 'id' => $model->item_id], [
                                    'class' => 'btn btn-tools-secondary',
                                    'title' => 'Modifica con OnlyOffice',
                                ]);
                        }
                        else
                        {
                            $modelO = OnlyofficeFiles::find()
                                ->andWhere(['attach_file_id' => $model->id])
                                ->one();
                            if (!empty($modelO))
                            {
                                return Html::a(AmosIcons::show('edit', null,
                                'am'), ['edit', 'id' => $modelO->id], [
                                    'class' => 'btn btn-tools-secondary',
                                    'title' => 'Modifica con OnlyOffice',
                                ]);
                            }
                        }
                        return '';
                    },
                    'delete' => function ($url, $model, $key) {
                        /** @var open20\amos\attachments\models\File $model */
                        return '';
                    },
                    'associa' => function ($url, $model, $key) {
                        /** @var open20\amos\attachments\models\File $model */
                        if (strpos($model->model, OnlyofficeFiles::class) === false)
                        {
                            $modelO = OnlyofficeFiles::find()
                                ->andWhere(['attach_file_id' => $model->id])
                                ->one();
                            if (empty($modelO))
                            {
                                return Html::a(AmosIcons::show('link', null,
                                    'am'), ['associa', 'id' => $model->id], [
                                        'class' => 'btn btn-tools-secondary',
                                        'title' => 'Associa ad OnlyOffice',
                                    ]);
                            }
                        }
                        return '';
                    },
                    'download' => function ($url, $model, $key) {
                        /** @var open20\amos\attachments\models\File $model */
                        return Html::a(AmosIcons::show('download', null,
                            'am'), $model->getWebUrl('original', true), [
                                'class' => 'btn btn-tools-secondary',
                                'title' => 'Associa ad OnlyOffice',
                            ]);
                    }
                ],
            ],
    ]
]) ?>