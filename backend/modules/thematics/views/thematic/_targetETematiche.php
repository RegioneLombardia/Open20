<?php

use open20\amos\admin\models\UserProfile;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use kartik\widgets\SwitchInput;
use open20\design\assets\BootstrapItaliaCustomAsset;
use open20\design\components\bootstrapitalia\ActiveForm;
use preference\userprofile\utility\TargetAttributeUtility;
use preference\userprofile\utility\TargetTagUtility;
use preference\userprofile\utility\TopicTagUtility;
use preference\userprofile\utility\UserInterestTagUtility;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\View;

$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);


?>

<div class="row">
	<div class="col-md-12">
		<p class="text-muted font-weight-bold text-uppercase">Canali</p>
	</div>

	<?php foreach (ArrayHelper::map(UserInterestTagUtility::getAllChannels(true), 'id', 'title') as $key => $value) : ?>

		<div class="toggles d-flex col-12 col-sm-4">
			<label for="toggle-stato-id-<?= $value ?>">
				<?php
				$channel = \preference\userprofile\models\PreferenceChannel::findOne($key);

				echo SwitchInput::widget([
					'name' => 'canale[' . $channel->id . ']',
					'options' => ['class' => 'target'],
					'pluginOptions' => [
						'size' => 'mini',
					],
					'value' => $channel->active,
				]);
				?>
			</label>
			<p class="text-muted font-weight-bold ml-2 text-center text-uppercase"><?= $value ?></p>
		</div>
	<?php endforeach; ?>
</div>
<?php
$listOfTarget = preference\userprofile\utility\TargetTagUtility::getAllTargetTag();
foreach ($listOfTarget

	as $key => $target) :
	$targetAttributes = TargetAttributeUtility::getAttributesByUserCode(Yii::$app->user->id, $target->codice);

	$attributes = TopicTagUtility::getAllTopicByTargetCode($target->codice, 1);
?>

	<hr class="dashed my-5">

	<div class="tematiche-container">

		<div class="row">
			<div class="col-md-8 <?= ($isSelected) ?: 'opacity-5' ?>">
				<div class="d-flex align-items-center mb-4">
					<svg class="icon icon-lg mr-2">
						<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#<?= TargetAttributeUtility::getTargetIcon($target->nome)?>"></use>
					</svg>
					<p class="font-weight-bold mb-0">Target: <span class="text-uppercase"><?= $target->nome ?></span></p>


				</div>

			</div>
		</div>

		<div class="tematiche">
			<div class="mb-3">
				<p class="text-muted font-weight-bold text-uppercase">tematiche</p>
			</div>
			<?php

			foreach ($attributes as $attribute) :
				$selectedTopic = 0;
			?>

				<div>
					<div class="form-check form-check-group d-flex">
						<label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold"><?= $attribute->nome ?></label>
						<?php
						$selectedTopic = $attribute->pc_active;
						echo SwitchInput::widget([
							'name' => 'topic[' . $attribute->id . ']',
							'options' => ['class' => 'topic', 'id' => 'topic-' . $attribute->id],
							'pluginOptions' => ['size' => 'mini'],
							'value' => $selectedTopic,
						]);
						?>
					</div>
				</div>

			<?php
			endforeach;
			?>
		</div>
	</div>
<?php
endforeach;
?>
<div id="messages-attributes-id"></div>