<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\privileges
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\views\AmosGridView;
use open20\amos\privileges\AmosPrivileges;
use yii\bootstrap\Modal;
use yii\web\JsExpression;

/**
 * @var array $array
 * @var array $cwhNodes
 * @var \yii\web\View $this
 * @var integer $userId
 */

$this->title = AmosPrivileges::t('amosprivileges', 'Manage privileges');
$this->params['breadcrumbs'][] = $this->title;
$url = \Yii::$app->urlManager->baseUrl . '/attachments/file/download/';
// AGID FIELDS ENABLE
$enableAgid = AmosPrivileges::instance()->enableAgid;
///attachments/file/download?id=37&hash=b22a4e0ac092f76630a23330598b5ddd&size=original

$format = <<< SCRIPT
function format(state) {
    if (!state.id) return state.text; // optgroup
    src = '$url?id=37&hash=b22a4e0ac092f76630a23330598b5ddd&size=original';
    return '<img height ="30px" width="37px" class="avatar-xs" class="flag" src="' + src + '"/>' + state.text;
}
SCRIPT;
$escape = new JsExpression("function(m) { return m; }");
$this->registerJs($format, $this::POS_HEAD);

$escape = new JsExpression("function(m) { return m; }");

Modal::begin([
    'id' => 'selectDomains',
    'header' => AmosPrivileges::t('amosprivileges', "Select domains")
]);
echo Html::tag('div',
    AmosPrivileges::t('amosprivileges', "To enable this kind of privilege, select domains and click 'Save changes'"));
echo Html::a(AmosPrivileges::t('amosprivileges', 'Ok'),
    null,
    ['data-dismiss' => 'modal', 'class' => 'btn btn-navigation-primary pull-right', 'style' => 'margin: 15px 0']
);
Modal::end();

$js = <<<JS
$('.collapseLink').click(function () {
    var idCollapse = $(this).attr('aria-controls');
    var notCollapse = $('.collapse').not($(idCollapse));
    var notThisLink = $('.collapseLink').not($(this));
    
    if($(this).attr('aria-expanded')=='false'){ /*collapse close*/
        $(this).find('.am-caret-down').addClass('am-caret-up').removeClass('am-caret-down');
        notCollapse.each(function () { /*close others collapse*/
            $(this).removeClass('in');
        });
        notThisLink.each(function() { /*change others collapseLink*/
            $(this).find('.am-caret-up').addClass('am-caret-down').removeClass('am-caret-up');
            $(this).attr('aria-expanded','false');
        });
    }
    else {
        $(this).find('.am-caret-up').addClass('am-caret-down').removeClass('am-caret-up');
    }
    
});
JS;
$this->registerJs($js);

?>
<div id="AmosGridViewAccordion" role="tablist">
    
    <?php
    $numSlide = 1;
    ?>
    
    <?php foreach ($array as $label => $data): ?>
        
        <?php
        $headSlide = "heading" . $numSlide;
        $collSlide = "collapse" . $numSlide;
        $acollSlide = "#" . $collSlide;
        ?>

        <div class="card">
            <div class="card-header" role="tab" id="<?= $headSlide ?>">
                <a class="collapseLink col-xs-12 nop" data-toggle="collapse" aria-expanded="<?= ($numSlide == 1) ? 'true' : 'false' ?>" href="<?= $acollSlide ?>"
                   aria-controls="<?= $collSlide ?>">
                    <h2 class="mb-0 pull-left">
                        
                        
                        <?= $label ?>


                    </h2>
                    <div class="p-t-20"><?= AmosIcons::show('caret-' . (($numSlide != 1) ? 'down' : 'up'), ['class' => 'am-2 m-l-15']) ?></div>
                </a>
            </div>

            <div id="<?= $collSlide ?>" class="collapse <?= ($numSlide == 1) ? 'in' : '' ?>"
                 role="tabpanel" aria-labelledby="<?= $headSlide ?>" data-parent="#accordion">
                <div class="card-body">
                    <?= AmosGridView::widget([
                        'dataProvider' => $data,
                        'id' => $label,
                        'layout' => '{items}',
                        'columns' => [
                            [
                                'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                                'template' => '{enableDisable}',
                                'buttons' => [
                                    'enableDisable' => function ($url, $model) use ($label, $userId) {
                                        $btn = '';
                                        if ($model['active']) {
                                            $btn = Html::a(AmosPrivileges::t('amosprivileges', 'Disable'),
                                                [
                                                    '/privileges/privileges/disable',
                                                    'userId' => $userId,
                                                    'priv' => $model['name'],
                                                    'type' => $model['type'],
                                                    'isCwh' => $model['isCwh'],
                                                    'anchor' => $label
                                                ],
                                                [
                                                    'class' => 'btn btn-navigation-primary',
                                                    'style' => 'font-size:0.8em;'
                                                ]);
                                        } else {
                                            if (!$model['can']) {
                                                if (!$model['isCwh']) {
                                                    $btn = Html::a(AmosPrivileges::t('amosprivileges', 'Enable'),
                                                        [
                                                            '/privileges/privileges/enable',
                                                            'userId' => $userId,
                                                            'priv' => $model['name'],
                                                            'type' => $model['type'],
                                                            'isCwh' => $model['isCwh'],
                                                            'anchor' => $label
                                                        ],
                                                        [
                                                            'class' => 'btn btn-navigation-primary',
                                                            'style' => 'font-size:0.8em;'
                                                        ]);
                                                } else {
                                                    $btn = Html::a(AmosPrivileges::t('amosprivileges', 'Enable'),
                                                        null,
                                                        [
                                                            'class' => 'btn btn-navigation-primary',
                                                            'style' => 'font-size:0.8em;',
                                                            'data-toggle' => 'modal',
                                                            'data-target' => '#selectDomains'
                                                        ]
                                                    );
                                                }
                                            } else {
                                                $parents = implode(', ', $model['parents']);
                                                $btn = Html::tag('div', AmosPrivileges::t('amosprivileges',
                                                        'Enabled because contained in roles:') . '<br/>' . $parents,
                                                    ['style' => 'font-size:0.8em;']);
                                            }
                                        }
                                        return $btn;
                                    },
                                ]
                            ],
                            [
                                'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                                'template' => '{statusIcon}',
                                'buttons' => [
                                    'statusIcon' => function ($url, $model) {
                                        $btn = '';
                                        if ($model['can']) {
                                            $btn = AmosIcons::show('check-circle', ['style' => 'color:green;']);
                                        }
                                        return $btn;
                                    }
                                ]
                            ],
                            'text' => [
                                'format' => 'html',
                                'attribute' => 'text',
                                'label' => AmosPrivileges::t('amosprivileges', 'Privilege')
//                'contentOptions'=>['style'=>'max-width:300px;overflow:hidden; word-break: break-word']
                            ],
                            'tooltip' => [
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a(AmosIcons::show('info',
                                        ['style' => 'color:green; font-size:1.5em;']), null,
                                        ['data-toggle' => 'tooltip', 'title' => $model['tooltip']]);
                                }
                            ],
                            'domains' => [
                                'label' => AmosPrivileges::t('amosprivileges', 'Active for domains'),
                                'format' => 'raw',
                                'value' => function ($model) use ($cwhNodes, $escape, $label, $userId) {
                                    $domains = '';
                                    if ($model['isCwh']) {
                                        $domains = Html::beginForm('/privileges/privileges/save-domains?userId=' . $userId . '&anchor=' . $label,
                                            'post',
                                            [
                                                'id' => 'auth-assign'
                                            ]);
                                        $domains .= \kartik\select2\Select2::widget([
                                            'name' => 'auth-assign[newDomains]',
                                            'data' => $cwhNodes,
                                            'value' => explode(',', $model['domains']),
                                            'options' => [
                                                'multiple' => true,
                                                'placeholder' => AmosPrivileges::t('amosprivileges',
                                                    'Select domains ...'),
                                            ],
                                            'pluginOptions' => [
//                                'templateResult' => new JsExpression('format'),
//                                'templateSelection' => new JsExpression('format'),
                                                'escapeMarkup' => $escape,
                                                'allowClear' => true
                                            ],
                                        ]);
                                        $domains .= Html::hiddenInput('auth-assign[savedDomains]', $model['domains']);
                                        $domains .= Html::hiddenInput('auth-assign[name]', $model['name']);
                                        $domains .= Html::hiddenInput('auth-assign[class_name]', $model['class_name']);
                                        $btnSave = Html::submitButton(AmosPrivileges::t('amosprivileges',
                                            'Save changes'),
                                            [
                                                'class' => 'btn btn-navigation-primary pull-right',
                                                'style' => 'margin-top: 5px;'
                                            ]);
                                        $domains .= $btnSave . Html::endForm();;
                                    }
                                    return $domains;
                                }
                            ]
                        ]
                    ]) ?>
                </div> <!-- card-body -->
            </div> <!-- collapseOne -->
        </div> <!-- card -->
        <?php $numSlide++; ?>
    <?php endforeach; ?>
    
    <?php
    $user = User::findOne($userId);
    $userProfileId = 0;
    if (!is_null($user)) {
        $userProfileId = $user->userProfile->id;
    }
    ?>
    <?php if ($enableAgid) : ?>
        <?php
        $cmd = Yii::$app->db->createCommand("SELECT name FROM auth_item WHERE name LIKE '%REDACTOR_%' and type = 1");
        $query = $cmd->queryAll();
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $query,
            'pagination' => FALSE
        ]);
        $label = 'redaction';
        ?>
        <div class="card">
            <div class="card-header" role="tab" id="redazione">
                <h2 class="mb-0 pull-left text-primary"><?= AmosPrivileges::t('amosprivileges', '#editorial_board'); ?></h2>
            </div>
            <div class="card-body">
                <?= AmosGridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => $label,
                    'layout' => '{items}',
                    'columns' => [
                        [
                            'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                            'template' => '{enableDisable}',
                            'buttons' => [
                                'enableDisable' => function ($url, $model) use ($label) {
                                    $btn = '';
                                    $userId = Yii::$app->request->get('id');
                                    
                                    if (in_array($model['name'], array_keys(\Yii::$app->authManager->getRolesByUser($userId)))) {
                                        
                                        $btn = Html::a(AmosPrivileges::t('amosprivileges', 'Disable'),
                                            [
                                                '/privileges/privileges/disable',
                                                'userId' => $userId,
                                                'priv' => $model['name'],
                                                'type' => 1,
                                                'isCwh' => $model['isCwh'],
                                                'anchor' => $label
                                            ],
                                            [
                                                'class' => 'btn btn-navigation-primary',
                                                'style' => 'font-size:0.8em;'
                                            ]);
                                    } else {
                                        
                                        $btn = Html::a(AmosPrivileges::t('amosprivileges', 'Enable'),
                                            [
                                                '/privileges/privileges/enable',
                                                'userId' => $userId,
                                                'priv' => $model['name'],
                                                'type' => 1,
                                                'isCwh' => $model['isCwh'],
                                                'anchor' => $label
                                            ],
                                            [
                                                'class' => 'btn btn-navigation-primary',
                                                'style' => 'font-size:0.8em;'
                                            ]);
                                        
                                    }
                                    return $btn;
                                },
                            ]
                        ],
                        [
                            'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                            'template' => '{statusIcon}',
                            'buttons' => [
                                'statusIcon' => function ($url, $model) {
                                    $btn = '';
                                    if ($model['can']) {
                                        $btn = AmosIcons::show('check-circle', ['style' => 'color:green;']);
                                    }
                                    return $btn;
                                }
                            ]
                        ],
                        'name' => [
                            'format' => 'html',
                            'attribute' => 'name',
                            'label' => AmosPrivileges::t('amosprivileges', 'Privilege')
                        ],
                        'tooltip' => [
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a(AmosIcons::show('info',
                                    ['style' => 'color:green; font-size:1.5em;']), null,
                                    ['data-toggle' => 'tooltip', 'title' => $model['tooltip']]);
                            }
                        ],
                        'domains' => [
                            'label' => AmosPrivileges::t('amosprivileges', 'Active for domains'),
                            'format' => 'raw',
                            'value' => function ($model) use ($userId, $escape, $label) {
                                $model['name'] = strtolower(str_replace('REDACTOR_', '', $model['name']));
                                $modulo = \Yii::$app->getModule($model['name']);
                                if (($modulo instanceof open20\amos\privileges\interfaces\CategoriesRolesInterface)) {
                                    $model['domains'] = array_keys($modulo::getCategoryArrayRoleAssignedToUser($userId));
                                    $domains = '';
                                    
                                    $userId = Yii::$app->request->get('id');
                                    $domains = Html::beginForm('/privileges/privileges/save-categorie-roles?userId=' . $userId . '&anchor=' . $label,
                                        'post',
                                        [
                                            'id' => 'auth-assign-roles'
                                        ]);
                                    $domains .= \kartik\select2\Select2::widget([
                                        'name' => 'auth-assign-roles[newDomains]',
                                        'data' => $modulo::getCategoryArrayRole(),
                                        'value' => $model['domains'],
                                        'options' => [
                                            'multiple' => true,
                                            'placeholder' => AmosPrivileges::t('amosprivileges',
                                                'Select domains ...'),
                                        ],
                                        'pluginOptions' => [
                                            'escapeMarkup' => $escape,
                                            'allowClear' => true
                                        ],
                                    ]);
                                    $domains .= Html::hiddenInput('auth-assign-roles[name]', $model['name']);
                                    $domains .= Html::hiddenInput('auth-assign-roles[rolename]', 'REDACTOR');
                                    $btnSave = Html::submitButton(AmosPrivileges::t('amosprivileges',
                                        'Save changes'),
                                        [
                                            'class' => 'btn btn-navigation-primary pull-right',
                                            'style' => 'margin-top: 5px;'
                                        ]);
                                    $domains .= $btnSave . Html::endForm();
                                    return $domains;
                                }
                                return null;
                            }
                        ]
                    ]
                ]) ?>
            </div> <!-- card-body -->
        </div>
    <?php endif; ?>
    <?php if ($userProfileId != 0): ?>
        <?= Html::a(AmosPrivileges::t('amosprivileges', 'Close'),
            ['/' . AmosAdmin::getModuleName() . '/user-profile/update', 'id' => $userProfileId, '#' => 'tab-administration'],
            ['class' => 'btn btn-navigation-primary pull-right'])
        ?>
    <?php endif; ?>
</div>
