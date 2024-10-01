<?php
/**
 * @var \luya\cms\base\PhpBlockView $this
*/

use open20\design\assets\BootstrapItaliaDesignAsset;
use luya\cms\helpers\BlockHelper;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
use app\modules\uikit\BaseUikitBlock;

$canSeeBlock = true;
$visibility = $this->varValue('visibility');

switch($visibility){
    case 'guest':
        $canSeeBlock = Yii::$app->user->isGuest;          
    break;
    case 'logged':
        $canSeeBlock = !Yii::$app->user->isGuest; 
		$n_class = $this->varValue('addclass');
		if($canSeeBlock && !empty($n_class)){
			$canSeeBlock = BaseUikitBlock::visivility($n_class);
		}
    break;
}

?>

<?php if ($canSeeBlock): ?>
	<?php if (!empty($this->extraValue('fileList'))): ?>
		<div class="it-list-wrapper">
			<ul class="it-list">
				<?php foreach ($this->extraValue('fileList') as $file) :
					$fileUpload = BlockHelper::fileUpload($file['file']);
					if(!isset($file['nome_sostitutivo']) || empty($file['nome_sostitutivo'])) {
						if($this->cfgValue('showType')) {
							$file['nome_sostitutivo'] = $fileUpload['name'];
						} else {
							$file['nome_sostitutivo'] = substr($fileUpload['name'], 0, - (strlen($fileUpload['extension']) + 1));
						}
					} else {
						if($this->cfgValue('showType')) {
							$file['nome_sostitutivo'] .= ' (' . strtoupper($fileUpload['extension']) . ')';
						}
					}
					?>
					<li class="w-100 position-relative">
						<a target="_blank" href="<?= $fileUpload['href']; ?>" data-original-title="<?= Yii::t('amosplatform', 'Scarica il documento').' '.$file['nome_sostitutivo'] ?>" data-toggle="tooltip" data-pjax="0">
							<div class="it-rounded-icon ml-0">
										<?php if ((in_array(strtolower($fileUpload['extension']), ['jpg', 'png', 'jpeg', 'svg']))) : ?>
											<svg class="icon icon-image">
												<use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-image"></use>
											</svg>
										<?php elseif ((in_array(strtolower($fileUpload['extension']), ['pdf']))) : ?>
											<svg class="icon icon-pdf">
												<use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-pdf"></use>
											</svg>
										<?php elseif ((in_array(strtolower($fileUpload['extension']), ['doc', 'docx']))) : ?>
											<svg class="icon icon-word">
												<use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-word"></use>
											</svg>
										<?php elseif ((in_array(strtolower($fileUpload['extension']), ['xls', 'xlsx']))) : ?>
											<svg class="icon icon-excel">
												<use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-excel"></use>
											</svg>
										<?php elseif ((in_array(strtolower($fileUpload['extension']), ['zip', 'rar']))) : ?>
											<svg class="icon icon-secondary">
												<use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#folder-zip"></use>
											</svg>
										<?php else : ?>
											<svg class="icon icon-secondary">
												<use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-link"></use>
											</svg>
										<?php endif ?>
							</div>
							<div class="it-right-zone">
									
									<span class="text">
										<?= $file['nome_sostitutivo']; ?>
										<?php if($this->cfgValue('showSize')) : ?>
											- <?= round(($fileUpload['size']/1024)).'KB'; ?>
										<?php endif; ?>
									</span>

								<!-- <p class="mb-0 text-black">< ?= $file['description']; ?></p>-->

							</div>

						</a>

					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
<?php endif;
