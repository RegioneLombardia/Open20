<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\widgets;

use open20\amos\community\models\Community;
use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\models\CwhNodi;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class DestinatariNEW
 * @package open20\amos\cwh\widgets
 */
class DestinatariNEW extends Widget
{
    public $moduleCwh, $scope;

    /**
     * @var \yii\widgets\ActiveForm $form
     */
    protected $form = null;

    /**
     * @var \yii\db\ActiveRecord $model
     */
    protected $model = null;

    /**
     * @var string
     */
    protected $nameField = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!isset($this->nameField)) {
            $refClass = new \ReflectionClass(get_class($this->getModel()));
            $this->setNameField($refClass->getShortName());
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
	$destinatariForScope = [];
        $enableDestinatariFatherChildren = isset(AmosCwh::getInstance()->enableDestinatariFatherChildren) ? AmosCwh::getInstance()->enableDestinatariFatherChildren
                : false;

        $networkModels = CwhConfig::find()
            ->andWhere(['<>', 'tablename', 'user'])
            ->all();


        $this->scope = AmosCwh::getInstance()->getCwhScope();
        $scopeFilter = (empty($this->scope)) ? false : true;

        //GROUP BY PER CWH CONFIG ID O AND WHERE TIPO RETE
        if ($scopeFilter) {
            $networks = CwhNodi::find()
                ->andWhere(['LIKE', 'id', 'community'])
                ->all();
        } else {
            $networks = CwhNodi::find()
                ->andWhere(['NOT LIKE', 'id', 'user'])
                ->andWhere(['NOT LIKE', 'id', 'community'])
                ->all();
        }

        $uid = Yii::$app->user->id;
        if (!$this->model->isNewRecord) {
            $uid = $this->model->created_by;
        }

        /** @var CwhConfig $networkModel */
        foreach ($networkModels as $networkModel) {
            $networkObject = Yii::createObject($networkModel->classname);

            $children     = [];
            $destinatari  = [];
            $categoryName = '';

            /** @var Community $model */
            if ($networkObject instanceof ModelLabelsInterface) {
                /** @var ModelGrammarInterface $networkModelGrammar */
                $networkModelGrammar = $networkObject->getGrammar();
                if ($networkModelGrammar) {
                    $categoryName = AmosCwh::t('messages', 'Solo gli utenti delle ').$networkModelGrammar->getModelLabel();
                }
            }

            $destinatariForScope[$categoryName] = [];
            $destinatariData[$categoryName]     = [];
            $networkIds                         = [];
            $userIds                            = [];

            if ($scopeFilter && $enableDestinatariFatherChildren && !empty($this->scope[$networkModel->tablename])) {
                $children = $this->getNetworkChildren($this->scope[$networkModel->tablename], $networkModel);
            }

            /** @var CwhNodi $network */
            foreach ($networks as $network) {
                if ($scopeFilter) {
                    if (isset($this->scope[$networkModel->tablename]) && ($network->record_id == $this->scope[$networkModel->tablename])) {
                        $destinatariForScope[] = $network;
                    }

                    if ($enableDestinatariFatherChildren && isset($this->scope[$networkModel->tablename]) && (
                        $network->record_id == $this->scope[$networkModel->tablename] || $this->isNetworkChild($network->record_id,
                            $children) || $this->isNetworkFather($network->record_id,
                            $this->scope[$networkModel->tablename], $networkModel) || $this->isNetworkBrother($network->record_id,
                            $this->scope[$networkModel->tablename], $networkModel))
                    ) {

                        $destinatari[] = $network;
                    }
                } else {
                    if ($network->classname == $networkModel->classname) {
                        $networkIds[$networkModel->classname][] = $network->record_id;
                        $userIds[$networkModel->classname][]    = $uid;
                    }
                }
                if (!$scopeFilter || ($scopeFilter && $enableDestinatariFatherChildren)) {
                    if (sizeof($destinatari) > 0) {
                        if (!array_key_exists($categoryName, $destinatariData)) {
                            $arr             = [$categoryName => ArrayHelper::map($destinatari, 'id', 'text')];
                            $destinatariData = array_merge($destinatariData, $arr);
                        } else {
                            $destinatariData[$categoryName] = array_merge($destinatariData[$categoryName],
                                ArrayHelper::map($destinatari, 'id', 'text'));
                        }
                    }
                }
            }

            $rows = [];
            if (isset($networkIds[$networkModel->classname]) && $networkObject->hasMethod('getListOfRecipients')) {
                $rows = $networkObject->getListOfRecipients(
                    $networkIds[$networkModel->classname], $userIds[$networkModel->classname]
                );

                $destinatariForScope[$categoryName] = $destinatariData[$categoryName]     = ArrayHelper::map($rows,
                        'objID', 'name');
            }

            if (count($destinatariForScope[$categoryName]) == 0) {
                unset($destinatariForScope[$categoryName]);
            }

            if (count($destinatariData[$categoryName]) == 0) {
                unset($destinatariData[$categoryName]);
            }
        }

        if ($this->getModel() instanceof ModelLabelsInterface) {
            $labelSuffix = ' '
                .$this->getModel()->getGrammar()->getArticleSingular()
                .' '
                .$this->getModel()->getGrammar()->getModelSingularLabel();
        } else {
            $labelSuffix = ' '.AmosCwh::t('amoscwh', 'il contenuto');
        }

        // if we are not under a specific network domain (destinatari and publication rule 3 are not automatically selected)
        if (!$scopeFilter) {
            return $this->renderWithoutScope($destinatariData, $labelSuffix);
        }

        // if we are inside a specific scope and the pubblication for network father and children is enabled
        if ($scopeFilter && $enableDestinatariFatherChildren) {
            return $this->renderWithScopeFatherChildren($destinatariForScope, $destinatariData, $labelSuffix);
        }

        // if we are inside a scope
        return $this->renderWithScope($destinatariForScope, $labelSuffix);
    }

    /**
     * @return \yii\db\ActiveRecord
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param \yii\db\ActiveRecord $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return \yii\widgets\ActiveForm
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param \yii\widgets\ActiveForm $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getNameField()
    {
        return $this->nameField;
    }

    /**
     * @param string $nameField
     */
    public function setNameField($nameField)
    {
        $this->nameField = $nameField;
    }

    /**
     * @param $currentId
     * @param $networkId
     * @param $networkModel
     * @return bool
     */
    public function isNetworkFather($currentId, $networkId, $networkModel)
    {
        if (!empty($networkId) && $networkModel) {
            $classname     = $networkModel->classname;
            $networkFather = $classname::findOne($networkId);
            return $currentId == $networkFather->parent_id;
        }
        return false;
    }

    /**
     * @param $currentId
     * @param $networkId
     * @param $networkModel
     * @return bool
     */
    public function isNetworkBrother($currentId, $networkId, $networkModel)
    {
        if (!empty($networkId) && $networkModel) {
            $brothersIds = [];
            $classname   = $networkModel->classname;
            $network     = $classname::findOne($networkId);

            if ($network) {
                $networkFather = $classname::findOne($network->parent_id);
                if ($networkFather) {
                    $brothers = $classname::find()->andWhere(['parent_id' => $networkFather->id])->all();
                    foreach ($brothers as $brother) {
                        $brothersIds [] = $brother->id;
                    }
                    return in_array($currentId, $brothersIds);
                }
            }
        }
        return false;
    }

    /**
     * @param $currentId
     * @param $childrenIds
     * @return bool
     */
    public function isNetworkChild($currentId, $childrenIds)
    {
        if (!empty($currentId)) {
            return in_array($currentId, $childrenIds);
        }
        return false;
    }

    /**
     * @param $networkId
     * @param $networkModel
     * @param null $model
     * @return array
     */
    public function getNetworkChildren($networkId, $networkModel, $model = null)
    {
        $children = [];
        /** INIZIALIZE THE MODEL */
        /** first step */
        if (!empty($networkId) && $networkModel) {
            $classname       = $networkModel->classname;
            $networkchildren = $classname::find()->andWhere(['parent_id' => $networkId])->all();
        } /** other steps */ else {
            $children []     = $model->id;
            $classname       = $model->className();
            $networkchildren = $classname::find()->andWhere(['parent_id' => $model->id])->all();
        }

        /** exit condition for recursive */
        if (count($networkchildren) == 0) {
            return $children;
        }

        /** MERGE THE RESULTS FORM THE  REVURSIVE CALLS */
        /** @var  $networkChild */
        foreach ($networkchildren as $networkChild) {
            $children = ArrayHelper::merge($children, $this->getNetworkChildren(null, null, $networkChild));
        }
        return $children;
    }

    /**
     * @param $destinatariData
     * @param $labelSuffix
     * @return string
     */
    public function renderWithoutScope($destinatariData, $labelSuffix)
    {
	$destinatariFromScope = false;
        $destinatariHidden = '';

        $primo_piano_checkbox = Yii::$app->user->can('UROI_LANDING') ? (Yii::$app->getModule('news')->params['site_publish_enabled']
                ? Html::checkbox(
                'landing-checkbox', $this->model->regola_pubblicazione == 5,
                ['label' => AmosCwh::t('message', 'Proponi pubblicazione sul portale pubblico'), 'id' => 'landing-checkbox'])
                : '') : '';

	if (!$this->getModel()->isNewRecord) {
            $destinatariDataTmp = [];
            foreach ($this->getModel()->destinatari as $cwhNodiId) {
                $cwhNodo = CwhNodi::findOne(['id' => $cwhNodiId, 'cwh_config_id' => 3]);
                if (!is_null($cwhNodo)) {
                    $destinatariDataTmp[$cwhNodiId] = $cwhNodo->text;
					$destinatariHidden .= Html::hiddenInput($this->getNameField().'[destinatari][]', $cwhNodiId);
                }
            }
 
            if (!empty($destinatariDataTmp)) {
                $destinatariData = $destinatariDataTmp;
                $destinatariFromScope = true;
            }
        }

        return $this->getForm()->field($this->getModel(), 'destinatari')->widget(
                \kartik\select2\Select2::className(),
                [
                'name' => $this->getNameField().'[destinatari]',
                'data' => $destinatariData,
                'options' => [
                    'multiple' => true,
                    'placeholder' => AmosCwh::t('amoscwh', 'Nella piattaforma'),
                    'name' => $this->getNameField().'[destinatari]',
                    'id' => 'cwh-destinatari'
                ],
                'showToggleAll' => false,
                'pluginOptions' => [
                    'tags' => true,
                    'maximumInputLength' => 10,
                ],
                'disabled' => !count($destinatariData) || $destinatariFromScope,
                ]
            )->label(AmosCwh::t('amoscwh', 'Dove vuoi mostrare ').$labelSuffix).$destinatariHidden.$primo_piano_checkbox;
    }

    /**
     * If we are under a network domain, publication is blocked for that network
     *
     * @param $destinatariForScope
     * @param $labelSuffix
     * @return
     */
    public function renderWithScope($destinatariForScope, $labelSuffix)
    {
        $destinatariField = $this
            ->getForm()
            ->field(
                $this->getModel(), 'destinatari'
            )
            ->label(AmosCwh::t('amoscwh', 'Dove vuoi mostrare '.$labelSuffix));

        $destinatariField->template = "<div class=\"row\">
                <div class=\"col-xs-12\">{label}</div>
                <div class=\"col-xs-12\"> <span class=\"tooltip-field pull-right\"> {hint} </span> <span class=\"tooltip-error-field pull-right\"> {error} </span> </div>
            \n<div class=\"col-xs-12\">".\kartik\select2\Select2::widget([
                'id' => 'placeholder-destinatari',
                'name' => 'placeholder-destinatari',
                'data' => [0 => isset($destinatariForScope[0]['text']) ? $destinatariForScope[0]['text'] : ''],
                'readonly' => true,
            ])."{input}</div>
            </div>";

        $destinatariField->hiddenInput([
            'value' => (isset($destinatariForScope[0]['id']) ? $destinatariForScope[0]['id'] : null
            ),
            'id' => 'cwh-destinatari', 'name' => $this->getNameField().'[destinatari][]'
        ]);

        return $destinatariField;
    }

    /**
     * @param $destinatariForScope
     * @param $destinatariData
     * @param $labelSuffix
     * @return
     */
    public function renderWithScopeFatherChildren($destinatariForScope, $destinatariData, $labelSuffix)
    {
        $currentNewtworkScope = $destinatariForScope[0]['id'];
        if (empty($this->getModel()->destinatari)) {
            $this->getModel()->destinatari = $destinatariForScope[0]['id'];
        }

        return $this->getForm()->field($this->getModel(), 'destinatari')->widget(
                \kartik\select2\Select2::className(),
                [
                'name' => $this->getNameField().'[destinatari]',
                'data' => $destinatariData,
                'options' => [
                    'multiple' => true,
                    'placeholder' => AmosCwh::t('amoscwh', 'Nella piattaforma'),
                    'name' => $this->getNameField().'[destinatari]',
                    'id' => 'cwh-destinatari',
                ],
                'showToggleAll' => false,
                'pluginOptions' => [
                    'tags' => true,
                    'maximumInputLength' => 10,
                ],
                'pluginEvents' => [
                    "select2:unselecting" => "function(event) {
                    if ('$currentNewtworkScope' == event.params.args.data.id) {
                        return false;
                    }
                }",
                ],
                'disabled' => !(count($destinatariForScope) && (count($destinatariData))),
                ]
            )->label(AmosCwh::t('amoscwh', 'Dove vuoi mostrare ').$labelSuffix);
    }
}