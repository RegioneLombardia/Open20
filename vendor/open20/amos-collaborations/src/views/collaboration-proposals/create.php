<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @open20/amos/collaborations/views/views 
 */
/**
* @var yii\web\View $this
* @var open20\amos\collaborations\models\CollaborationProposals $model
*/

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;
use yii\helpers\Url;

$this->title = \Yii::t('amoscore', 'Crea', ['modelClass' => 'Collaboration Proposals']);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/collaborations']];
$this->params['breadcrumbs'][] = ['label' => \Yii::t('amoscollaborations', 'Proposte di collaborazione'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['forceBreadcrumbs'][] = ['label' => \Yii::t('amoscollaborations', 'Proposte di collaborazione'),  'url' => ['/collaborations/collaboration-proposals/own-interest']];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];

?>
<div class="collaboration-proposals-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
