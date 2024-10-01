<?php
echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
use open20\amos\sondaggi\models\SondaggiDomandePagine;
use open20\amos\sondaggi\models\SondaggiDomandeTipologie;
use open20\amos\sondaggi\models\SondaggiDomandeCondizionate;
use open20\amos\sondaggi\models\SondaggiRispostePredefinite;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiDomande;
use open20\amos\sondaggi\models\SondaggiRisposte;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use open20\amos\sondaggi\models\SondaggiStato;
use open20\amos\core\helpers\Html;
use yii\helpers\Url;
use open20\amos\core\forms\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\attachments\components\AttachmentsInput;
use kartik\file\FileInput;
use open20\amos\attachments\components\AttachmentsList;
use open20\amos\sondaggi\assets\ModuleRisultatiFrontendAsset;
use open20\amos\sondaggi\assets\ModuleSondaggiAsset;

/**
* Questa è la view per la pagina "<?= $generator->paginaSondaggio ?>" del sondaggio "<?= $generator->titoloSondaggio ?>".
*
*/

$title = '<?= str_replace("'", "\'", $generator->titoloSondaggio); ?>';
$showTitle = <?= $sondaggio->visualizza_solo_titolo ?>;

if (!
    (
        $showTitle == 1
        || date("Y-m-d") > '<?= $sondaggio->close_date ?>'
    )
) {
    $title = 'Compila: ' . $title;
}

$this->title = AmosSondaggi::t('amossondaggi', $title);
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
if(!isset($libero)){
$libero = FALSE;
}

<?php
echo "\n?>\n";
?>
<script
  src="https://code.jquery.com/jquery-3.6.3.min.js"
  integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
  crossorigin="anonymous"></script>
<div>
    <?php
    $sondaggioLive = ($sondaggio->sondaggio_type == \open20\amos\sondaggi\models\base\SondaggiTypes::getLiveType());
    $domandaLive = 0;
    if ($sondaggioLive) {
        $domandaLive = $sondaggio->getSondaggiDomandes()->one()->id;
    }
    if($sondaggio->use_get_language == 1){
         echo "<?php\n \Yii::\$app->language = \$language \n?>\n";
    } else if ($sondaggio->frontend == 1 && !$sondaggioLive) {
        echo "<?php \n"
            . "if(strpos(\yii\helpers\Url::current(), '/frontend/compila?id') !== false){"
            . "\$sondaggioFrontendAsset = ModuleRisultatiFrontendAsset::register(\$this); \n"
            . "?>\n";
        if (!empty($sondaggio->forza_lingua)) {
            echo "<?php\n \Yii::\$app->language = '" . $sondaggio->forza_lingua . "'; \n?>\n";
        }
        $baseurl = '<?= $sondaggioFrontendAsset->baseUrl ?>';
    ?>
        <div class="container-padding">
            <div class="flexbox">
                <div>
                <img class="icon-sondaggio" src="<?= $baseurl ?>/img/poll.png">
                </div>
                <h1><?= "<?= AmosSondaggi::t('amossondaggi', '".  (str_replace("'", "\'", $generator->titoloSondaggio)) . "') ?>" ?></h1>

            </div>

            <div style="background: #ddd;">
                <?php if ($progress > 0) : ?>
                    <div class="simple-progressbar" style="height:24px;min-width:10%;width:<?= $progress ?>%;background: green;text-align:center;color:#fff"><?= $progress ?>%
                    </div>
                <?php else : ?>
                    <div class="simple-progressbar" style="height:24px;width:<?= $progress ?>%;background: green;text-align:center;color:#000;position:relative;left:0px;"><?= $progress ?>%
                    </div>
                <?php endif; ?>
            </div>

        </div>

    <?php
        echo "<?php \n"
            . "}"
            . "?>\n";
    } else {
        echo "<?php \n"
            . "\$sondaggioAsset = ModuleSondaggiAsset::register(\$this); \n"
            . "?>\n";
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function() {

            // get all inputs in the poll form
            var $poll_form = $('.sondaggi-form'),
                $trigger_inputs = $poll_form.find('input, select, textarea')
                .not('.select2-search__field, .no-evaluate-input'),
                $poll_questions = $poll_form.find('[data-question_id]:not([data-question_id=""])'),
                $conditional_questions = $poll_form.find('[data-conditions]:not([data-conditions=""])'),
                poll_data = {},
                pushUniqueValues = function(array, value) {
                    if (value) {
                        if (Array.isArray(value)) {
                            value.forEach(function(v) {
                                v = parseInt(v);
                                if (array.indexOf(v) === -1) {
                                    array.push(v);
                                }
                            });
                        } else {
                            value = parseInt(value);
                            if (array.indexOf(value) === -1) {
                                array.push(value);
                            }
                        }
                    }
                },
                getQuestionValue = function($question) {
                    // initialize values array
                    var values = [];

                    // loop for each input of the question to collect values
                    $question.find('input, select, textarea')
                        .not('.select2-search__field, .no-evaluate-input').each(function() {

                            var value,
                                $input = $(this),
                                input_type = $input.prop('tagName') === 'TEXTAREA' ? 'textarea' : $input.attr('type');

                            // switch behaviour for different input types
                            switch (input_type) {
                                case ('radio'):
                                case ('checkbox'):
                                    // in case of radio or checkbox add values to array for every checked input
                                    if ($input.is(":checked")) {
                                        value = $input.val();
                                    }
                                    break;
                                case ('date'):
                                case ('file'):
                                case ('text'):
                                case ('textarea'):
                                    if ($input.val().trim().length) {
                                        value = 1;
                                    }
                                    break;
                                default:
                                    // default behaviour is to add question value(s) to array
                                    value = $input.val();
                                    break;
                            }

                            // push unique value in values array
                            if (value) {
                                pushUniqueValues(values, value);
                            }
                        });

                    return values;
                },
                updateQuestionValue = function($question) {
                    var question_id = $question.data('question_id');

                    if (question_id in poll_data) {

                        // update question values in poll data
                        poll_data[question_id].values = getQuestionValue($question);
                    }
                };

            // on startup populate poll data with all questions
            $poll_questions.each(function() {
                var $question = $(this),
                    question_id = $question.data('question_id');

                // add current question ID property and values
                poll_data[question_id] = {
                    question: $question,
                    values: getQuestionValue($question),
                    conditional_questions: [],
                };
            });

            // on startup hide all conditional questions
            $conditional_questions.hide();

            // on startup add conditional questions to poll data
            $conditional_questions.each(function() {

                // get data conditions object
                var $conditional_question = $(this),
                    conditions = $conditional_question.data('conditions');

                // collect conditions from all questions
                jQuery.each(Object.keys(conditions), function(k, key) {
                    if (key in poll_data) {
                        poll_data[key].conditional_questions.push({
                            element: $conditional_question,
                            conditions: conditions,
                        });
                    }
                });
                
                let requiredOnCondition = $(this).data('required_on_condition');
                let requiredQuestionId = $(this).data('question_id');
                if (requiredOnCondition){
                    $( "div[data-domanda="+requiredQuestionId+"]" ).addClass('required');
                }

            });

            // trigger change event on file remove button click
            $poll_form.on('click', '.fileinput-remove-button', function() {
                $(this).closest('.input-group').find('input').trigger('change');
            });

            // bind checks on inputs change action
            $trigger_inputs.each(function() {

                var $trigger_input = $(this);

                // bind change of the input
                $trigger_input.change(function() {

                    var $question = $trigger_input.closest('[data-question_id]:not([data-question_id=""])'); // current question element
                    question_id = $question.data('question_id'); // get question ID

                    // update question values in poll data
                    updateQuestionValue($question);

                    if (question_id in poll_data) {
                        if (poll_data[question_id].conditional_questions.length) {
                            poll_data[question_id].conditional_questions.forEach(function(v) {

                                // initialize visible bool variable to false
                                var visible = false;

                                // check for any question value if is in answers
                                jQuery.each(v.conditions, function(key, answers) {
                                    if (key in poll_data) {
                                        poll_data[key].values.forEach(function(value) {
                                            if (answers.indexOf(value) > -1) {
                                                visible = true;
                                            }
                                        });
                                    }
                                });

                                let requiredOnCondition = v.element.data('required_on_condition');
                                let requiredQuestionId = v.element.data('question_id');

                                // show or hide question
                                if (visible) {
                                     // add required on visible conditional questions required
                                    if (requiredOnCondition){
                                        $( "div[data-domanda="+requiredQuestionId+"]" ).addClass('required');
                                    }

                                    v.element.show();
                                } else {
                                    v.element.hide();
                                    
                                    if (requiredOnCondition){
                                        $( "div[data-domanda="+requiredQuestionId+"]" ).removeClass('required');
                                    }


                                    // get question inputs
                                    var $conditional_question_inputs = v.element.find('input, textarea, select');

                                    // empty all input values in the field group
                                    $conditional_question_inputs.each(function() {
                                        var $input_to_reset = $(this);

                                        if ($input_to_reset.attr('type') === 'checkbox' || $input_to_reset.attr('type') === 'radio') {
                                            $input_to_reset.prop('checked', false);
                                        } else {
                                            $input_to_reset.val("");
                                        }
                                    });

                                    // trigger change on first input to make it recursive
                                    $conditional_question_inputs.first().trigger("change");
                                }
                            });
                        }
                    }
                });
            });

            var submitClicked = false;

            $('#form_true_sondaggio-<?= $pagina->id ?>').submit(function(e) {
                var action = jQuery(e.target).attr('action');
                var deleteAction = action.indexOf('file/delete');
                if (submitClicked === false && deleteAction < 0) {
                    e.preventDefault();
                    return false;
                }
            });

            $('#form_true_sondaggio-<?= $pagina->id ?>').submit(function(e) {
                var action = jQuery(e.target).attr('action');
                var deleteAction = action.indexOf('file/delete');
                if (submitClicked === false && deleteAction < 0) {
                    e.preventDefault();
                    return false;
                }
            });

            $('#truesubmit-<?= $pagina->id ?>').click(function() {
                submitClicked = true;
                <?php if ($sondaggioLive) { ?>
                    if (poll_data[<?= $domandaLive ?>]['values'].length == 0) {
                        $('#sondaggiolive_error').show();
                    } else {
                        $('#sondaggiolive_error').hide();
                        $('#form_true_sondaggio-<?= $pagina->id ?>').submit();
                    }
                <?php } else { ?>
                    $('#form_true_sondaggio-<?= $pagina->id ?>').submit();
                <?php } ?>
            });

            // find elements
            var sortCheck = $(".sortable-response");

            if(sortCheck.length > 0){
                //Rendo draggabile le risposte
                sortCheck.sortable({
                    handle: ".dragger",
                });
            }

            jQuery('.mover').click(function(e) {
                var arrow = jQuery(this);
                var dir = arrow.data('direction');
                var row = arrow.parent().parent();

                switch (dir) {
                    case 'up':
                        var prev = row.prev();

                        if (prev) {
                            prev.before(row);
                        }
                        break;
                    case 'down':
                        var next = row.next();

                        if (next) {
                            next.after(row);
                        }
                        break;
                }

                //My Own Sortable
                var sortable = row.parents('.sortable-response');

                //Trigger Update
                sortable.trigger('sortupdate');
            });

            sortCheck.on('sortupdate', function(e, ui) {
                var questionId = jQuery(this).data('question');
                var sortInputsResponse = jQuery('input[type!="hidden"]', this);
                var sortQuestions = jQuery(this).parents('form>div.row');

                //Salvo ordinamento per il POST nelle risposte
                sortInputsResponse.each(function(k, v) {
                    var responseId = jQuery(this).val();
                    var sortInput = jQuery('input[type="hidden"][data-response="' + responseId + '"]');
                    sortInput.val(k + 1);
                });

                //Items to sort
                var toBeSorted = sortQuestions.children('[data-sorttype="response"]');

                //Element preceding the onnes to be sorted
                var whereToAppend = toBeSorted.first().prev();

                //Procedura di ordinamento
                var sorted = toBeSorted.sort(function(a, b) {
                    var aResponseId = jQuery(a).data('sortby');
                    var bResponseId = jQuery(b).data('sortby');
                    var aParent = jQuery('input[type="hidden"][data-response="' + aResponseId + '"]');
                    var bParent = jQuery('input[type="hidden"][data-response="' + bResponseId + '"]');
                    var aVal = aParent.val();
                    var bVal = bParent.val();

                    return (aVal < bVal) ? -1 : (aVal > bVal) ? 1 : 0;
                });

                //Ordino Graficamente le domandevincolate
                whereToAppend.after(sorted);
            });
        });
    </script>
    <div class="sondaggi-compilazione pagid-<?= $pagina->id ?>">
        <?php
        echo "<?php \n";
        ?>
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' =>
        'form_true_sondaggio-<?= $pagina->id ?>']]);
        <?php
        echo "\n ?>\n";
        ?>
        <div class="sondaggi-form">
            <?php
            echo "<?php \n";
            ?>
            $customView = Yii::$app->getViewPath() . '/imageField.php';
            <?php
            echo "\n ?>\n";
            ?>
            <?php if (!$sondaggioLive) { ?>
                <div class="row header-page d-flex flex-wrap my-4">
                    <div class="col-sm-7 col-md-9">
                        <?php if (!empty(trim($generator->paginaSondaggio))) { ?>
                            <h3 class="m-t-10 m-b-30 sondaggio"><?= "<?= AmosSondaggi::t('amossondaggi', '". (str_replace("'", "\'", $generator->paginaSondaggio)) . "') ?>" ?></h3>
                        <?php } ?>
                        <?php if (!empty(trim($generator->descPaginaSondaggio))) { ?>
                            <h4 class="m-b-30 sondaggio"><?= "<?= AmosSondaggi::t('amossondaggi', '".  (str_replace("'", "\'", $generator->descPaginaSondaggio)) . "') ?>" ?></h4>
                        <?php } ?>
                    </div>
                    <div class="sondaggi-page_immage col-sm-5 col-md-3 p-t-20">
                        <?php if (!empty($pagina->file)) { ?>
                            <img src="<?= $pagina->file->getWebUrl('square_large') ?>" title="Immagine della pagina" class="img-responsive" />
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <div class="row flex-column">
                <?php foreach ($campi as $campo) : ?>
                    <?= $campo ?>
                <?php endforeach; ?>
            </div>
            <div class="m-b-25" style="display:none; color: red;" id="sondaggiolive_error"><?= "<?= AmosSondaggi::t('amossondaggi', 'E\' necessario selezionare una risposta per votare') ?>" ?></div>
            <div class="text-right">
                <?php
                if (!empty($obbligatorie) && $obbligatorie > 0 && !$sondaggioLive) {
                    echo "<?= '* ' . \Yii::t('amossondaggi', 'campi obbligatori') ?>";
                }
                ?>
            </div>
        </div>
        <div id="form-actions" class="bk-btnFormContainer" <?= ($sondaggioLive ? 'style="display: block;"' : '') ?>>

            <?php
            $urlSondaggio = trim($sondaggio->url_chiudi_sondaggio);
            if ($sondaggioLive) {
                echo "
                <?= Html::button(
                                AmosSondaggi::t('amossondaggi', 'Vota'), [
                                'id' => 'truesubmit-" . $pagina->id . "',
                    'class' => 'btn btn-xs btn-primary'
                ]);
                ?>\n
            ";
            } else {
                echo "<?php
                if(!isset(\$attivita) && !\$libero): ?>\n
                <?php
				\$url_default = '/sondaggi/pubblicazione/index';
				if (date('Y-m-d') > '" . $sondaggio->close_date . "')
					\$url_default = '/sondaggi/pubblicazione/by-user-organization-closed';
				
                \$closeLink = !empty(\$url) ? \$url : \$url_default;
                echo Html::a(AmosSondaggi::t('amossondaggi', 'Chiudi'), " . (!empty($urlSondaggio && open20\amos\sondaggi\AmosSondaggi::instance()->enableRedirectionUrl) ? "'$urlSondaggio'" : "[\$closeLink]") . ", [
                    'class' => 'btn btn-secondary undo-edit'
                ]);
                ?>\n
                <?php endif; ?>\n
                <?php 
				if(date('Y-m-d') < '".$sondaggio->close_date."')
				echo Html::button(
                                ((\$idPagina == \$ultimaPagina)? AmosSondaggi::t('amossondaggi', 'Conferma') : AmosSondaggi::t('amossondaggi', 'Prosegui')), [
                                'id' => 'truesubmit-" . $pagina->id . "',
                    'class' => 'btn btn-navigation-primary'
                ]);
                ?>\n
            ";
            }
            ?>

        </div>
        <input type="hidden" name="idPagina" value="<?= '<?=$idPagina?>' ?>">
        <input type="hidden" name="idSessione" value="<?= '<?=$idSessione?>' ?>">
        <input type="hidden" name="utente" value="<?= '<?=$utente?>' ?>">
        <input type="hidden" name="urlLive" value="<?= '<?=$urlLive?>' ?>">
        <?php
        if($sondaggio->field_extra == 1){
        ?>
        <input type="hidden" name="field_extra" value="<?= '<?=$field_extra?>' ?>">
        <?php
        }
        echo "\n<?php ActiveForm::end(); ?>\n" ?>
    </div>
</div>
