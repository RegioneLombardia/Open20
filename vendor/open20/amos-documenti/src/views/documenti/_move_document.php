<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Proscriptions/license-default.txt to change this proscription
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

use yii\helpers\Html;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\cwh\models\CwhPubblicazioniCwhNodiEditoriMm;
use open20\amos\documenti\models\search\DocumentiSearch;
use open20\amos\documenti\models\DocumentiCartellePath;
use open20\amos\documenti\models\Documenti;
use open20\amos\core\forms\ActiveForm;
use yii\helpers\ArrayHelper;

$documentsModule = AmosDocumenti::instance();
if ($documentsModule->enableMoveDoc) {
    $jsMove = <<<JS
          
	
	$(document).on("click", ".open-modalMove", function () {
            var myDocId = $(this).data('id');
            $(".modal-body #docId").val( myDocId );
			
			var selectElement = document.getElementById("destination-select-id");
			
			var options = selectElement.options;

			for (var i = 0; i < options.length; i++) {
				options[i].disabled = false;
				options[i].style.display = "block";
				options[i].style.visibility = "visible";
				if (options[i].value == myDocId) {
					options[i].disabled = true;
					 options[i].style.display = "none";
					 options[i].style.visibility = "hidden";
				}
			}

        })
JS;
    $this->registerJs($jsMove);
}


$moduleCwh = \Yii::$app->getModule('cwh');
isset($moduleCwh) ? $showReceiverSection = true : null;
isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;
?>

<div class="modal" id="modalMove" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color:#fff !important">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title"> <?= AmosDocumenti::t('amoscommunity', 'Spostamento Documento/Cartella') ?>:</h5>

            </div>
            <div class="modal-body">
                <?php
                $form = ActiveForm::begin([
                    'action' => '/' . AmosDocumenti::getModuleName() . '/documenti/move',
                    'id' => 'form-move-group',
                ]);
                if ($scope['community']) {
                    $documentiSearch = new DocumentiSearch();

                    $communityId = $scope['community'];
                    $editorsNode = CwhPubblicazioniCwhNodiEditoriMm::find()->andWhere(['cwh_network_id' => $communityId])->all();
                    // $query = DocumentiSearch::find()->andWhere(['is_folder'=>1]);
                    $data = \yii\helpers\ArrayHelper::map($documentiSearch->getFoldersList($communityId, $parentId), 'id', 'titolo');
                    if ($parent) {
                        $data = ArrayHelper::merge(['0' => 'Radice'], $data);
                    }
                }
                ?>

                <p><?php echo \Yii::t('app', "Cartella di destinazione");
                    ?>
                </p>
                <select name="destinationFolder" class="form-control" id="destination-select-id">
                    <option value="" disabled selected><?= \Yii::t('app', "Seleziona la destinazione ...") ?></option>
                    <?php
					if($_GET['parentId']){
						echo "<option value='0'>Radice</option>";
					}
                    foreach ($data as $key => $value) {
						$modelDocumento = Documenti::findOne($key);
						$stringa = DocumentiCartellePath::getPath($modelDocumento);
						$percorso = $stringa.$value;						
                        echo "<option value='$key'>$percorso</option>";
                    };
                    ?>

                </select>
                <div class="modal-body">
                    <input type="hidden" name="docId" id="docId" />
                </div>



            </div>
            <div class="modal-footer row">
                <div class="m-t-10">
                    <?php
                    echo Html::button(
                        Yii::t('amoscommunity', 'Conferma'),
                        [
                            'class' => 'btn btn-success pull-right',
                            'value' => 'conferma',
                            'type' => 'submit',
                            'name' => 'submit-conferma',
                            'id' => 'submitConferma'
                        ]
                    );
                    ?>
                    <?php
                    echo Html::button(
                        Yii::t('amoscommunity', 'Annulla'),
                        [
                            'class' => 'btn btn-secondary pull-left',
                            'value' => 'Annulla',
                            'data-dismiss' => 'modal',
                        ]
                    );
                    ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>