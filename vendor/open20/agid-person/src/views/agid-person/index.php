<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modues/operators/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use kartik\grid\GridView;
use open20\agid\person\Module;
use yii\widgets\Pjax;
use yii\data\ActiveDataProvider;
use open20\amos\admin\models\base\UserProfile;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\modules\operators\models\AgidPersonSearch $model
 */

$this->title = Yii::t('amoscore', 'Persone');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/app']];
$this->params['breadcrumbs'][] = $this->title;
$exportColumns=[
    'id' => [
        'attribute' => 'id',
        'value' => 'id',
        'label' => '#ID'
    ],
    'name' => [
        'attribute' => 'name',
        'format' => 'html',
        'value' => function ($model) {
            return $model->name;
        },
        'label' => Module::t('amosperson', 'Nome')
    ],
    'surname' => [
        'attribute' => 'surname',
        'format' => 'html',
        'value' => function ($model) {
            return $model->surname;
        },
        'label' => Module::t('amosperson', 'Cognome')
    ],
    'role' => [
        'attribute' => 'role',
        'format' => 'html',
        'value' => function ($model) {
            return $model->role;
        },
        'label' => Module::t('amosperson', 'Ruolo')
    ],
    'telephone' => [
        'attribute' => 'telephone',
        'format' => 'html',
        'value' => function ($model) {
            return $model->telephone;
        },
        'label' => Module::t('amosperson', 'Telefono')
    ],
    'email' => [
        'attribute' => 'email',
        'format' => 'html',
        'value' => function ($model) {
            return $model->email;
        },
        'label' => Module::t('amosperson', 'Email')
    ],
    'agidPersonType' => [
        'attribute' => 'agidPersonType.name',
        'format' => 'html',
        'value' => function ($model) {
            return $model->agidPersonType->name;
        },
        'label' => Module::t('amosperson', 'Tipologia persona')
    ],
	'organizationalunit' => [
        'attribute' => 'agidPersonType.name',
        'format' => 'html',
        'value' => function ($model) {
            $ous=$model->getAgidOrganizationalUnit();
            $orgunit='';
            foreach ($ous as $ou){
            	$orgunit.=$ou->name.',';
            }
            return rtrim($orgunit, ",");
        },
        'label' => Module::t('amosperson', 'Organizzazione di riferimento')
    ],
//  Organizzazione di riferimento (concatenati con virgola)

    'created_by' => [
        'attribute' => 'created_by',
        'value' => function ($model) {
            if( $user_profile = UserProfile::find()->andWhere(['user_id' => $model->created_by])->one() ){
                return $user_profile->nome . " " . $user_profile->cognome;
            }
            return;
        },
        'label' => 'Creato da'
    ],
    'created_at:datetime' => [
        'attribute' => 'created_at',
        'value' => 'created_at',
        'format' => ['date', 'php:d/m/Y H:i:s'],
        'label' => Module::t('amosperson', 'Creato il')
    ],
    'updated_by' => [
        'attribute' => 'updated_by',
        'value' => function ($model) {
            if( $user_profile = UserProfile::find()->andWhere(['user_id' => $model->updated_by])->one() ){
                return $user_profile->nome . " " . $user_profile->cognome;
            }
            return;
        },
        'label' => 'Aggiornato da'
    ],

    'updated_at:datetime' => [
        'attribute' => 'updated_at',
        'value' => 'updated_at',
        'format' => ['date', 'php:d/m/Y H:i:s'],
        'label' => Module::t('amosperson', 'Aggiornato il')
    ],

    'status' => [
        'attribute' => 'status',
        'value' => function ($model) {
            // return $model->hasWorkflowStatus() ? $model->getWorkflowStatus()->getLabel() : '--';
            return Module::t('amosperson', $model->status);
        },
        'label' => Module::t('amosperson', 'Stato')
    ],
	'tag'=> [
        'label' => Module::t('amosorganizationalunit', 'Tags'),
        'value' => function ($model) {
            $goals='';
            $entityTags = open20\amos\tag\models\EntitysTagsMm::find()
                ->andWhere(['record_id' => $model->id])
                ->andWhere(['classname' => $model->className()])->all();
            foreach ($entityTags as $tag) {
                $tagn=open20\amos\tag\models\Tag::find()
                    ->andWhere(['id' => $tag->tag_id,'root' => $tag->root_id
                    ])->one();
                $goals .= $tagn->nome .',';
            }
            return rtrim($goals, ", ");
        },
    ]
];
?>
<div class="agid-person-index">

    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

    <?= 
		DataProviderView::widget([
			'dataProvider' => $dataProvider,
			'currentView' => $currentView,
			'gridView' => [
				'columns' => [

					'id' => [
						'attribute' => 'id',
						'value' => 'id',
						'label' => '#ID'
					],

					'name',
					'surname',
					'role',
					'telephone',
					'email',

					'agidPersonType' => [
						'attribute' => 'agidPersonType.name',
						'format' => 'html',
						'value' => function ($model) {
							return $model->agidPersonType->name;
						},
						'label' => Module::t('amosperson', 'Tipologia persona') 
					],
					
					'updated_by' => [
						'attribute' => 'updated_by',
						'value' => function ($model) {
							if( $user_profile = UserProfile::find()->andWhere(['user_id' => $model->updated_by])->one() ){
								return $user_profile->nome . " " . $user_profile->cognome;
							}
							return;
						},
						'label' => 'Aggiornato da'
					],

					'updated_at:datetime' => [
						'attribute' => 'updated_at',
						'value' => 'updated_at',
						'format' => ['date', 'php:d/m/Y H:i:s'],
						'label' => Module::t('amosperson', 'Aggiornato il') 
					],

					'status' => [
						'attribute' => 'status',
						'value' => function ($model) {
							// return $model->hasWorkflowStatus() ? $model->getWorkflowStatus()->getLabel() : '--';
							return Module::t('amosperson', $model->status);
						},
						'label' => Module::t('amosperson', 'Stato') 
					],

					[
						'class' => 'open20\amos\core\views\grid\ActionColumn',
					],
				],
                'enableExport' => true,
				'responsive' => true,
				'hover' => true,
				'showPageSummary' => true,
				'panel' => [
					'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Persone</h3>',
					'type' => 'default',
					'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> Crea Nuovo', ['create'], ['class' => 'btn btn-success']),
				],
			],
            'exportConfig' => [
                'exportEnabled' => true,
                'exportColumns' => $exportColumns
            ],
		]); 
	?>

</div>
