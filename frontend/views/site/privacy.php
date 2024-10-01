<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use Yii;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

//check to avoid whole page having title 'Privacy Policy' when rendered in a modal
if (empty($this->title)) {
    $this->title = Yii::t('amosapp', 'TRATTAMENTO DEI DATI PERSONALI');
}

?>
DA CREARE