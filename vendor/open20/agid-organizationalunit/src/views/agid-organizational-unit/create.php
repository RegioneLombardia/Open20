<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views 
 */
/**
* @var yii\web\View $this
* @var open20\agid\organizationalunit\models\AgidOrganizationalUnit $model
*/
use open20\agid\organizationalunit\Module;

$this->title = Yii::t('amosorganizationalunit', 'Crea Unità Organizzativa', [
    'modelClass' => 'Agid Organizational Unit',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/app']];
$this->params['breadcrumbs'][] = ['label' => Module::t('amosorganizationalunit', 'Organizational Unit'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="agid-organizational-unit-create">

    <?= 
        $this->render('_form', [
            'model' => $model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]) 
    ?>

</div>
