<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\models
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\models;

use ReflectionClass;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\View;
use yii\gii\CodeFile;
use open20\amos\attachments\components\AttachmentsList;

/**
 * Class GeneratoreSondaggio
 * @package open20\amos\sondaggi\models
 */
class GeneratoreSondaggio extends \yii\base\Model {

        public $byBassRuleCwh = true;
	public $template = 'default';
	public $templates = [];
	public $ns = '';
	public $titoloSondaggio = '';
	public $paginaSondaggio = '';
	public $descPaginaSondaggio = '';
	public $baseClass = 'yii\base\Model';
	public $notRedeclare = [];

	/**
	 * Genera il Model nel percorso specificato per la pagina indicata
	 */
	public function creaModel( $percorso, $id, $percorso_validator, $ns ) {
		$className             = "Pagina_" . $id;
		$this->ns              = $ns;
		$pagina                = SondaggiDomandePagine::findOne( [ 'id' => $id ] );
		$domande               = $pagina->getSondaggiDomandes();
		$this->titoloSondaggio = $pagina->sondaggi->titolo;
		$this->paginaSondaggio = $pagina->titolo;
		$params                = [
			'className'   => "Pagina_" . $id,
			'labels'      => $this->generaLabel( $pagina ),
			'rules'       => $this->generateRules( $pagina, $percorso_validator ),
			'ns'          => $this->ns,
			'attributi'   => $this->generateAttributi( $pagina ),
			'funzioni'    => [],
			'salvataggio' => $this->generaSave( $id )
		];
		$files                 = ( new CodeFile(
			Yii::getAlias( '@' . str_replace( '\\', '/', $percorso ) ) . '/' . $className . '.php',
			$this->render( 'model.php', $params )
		) )->save();
	}

	/**
	 * Genera la classe Validatrice nel percorso specificato
	 *
	 */
	public function creaValidator( $percorso, $id ) {

		$this->ns              = $percorso;
		$pagina                = SondaggiDomandePagine::findOne( [ 'id' => $id ] );
		$domande               = $pagina->getSondaggiDomandes();
		$this->titoloSondaggio = $pagina->sondaggi->titolo;
		$this->paginaSondaggio = $pagina->titolo;
		foreach ( $domande->all() as $Domanda ) {
			if ( $Domanda['sondaggi_domande_tipologie_id'] == 9 && strlen( $Domanda['nome_classe_validazione'] ) > 0 ) {
				$className = $Domanda['nome_classe_validazione'];
				$params    = [
					'className' => $className,
					'ns'        => $this->ns,
				];
				$esiste    = SondaggiDomande::find()->andWhere( [ 'nome_classe_validazione' => $Domanda['nome_classe_validazione'] ] );
				if ( $esiste->count() == 0 ) {
					$files = ( new CodeFile(
						Yii::getAlias( '@' . str_replace( '\\', '/', $percorso ) ) . '/' . $className . '.php',
						$this->render( 'validator.php', $params )
					) )->save();
				}
			}
		}
	}

	/**
	 * Genera la View nel percorso specificato per la pagina indicata
	 *
	 */
	public function creaView( $percorso, $id, $ns ) {
		$className     = "Pagina_" . $id;
		$this->ns      = $ns;
		$pagina        = SondaggiDomandePagine::findOne( [ 'id' => $id ] );
		$nPagine       = SondaggiDomandePagine::find()->andWhere( [ 'sondaggi_id' => $pagina->sondaggi_id ] )->count();
		$allPagine     = SondaggiDomandePagine::find()->andWhere( [ 'sondaggi_id' => $pagina->sondaggi_id ] )->orderBy( 'id' )->asArray()->all();
		$nObbligatorie = SondaggiDomande::find()->andWhere( [ 'sondaggi_domande_pagine_id' => $id ] )->andWhere( [ 'obbligatoria' => 1 ] )->count();
		$arrPagine     = [];
		foreach ( $allPagine as $k => $v ) {
			$arrPagine[ $v['id'] ] = $k;
		}
		$progress                  = round( bcdiv( bcmul( 100, $arrPagine[ $pagina->id ], 4 ), $nPagine, 4 ), 0 );
		$sondaggio                 = Sondaggi::findOne( [ 'id' => $pagina->sondaggi_id ] );
		$this->titoloSondaggio     = $pagina->sondaggi->titolo;
		$this->paginaSondaggio     = $pagina->titolo;
		$this->descPaginaSondaggio = $pagina->descrizione;
		$params                    = [
			'className'    => "Pagina_" . $id,
			'campi'        => $this->generaCampi( $pagina ),
			'ns'           => $this->ns,
			'sondaggio'    => $sondaggio,
			'pagina'       => $pagina,
			'progress'     => $progress,
			'obbligatorie' => $nObbligatorie,
		];
		$files                     = ( new CodeFile(
			Yii::getAlias( '@' . str_replace( '\\', '/', $percorso ) ) . '/' . $className . '.php',
			$this->render( 'view.php', $params )
		) )->save();
	}

	/**
	 * Genera le rules del model.
	 *
	 * @param open20\amos\sondaggi\models\SondaggiDomandePagine $pagina
	 *
	 * @return array L'array delle rules del model
	 */
	public function generateRules( SondaggiDomandePagine $pagina, $percorso_validator ) {
		$rules       = [];
		$userProfile = \Yii::$app->getUser()->getId();
		$domande     = $pagina->getSondaggiDomandes();
		foreach ( $domande->all() as $Domanda ) {
			$tipoCondizionataArr = [];
			$tipo                = SondaggiDomandeTipologie::findOne( $Domanda['sondaggi_domande_tipologie_id'] )->html_type;
			$condizionata        = $Domanda->domanda_condizionata;

			if ( $Domanda['obbligatoria'] && ! $condizionata ) {
				$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'required']";
			} else if ( $Domanda['obbligatoria'] && $Domanda['domanda_condizionata'] ) {

				$condizioni = SondaggiDomandeCondizionate::find()->andWhere( [ 'sondaggi_domande_id' => $Domanda['id'] ] )->all();

				foreach ( $condizioni as $cond1 ) {
					$rispostaCondizione   = SondaggiRispostePredefinite::find()->andWhere( [ 'id' => $cond1->sondaggi_risposte_predefinite_id ] )->one();
					$idRispostaCondizione = $rispostaCondizione->id;
					$domandaCondizionata  = $rispostaCondizione->sondaggi_domande_id;
					$DomandaCond          = SondaggiDomande::findOne( [ 'id' => $domandaCondizionata ] );
					$type                 = SondaggiDomandeTipologie::findOne( [ 'id' => $DomandaCond->sondaggi_domande_tipologie_id ] )->html_type;
					if ( ! in_array( $idRispostaCondizione, $tipoCondizionataArr[ $type ][ $domandaCondizionata ] ) ) {
						$tipoCondizionataArr[ $type ][ $domandaCondizionata ][] = $idRispostaCondizione;
					}
				}

				if ( $pagina->id == $Domanda->sondaggi_domande_pagine_id ) {
					$rules[] = "['domanda_" . $Domanda->id . "', 'required', 'when' => function(\$model) {\n"
					           . $this->getWhenCondition( $tipoCondizionataArr )
					           . "}, 'whenClient' => 'function (attribute, value) {
                            return $(attribute.container).is(\":visible\");
                            }'"
					           . "]\n";
				} else {
					$sessione     = SondaggiRisposteSessioni::findOne( [
						'sondaggi_id' => $Domanda['sondaggi_id'],
						'user_id'     => $userProfile
					] );
					$risposteDate = SondaggiRisposte::findOne( [
						'sondaggi_risposte_sessioni_id'    => $sessione->id,
						'sondaggi_domande_id'              => $condizione->sondaggi_domande_id,
						'sondaggi_risposte_predefinite_id' => $idRispostaCondizione
					] );
					if ( count( $risposteDate ) == 1 ) {
						$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'required']";
					}
				}
			}
			$domCondizione   = $Domanda->getSondaggiRispostePredefinitesCondizionate()->one();
			$padreCondizione = null;
			if ( ! empty( $domCondizione ) ) {
				$padreCondizione = $domCondizione->sondaggiDomande;
			}
			if ( $condizionata > 0 && ! empty( $padreCondizione ) && $padreCondizione->abilita_ordinamento_risposte == 1 ) {
				if ( ! empty( $domCondizione ) ) {
					$idRispC = $domCondizione->id;
					$text    = "[['ord_risp_{$padreCondizione->id}_{$idRispC}'], 'safe']";
					if ( ! in_array( $text, $rules ) ) {
						$rules[] = $text;
					}
				}
			}

			$min     = $Domanda['min_int_multipla'];
			$max     = $Domanda['max_int_multipla'];
			$minMaxRule = $this->generaRuleMinMax('domanda_' . $Domanda['id'], $min, $max);
			if (!empty($Domanda->parent_id)) {
				switch ($tipo) {
					case 'select':
						$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'each', 'rule' => ['integer']]";
						break;
					case 'radio':
						$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'integer']";
						break;
					case 'string':
						$rules[]           = "[['domanda_" . $Domanda['id'] . "'], 'each', 'rule' => ['string', 'max' => 255]]";
						$validazioniCustom = $Domanda->sondaggiDomandeRuleMms;
						if ( ! empty( $validazioniCustom ) ) {
							foreach ( $validazioniCustom as $validazione ) {
								$regola = $validazione->sondaggiDomandeRule;
								if ( ! empty( $regola ) ) {
									if ( $regola->custom == 0 ) {
										$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'each', 'rule' => ['{$regola->standard}']]";
									} else if ( $regola->custom == 1 ) {
										if ( ! empty( $regola->namespace ) ) {
											$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'each', 'rule' => ['{$regola->namespace}']]";
										}
										if ( ! empty( $regola->codice_custom ) ) {
											$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'each', 'rule' => [{$regola->codice_custom}]]";
										}
									}
								}
							}
						}
						break;
					case 'text':
						$rules[]           = "[['domanda_" . $Domanda['id'] . "'], 'each', 'rule' => ['string']]";
						$validazioniCustom = $Domanda->sondaggiDomandeRuleMms;
						if ( ! empty( $validazioniCustom ) ) {
							foreach ( $validazioniCustom as $validazione ) {
								$regola = $validazione->sondaggiDomandeRule;
								if ( ! empty( $regola ) ) {
									if ( $regola->custom == 0 ) {
										$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'each', 'rule' => ['{$regola->standard}']]";
									} else if ( $regola->custom == 1 ) {
										if ( ! empty( $regola->namespace ) ) {
											$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'each', 'rule' => ['{$regola->namespace}']]";
										}
										if ( ! empty( $regola->codice_custom ) ) {
											$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'each', 'rule' => [{$regola->codice_custom}]]";
										}
									}
								}
							}
						}
						break;

				}
			} else {
				switch ( $tipo ) {
					case 'checkbox':
						$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'safe']";
						if (!empty($min) || !empty($max)) {
							$rules[] = $minMaxRule;
						}
						break;
					case 'checkbox+text':
						$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'safe']";
						if (!empty($min) || !empty($max)) {
							$rules[] = $minMaxRule;
						}
						break;
					case 'radio':
						$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'integer']";
						break;
					case 'select':
						$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'integer']";
						break;
					case 'select-multiple':
						$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'safe']";
						if (!empty($min) || !empty($max)) {
							$rules[] = $minMaxRule;
						}
						break;
					case 'string':
						$rules[]           = "[['domanda_" . $Domanda['id'] . "'], 'string', 'max' => 255]";
						$validazioniCustom = $Domanda->sondaggiDomandeRuleMms;
						if ( ! empty( $validazioniCustom ) ) {
							foreach ( $validazioniCustom as $validazione ) {
								$regola = $validazione->sondaggiDomandeRule;
								if ( ! empty( $regola ) ) {
									if ( $regola->custom == 0 ) {
										$rules[] = "[['domanda_" . $Domanda['id'] . "'], '{$regola->standard}']";
									} else if ( $regola->custom == 1 ) {
										if ( ! empty( $regola->namespace ) ) {
											$rules[] = "[['domanda_" . $Domanda['id'] . "'], '{$regola->namespace}']";
										}
										if ( ! empty( $regola->codice_custom ) ) {
											$rules[] = "[['domanda_" . $Domanda['id'] . "'], {$regola->codice_custom}]";
										}
									}
								}
							}
						}
						break;
					case 'text':
						$rules[]           = "[['domanda_" . $Domanda['id'] . "'], 'string']";
						$validazioniCustom = $Domanda->sondaggiDomandeRuleMms;
						if ( ! empty( $validazioniCustom ) ) {
							foreach ( $validazioniCustom as $validazione ) {
								$regola = $validazione->sondaggiDomandeRule;
								if ( ! empty( $regola ) ) {
									if ( $regola->custom == 0 ) {
										$rules[] = "[['domanda_" . $Domanda['id'] . "'], '{$regola->standard}']";
									} else if ( $regola->custom == 1 ) {
										if ( ! empty( $regola->namespace ) ) {
											$rules[] = "[['domanda_" . $Domanda['id'] . "'], '{$regola->namespace}']";
										}
										if ( ! empty( $regola->codice_custom ) ) {
											$rules[] = "[['domanda_" . $Domanda['id'] . "'], {$regola->codice_custom}]";
										}
									}
								}
							}
						}
						break;
					case 'date':
						$rules[]           = "[['domanda_" . $Domanda['id'] . "'], 'safe']";
						$validazioniCustom = $Domanda->sondaggiDomandeRuleMms;
						if ( ! empty( $validazioniCustom ) ) {
							foreach ( $validazioniCustom as $validazione ) {
								$regola = $validazione->sondaggiDomandeRule;
								if ( ! empty( $regola ) ) {
									if ( $regola->custom == 0 ) {
										$rules[] = "[['domanda_" . $Domanda['id'] . "'], '{$regola->standard}']";
									} else if ( $regola->custom == 1 ) {
										if ( ! empty( $regola->namespace ) ) {
											$rules[] = "[['domanda_" . $Domanda['id'] . "'], '{$regola->namespace}']";
										}
										if ( ! empty( $regola->codice_custom ) ) {
											$rules[] = "[['domanda_" . $Domanda['id'] . "'], {$regola->codice_custom}]";
										}
									}
								}
							}
						}
						break;
					case 'img':
						$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'integer']";
						break;
					case 'img-multiple':
						$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'integer']";
						if (!empty($min) || !empty($max)) {
							$rules[] = $minMaxRule;
						}
						break;
					case 'file':
						$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'safe']";
						break;
					case 'file-multiple':
						$rules[] = "[['domanda_" . $Domanda['id'] . "'], 'safe']";
						break;
					case 'custom':
						$rules[] = "[['domanda_" . $Domanda['id'] . "'], '$percorso_validator" . $Domanda['nome_classe_validazione'] . "']";
						break;
				}
			}
		}

		return $rules;
	}

	/**
	 *
	 * @param type $condizioni
	 *
	 * @return type
	 */
	public function getWhenCondition( $condizioni ) {
		$cond = [];

		foreach ( $condizioni as $tipo => $value ) {
			foreach ( $value as $domandaCondizionata => $risposte ) {
				foreach ( $risposte as $idRispostaCondizione ) {
					$cond[] = "(in_array($idRispostaCondizione, (property_exists(\$model, 'domanda_$domandaCondizionata')? (is_array(\$model->domanda_$domandaCondizionata)? \$model->domanda_$domandaCondizionata : [\$model->domanda_$domandaCondizionata]) : [])))";
				}
			}
		}

		return "return " . implode( ' || ', $cond ) . ";\n";
	}

	/**
	 * Genera le rules del model.
	 *
	 * @param open20\amos\sondaggi\models\SondaggiDomandePagine $pagina
	 *
	 * @return array L'array delle rules del model
	 */
	public function generaCampi( SondaggiDomandePagine $pagina ) {
		$campi   = [];
		$domande = $pagina->getSondaggiDomandes()->orderBy( 'ordinamento ASC' );
		foreach ( $domande->all() as $Domanda ) {
			if (!empty($Domanda->parent_id)) continue;
			$tipo                     = SondaggiDomandeTipologie::findOne( $Domanda['sondaggi_domande_tipologie_id'] )->html_type;
			$idD                      = $Domanda['id'];
			$tooltip                  = addslashes( $Domanda->tooltip );
			$introduzione             = ( ( ! empty( trim( $Domanda->introduzione ) ) ) ? \Yii::$app->formatter->asHtml( str_replace( '<p></p>',
				'', trim( $Domanda->introduzione ) ) ) : '' );
			$introduzioneCondizionata = ( ( ! empty( trim( $Domanda->introduzione_condizionata ) ) ) ? \Yii::$app->formatter->asHtml( str_replace( '<p></p>',
				'', trim( $Domanda->introduzione_condizionata ) ) ) : '' );
			$tooltipHtml              = ! empty( $tooltip ) ? "<span class=\"tooltip-field m-l-10\">
                                <span title=\"\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"$tooltip\" aria-describedby=\"tooltip833635\">
                                    <span class=\"am am-help\">
                                    </span>
                                </span>
                                <div class=\"tooltip fade top\" role=\"tooltip\" id=\"tooltip833635\" style=\"top: -47px; left: 590.281px; display: none;\"><div class=\"tooltip-arrow\" style=\"left: 50%;\"></div>
                                    <div class=\"tooltip-inner\">$tooltip</div>
                                </div>
                            </span>" : '';
			$idPagina                 = $pagina->id;
			$js                       = "";
			$condizionata             = $Domanda['domanda_condizionata'];
			$inline                   = ( $Domanda['inline'] ) ? 'true' : 'false';
			if ( $condizionata ) {
				$js = $this->generaJavascript( $idD );
			}
			$arrRispPreCond = '';
			$sortable       = false;
			$idRispC        = null;

			/* TODO: maybe remove */
			$domCondizione = $Domanda->getSondaggiRispostePredefinitesCondizionate()->one();

			// build question conditions array
			$question_conditions = $Domanda->getSondaggiRispostePredefinitesCondizionate()->all();
			$conditions          = [];
			if ( ! empty( $question_conditions ) ) {
				foreach ( $question_conditions as $question_condition ) {
					$conditioning_question    = $question_condition->sondaggiDomande;
					$conditioning_question_id = $conditioning_question->id;
					$conditioning_answer_id   = $question_condition->id;

					if ( ! isset( $conditions[ $conditioning_question_id ] ) ) {
						$conditions[ $conditioning_question_id ] = [];
					}

					array_push( $conditions[ $conditioning_question_id ], $conditioning_answer_id );
				}
			}

			// add free text input condition
			if ( $Domanda->domanda_condizionata_testo_libero ) {
				$conditions[ $Domanda->domanda_condizionata_testo_libero ] = [ 1 ];
			}

			// save conditions data in a valid json data-attribute format
			$conditions_data = ! empty( $conditions ) ? htmlspecialchars( json_encode( $conditions ), ENT_QUOTES, 'UTF-8' ) : '';

			/*
			ob_flush();
			ob_start();
			echo '<pre>';
			print_r( $Domanda->domanda_condizionata_testo_libero );
			echo '</pre>';
			echo '<pre>';
			print_r( $conditions );
			echo '</pre>';
			file_put_contents( "dump.txt", ob_get_flush() );
			ob_end_clean();
			*/

			$padreCondizione  = null;
			$idParent         = null;
			$idRispostaParent = null;
			if ( ! empty( $domCondizione ) ) {
				$padreCondizione  = $domCondizione->sondaggiDomande;
				$idParent         = $padreCondizione->id;
				$idRispostaParent = $domCondizione->id;
			}

			$checkboxoptions = "['class' => 'sortable-response', 'tabindex' => \$read ? '-1' : null, 'data' => ['question' => '{$idD}'], 'itemOptions' => ['tabindex' => \$read ? '-1' : null]]";
			$generalOptions  = "['data' => ['domanda' => '{$idD}'], 'itemOptions' => ['tabindex' => \$read ? '-1' : null]]";
			if ( $Domanda->abilita_ordinamento_risposte ) {
				$sposasu  = \open20\amos\sondaggi\AmosSondaggi::t( 'amossondaggi', 'Sposta su' );
				$sposagiu = \open20\amos\sondaggi\AmosSondaggi::t( 'amossondaggi', 'Sposta giu' );
				//drag & drop momentaneamente tolto, andrà messo sotto parametro
				$draganddrop     = '<span class=\\\"am am-swap-vertical dragger\\\"></span>';
				$checkboxoptions = "['item' => function(\$index, \$label, \$name, \$checked, \$value) {"
				                   . "\$check = (\$checked? 'checked' : '');"
				                   . "return \"<div class=\\\"checkbox checkbox-sortable\\\"><label><input type=\\\"checkbox\\\" {\$check} name='{\\\$name}' value=\\\"{\$value}\\\"/>{\$label}</label><div class=\\\"direction\\\"><span class=\\\"am am-chevron-up mover\\\" title=\\\"$sposasu\\\" data-direction=\\\"up\\\"></span><span class=\\\"am am-chevron-down mover\\\" title=\\\"$sposagiu\\\" data-direction=\\\"down\\\"></span></div></div>\";}, 'class' => 'sortable-response', 'data' => ['question' => '{$idD}'], 'itemOptions' => ['tabindex' => \$read ? '-1' : null]]";
			}
			if ( $condizionata > 0 && ! empty( $padreCondizione ) && $padreCondizione->abilita_ordinamento_risposte == 1 ) {
				if ( ! empty( $domCondizione ) ) {
					$sortable = true;
					$idRispC  = $domCondizione->id;
					if ( ! in_array( $idRispC, $this->notRedeclare ) ) {
						$arrRispPreCond = "\n"
						                  . "<?php "
						                  . "if(empty(\$model->ord_risp_{$padreCondizione->id}_{$idRispC})){"
						                  . "\$model->ord_risp_{$padreCondizione->id}_{$idRispC} = $domCondizione->ordinamento;"
						                  . "}"
						                  . "\n echo \$form->field(\$model, 'ord_risp_{$padreCondizione->id}_{$idRispC}', ['options' => ['style' => 'display:none;']])->hiddenInput(['class' => 'no-evaluate-input', 'data' => ['response' => $idRispC, 'question' => {$padreCondizione->id}]])->label(false);\n"
						                  . "?>\n";
					}
					$this->notRedeclare[] = $idRispC;
				}
			}
			$extraAttributes = '';

			if ( strlen( $arrRispPreCond ) > 0 ) {
				$extraAttributes .= ' data-sorttype="response" ';
				$extraAttributes .= ' data-sortby="' . $idRispC . '" ';
			}

			$allegati = null;
			if ($Domanda->file) {
				$allegati = AttachmentsList::widget([
					'model' => $Domanda,
					'attribute' =>  'file',
					'viewDeleteBtn' => false
				]);
			}


			/* TODO: verificare se si può ed evitare di ripetere il testo introduttivo e l'apertura e chiusura del div in tutti i campi ma metterli prima e dopo lo switch */

			// Generazione form per domande multiple. Viene generata una tabella in base alla tipologia di risposta scelta
			if ($Domanda->is_parent) {
				$content = "<?php \$reflect = new \\ReflectionClass(\$model);?>";
				$content .= (! empty( $introduzione ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo\">$introduzione</div>" : '' )
				 . "<div class=\"col-lg-12 col-sm-12 sondaggi-content_domanda\" id=\"div-domanda_$idD\" data-question_id=\"$idD\" " . ( $conditions_data ? "data-conditions=\"$conditions_data\" " : "" ) . ">\n"
				 . ( ! empty( $introduzioneCondizionata ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo testo-vincolato\">$introduzioneCondizionata</div>" : '' )
				 . ( ! empty( $allegati ) ? "<div class=\"col-lg-12 col-sm-12\">$allegati</div>" : '' )
				 . "<div class=\"col-lg-12 col-sm-12\">".$Domanda->domanda."</div>";
				$content .= '<table class="table"><tr><th></th>';
				if ($tipo == 'radio') {
					$cols = SondaggiRispostePredefinite::find()->andWhere(['sondaggi_domande_id' => $idD])->orderBy('ordinamento ASC')->all();
					foreach($cols as $col) {
						$content .= '<th>'.$col->risposta.'</th>';
					}
				} else {
					foreach($Domanda->multi_columns as $key => $column) {
						$content .= '<th scope="col">'.$column.'</th>';
					}
				}
				$content .= '</th>';
				foreach($Domanda->getChildren()->all() as $child) {
					$content .= '<tr><th scope="row">'.$child->domanda.'</th>';
					switch ( $tipo ) {
						case 'select':
							foreach($Domanda->multi_columns as $key => $column) {
									$content .= "<td><?php echo \$form->field(\$model, 'domanda_{$child->id}', ['options' => ['name' =>  \$reflect->getShortName() . '[domanda_{$child->id}][$key]', 'style' => \$read ? 'pointer-events: none;' : null, 'data' => ['domanda' => '{$idD}']]])->widget(Select2::className(), ['data' => ArrayHelper::map(SondaggiRispostePredefinite::find()->andWhere(['sondaggi_domande_id' => {$idD}])->select(['id', 'risposta'])->all(), 'id', 'risposta'),\n"
									 . "'language' => substr(Yii::\$app->language, 0, 2),\n"
									 . "'options' => ['value' => [SondaggiRisposte::find()->andWhere(['sondaggi_domande_id' => {$child->id}, 'sondaggi_risposte_sessioni_id' => \$idSessione, 'column' => $key])->one()->sondaggi_risposte_predefinite_id], 'placeholder' => AmosSondaggi::t('amossondaggi', 'Seleziona una risposta ...'), 'id' => 'select-domanda_{$child->id}_{$key}', 'name' => \$reflect->getShortName() . '[domanda_{$child->id}][$key]', 'style' => \$read ? 'pointer-events: none;' : null, 'data' => ['question' => '{$child->id}']],\n"
									 . "'pluginOptions' => [\n"
									 . "    'allowClear' => true\n"
									 . "],\n"
									 . "'toggleAllSettings' => [\n"
									 . "'selectLabel' => '<i class=\"glyphicon glyphicon-unchecked\"></i>' . \Yii::t('amosapp', 'Seleziona tutto'),\n"
									 . "'unselectLabel' => '<i class=\"glyphicon glyphicon-check\"></i>' . \Yii::t('amosapp', 'Deseleziona tutto'),\n"
									 . "],\n"
									 . "])->label('');\n"
									 . "?></td>\n";
								}
							break;
							case 'radio':
								$content .= "<td><?php \n"
								 . "\$dati_{$child->id} = SondaggiRispostePredefinite::find()->andWhere(['sondaggi_domande_id' => $idD])->select(['id', 'risposta'])->orderBy('ordinamento ASC')->asArray()->all(); \n"
								 . "echo \$form->field(\$model, 'domanda_{$child->id}', ['enableLabel' => false, 'options' => [ 'style' => \$read ? 'pointer-events: none;' : null, 'data' => ['domanda' => '{$idD}']]])->inline($inline)->radioList(ArrayHelper::map(\$dati_{$child->id}, 'id', 'risposta'), ['separator' => '</td><td>'])->label(false);\n"
								 . "?></td>\n";
								break;

							case 'string':
								foreach($Domanda->multi_columns as $key => $column) {
									$content .= "<td><?php \n"
														 . "echo Html::input('text', \$reflect->getShortName() . '[domanda_{$child->id}][$key]', SondaggiRisposte::find()->andWhere(['sondaggi_domande_id' => {$child->id}, 'sondaggi_risposte_sessioni_id' => \$idSessione, 'column' => $key])->one(), ['options' => ['style' => \$read ? 'pointer-events: none;' : null, 'data' => ['domanda' => '{$child->id}']]]);\n"
														 . "?></td>\n";
									}
								break;

							case 'text':
								foreach($Domanda->multi_columns as $key => $column) {
									$content .= "<td><?php \n"
									 . "echo Html::input('text', \$reflect->getShortName() . '[{$child->id}][$key]', SondaggiRisposte::find()->andWhere(['sondaggi_domande_id' => {$child->id}, 'sondaggi_risposte_sessioni_id' => \$idSessione, 'column' => $key])->one(), ['options' => ['style' => \$read ? 'pointer-events: none;' : null, 'data' => ['domanda' => '{$child->id}']]]);\n"
									 . "?></td>\n";
									break;
							}
						}
					$content .= '</tr>';
				}
				$content .= '</table>'.$js.'</div>';
				$campi[] = $content;

			} else {
				// Generazione form per domande singole
				switch ( $tipo ) {
					case 'checkbox':
						$campi[] = ( ! empty( $introduzione ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo\">$introduzione</div>" : '' )
						           . "<div class=\"col-lg-12 col-sm-12 sondaggi-content_domanda\" id=\"div-domanda_$idD\" data-question_id=\"$idD\" " . ( $conditions_data ? "data-conditions=\"$conditions_data\" " : "" ) . ">\n"
						           . ( ! empty( $introduzioneCondizionata ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo testo-vincolato\">$introduzioneCondizionata</div>" : '' )
											 . ( ! empty( $allegati ) ? "<div class=\"col-lg-12 col-sm-12\">$allegati</div>" : '' )
												. "<?php \n"
						           . "\$dati_$idD = SondaggiRispostePredefinite::find()->andWhere(['sondaggi_domande_id' => $idD])->select(['id', 'risposta'])->orderBy('ordinamento ASC')->asArray()->all(); \n"
						           . "echo \$form->field(\$model, 'domanda_$idD', ['options' => ['style' => \$read ? 'pointer-events: none;' : null, 'data' => ['domanda' => '{$idD}']]])->inline($inline)->checkboxList(ArrayHelper::map(\$dati_$idD, 'id', 'risposta'), $checkboxoptions)->label(\$model->attributeLabels()[ 'domanda_$idD'] . '$tooltipHtml');\n"
						           . "?>\n"
						           . $js
						           . "</div>\n";
						break;
					/*case 'checkbox+text':
						$campi[] = ( ! empty( $introduzione ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo\">$introduzione</div>" : '' )
											 . "<div class=\"col-lg-12 col-sm-12 sondaggi-content_domanda\" id=\"div-domanda_$idD\" data-question_id=\"$idD\" " . ( $conditions_data ? "data-conditions=\"$conditions_data\" " : "" ) . ">\n"
											 . ( ! empty( $introduzioneCondizionata ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo testo-vincolato\">$introduzioneCondizionata</div>" : '' )
											 . ( ! empty( $allegati ) ? "<div class=\"col-lg-12 col-sm-12\">$allegati</div>" : '' )
												. "<?php \n"
											 . "\$dati_$idD = SondaggiRispostePredefinite::find()->andWhere(['sondaggi_domande_id' => $idD])->select(['id', 'risposta'])->orderBy('ordinamento ASC')->asArray()->all(); \n"
											 . "foreach(\$dati_$idD as \$answer) {?>\n"
											 . "<label for=''>Html::checkbox(\$reflect->getShortName() . '[domanda_{$child->id}][$key]', \$model, \$checked);</label><input type='text' name='' value=''><br>\n"
											 . "<?php}?>".$js".</div>";



											 . "echo \$form->field(\$model, 'domanda_$idD', ['options' => ['style' => \$read ? 'pointer-events: none;' : null, 'data' => ['domanda' => '{$idD}']]])->inline($inline)->checkboxList(ArrayHelper::map(\$dati_$idD, 'id', 'risposta'), \yii\helpers\ArrayHelper::merge($checkboxoptions, ['item' => function(\$index, \$label, \$name, \$checked, \$value)\n"
											 . "{return \"<label><input type='checkbox' {\$checked} name='{\$name}' value='{\$value}'>{\$label}</label><input type='text' name='\". \$reflect->getShortName() . \"[domanda_$idD][\".\$index.\"]'>\";}])->label(\$model->attributeLabels()[ 'domanda_$idD'] . '$tooltipHtml');\n"
											 . "?>\n"
											 . $js
											 . "</div>\n";
						break;*/
					case 'radio':

						$campi[] = ( ! empty( $introduzione ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo\">$introduzione</div>" : '' )
						           . "<div class=\"col-lg-12 col-sm-12 sondaggi-content_domanda\" id=\"div-domanda_$idD\" $extraAttributes data-question_id=\"$idD\" " . ( $conditions_data ? "data-conditions=\"$conditions_data\" " : "" ) . ">\n"
						           . ( ! empty( $introduzioneCondizionata ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo testo-vincolato\">$introduzioneCondizionata</div>" : '' )
											 . ( ! empty( $allegati ) ? "<div class=\"col-lg-12 col-sm-12\">$allegati</div>" : '' )
						           . "<?php \n"
						           . "\$dati_$idD = SondaggiRispostePredefinite::find()->andWhere(['sondaggi_domande_id' => $idD])->select(['id', 'risposta'])->orderBy('ordinamento ASC')->asArray()->all(); \n"
						           . "echo \$form->field(\$model, 'domanda_$idD', ['options' => ['style' => \$read ? 'pointer-events: none;' : null, 'data' => ['domanda' => '{$idD}']]])->inline($inline)->radioList(ArrayHelper::map(\$dati_$idD, 'id', 'risposta'), $generalOptions)->label(\$model->attributeLabels()[ 'domanda_$idD'] . '$tooltipHtml');\n"
						           . "?>\n"
						           . $js
						           . "</div>\n";
						break;
					case 'select':
						//$campi[] = "echo \$form->field(\$model, 'domanda_$idD')->dropDownList(ArrayHelper::map(SondaggiRispostePredefinite::find()->andWhere(['sondaggi_domande_id' => $idD])->select(['id', 'risposta'])->all(), 'id', 'risposta'), ['prompt' => AmosSondaggi::t('amossondaggi', 'Seleziona una risposta ...')]);";
						$campi[] = ( ! empty( $introduzione ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo\">$introduzione</div>" : '' )
						           . "<div class=\"col-lg-12 col-sm-12 sondaggi-content_domanda\" id=\"div-domanda_$idD\" data-question_id=\"$idD\" " . ( $conditions_data ? "data-conditions=\"$conditions_data\" " : "" ) . ">\n"
						           . ( ! empty( $introduzioneCondizionata ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo testo-vincolato\">$introduzioneCondizionata</div>" : '' )
											 . ( ! empty( $allegati ) ? "<div class=\"col-lg-12 col-sm-12\">$allegati</div>" : '' )
						           . "<?php \n"
						           . "echo \$form->field(\$model, 'domanda_$idD', ['options' => ['style' => \$read ? 'pointer-events: none;' : null,'data' => ['domanda' => '{$idD}']]])->widget(Select2::className(), ['data' => ArrayHelper::map(SondaggiRispostePredefinite::find()->andWhere(['sondaggi_domande_id' => $idD])->select(['id', 'risposta'])->all(), 'id', 'risposta'),\n"
						           . "'language' => substr(Yii::\$app->language, 0, 2),\n"
						           . "'options' => ['placeholder' => AmosSondaggi::t('amossondaggi', 'Seleziona una risposta ...'), 'id' => 'select-domanda_$idD', 'data' => ['question' => '{$idD}']],\n"
						           . "'pluginOptions' => [\n"
						           . "    'allowClear' => true\n"
						           . "],\n"
						           . "'toggleAllSettings' => [\n"
						           . "'selectLabel' => '<i class=\"glyphicon glyphicon-unchecked\"></i>' . \Yii::t('amosapp', 'Seleziona tutto'),\n"
						           . "'unselectLabel' => '<i class=\"glyphicon glyphicon-check\"></i>' . \Yii::t('amosapp', 'Deseleziona tutto'),\n"
						           . "],\n"
						           . "])->label(\$model->attributeLabels()[ 'domanda_$idD'] . '$tooltipHtml');\n"
						           . "?>\n"
						           . $js
						           . "</div>\n";
						break;
					case 'descrizione':
						$campi[] = ( ! empty( $introduzione ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo\">$introduzione</div>" : '' )
						           . "<div class=\"col-lg-12 col-sm-12 sondaggi-content_domanda\" id=\"div-domanda_$idD\" data-question_id=\"$idD\" " . ( $conditions_data ? "data-conditions=\"$conditions_data\" " : "" ) . ">\n"
						           . ( ! empty( $introduzioneCondizionata ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo testo-vincolato\">$introduzioneCondizionata</div>" : '' )
											 . ( ! empty( $allegati ) ? "<div class=\"col-lg-12 col-sm-12\">$allegati</div>" : '' )
						           . "<div class=\"testo-introduttivo testo-sezione\"><?= \$model->attributeLabels()['domanda_$idD'] . '$tooltipHtml' ?></div>\n"
						           . $js
						           . "</div>\n";
						break;
					case 'select-multiple':
						$campi[] = ( ! empty( $introduzione ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo\">$introduzione</div>" : '' )
						           . "<div class=\"col-lg-12 col-sm-12 sondaggi-content_domanda\" id=\"div-domanda_$idD\" data-question_id=\"$idD\" " . ( $conditions_data ? "data-conditions=\"$conditions_data\" " : "" ) . ">\n"
						           . ( ! empty( $introduzioneCondizionata ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo testo-vincolato\">$introduzioneCondizionata</div>"
								: '' )
											 . ( ! empty( $allegati ) ? "<div class=\"col-lg-12 col-sm-12\">$allegati</div>" : '' )
						           . "<?php \n"
						           . "echo \$form->field(\$model, 'domanda_$idD', ['options' => ['data' => ['domanda' => '{$idD}']]])->widget(Select2::className(), ['data' => ArrayHelper::map(SondaggiRispostePredefinite::find()->andWhere(['sondaggi_domande_id' => $idD])->select(['id', 'risposta'])->all(), 'id', 'risposta'),\n"
						           . "'language' => substr(Yii::\$app->language, 0, 2),\n"
						           . "'options' => ['style' => \$read ? 'pointer-events: none;' : null, 'placeholder' => AmosSondaggi::t('amossondaggi', 'Seleziona una o più risposte ...'), 'id' => 'select-domanda_$idD', 'multiple' => true, 'data' => ['question' => '{$idD}']],\n"
						           . "'pluginOptions' => [\n"
						           . "    'allowClear' => true\n"
						           . "],\n"
						           . "'toggleAllSettings' => [\n"
						           . "'selectLabel' => '<i class=\"glyphicon glyphicon-unchecked\"></i>' . \Yii::t('amosapp', 'Seleziona tutto'),\n"
						           . "'unselectLabel' => '<i class=\"glyphicon glyphicon-check\"></i>' . \Yii::t('amosapp', 'Deseleziona tutto'),\n"
						           . "],\n"
						           . "])->label(\$model->attributeLabels()[ 'domanda_$idD'] . '$tooltipHtml');\n"
						           . "?>\n"
						           . $js
						           . "</div>\n";
						break;
					case 'string':
						$campi[] = ( ! empty( $introduzione ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo\">$introduzione</div>" : '' )
						           . "<div class=\"col-lg-12 col-sm-12 sondaggi-content_domanda\" id=\"div-domanda_$idD\" data-question_id=\"$idD\" " . ( $conditions_data ? "data-conditions=\"$conditions_data\" " : "" ) . ">\n"
						           . ( ! empty( $introduzioneCondizionata ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo testo-vincolato\">$introduzioneCondizionata</div>" : '' )
											 . ( ! empty( $allegati ) ? "<div class=\"col-lg-12 col-sm-12\">$allegati</div>" : '' )
						           . "<?php \n"
						           . "echo \$form->field(\$model, 'domanda_$idD', ['options' => ['style' => \$read ? 'pointer-events: none;' : null, 'data' => ['domanda' => '{$idD}']]])->textInput(['maxlength' => true], $generalOptions)->label(\$model->attributeLabels()[ 'domanda_$idD'] . '$tooltipHtml');\n"
						           . "?>\n"
						           . $js
						           . "</div>\n";
						break;
					case 'text':
						$campi[] = ( ! empty( $introduzione ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo\">$introduzione</div>" : '' )
						           . "<div class=\"col-lg-12 col-sm-12 sondaggi-content_domanda\" id=\"div-domanda_$idD\" data-question_id=\"$idD\" " . ( $conditions_data ? "data-conditions=\"$conditions_data\" " : "" ) . ">\n"
						           . ( ! empty( $introduzioneCondizionata ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo testo-vincolato\">$introduzioneCondizionata</div>" : '' )
											 . ( ! empty( $allegati ) ? "<div class=\"col-lg-12 col-sm-12\">$allegati</div>" : '' )
						           . "<?php \n"
						           . "echo \$form->field(\$model, 'domanda_$idD', ['options' => ['style' => \$read ? 'pointer-events: none;' : null, 'data' => ['domanda' => '{$idD}']]])->textarea(['rows' => 6], $generalOptions)->label(\$model->attributeLabels()[ 'domanda_$idD'] . '$tooltipHtml');\n"
						           . "?>\n"
						           . $js
						           . "</div>\n";
						break;
					case 'file':
						$campi[] = ( ! empty( $introduzione ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo\">$introduzione</div>" : '' )
						           . "<div class=\"col-lg-12 col-sm-12 sondaggi-content_domanda\" id=\"div-domanda_$idD\" data-question_id=\"$idD\" " . ( $conditions_data ? "data-conditions=\"$conditions_data\" " : "" ) . ">"
						           . ( ! empty( $introduzioneCondizionata ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo testo-vincolato\">$introduzioneCondizionata</div>" : '' )
											 . ( ! empty( $allegati ) ? "<div class=\"col-lg-12 col-sm-12\">$allegati</div>" : '' )
						           . "<?php echo Html::tag('label', \$model->attributeLabels()['domanda_{$idD}']. '$tooltipHtml', ['class'=>'control-label']); ?>
	                    <?php
											if (!\$read) {
		                    echo
		                    FileInput::widget([
		                        'name' => 'domanda_{$idD}',
		                            'options' => ['data' => ['domanda' => $idD]],
		                          'pluginOptions' => [
		                            'showPreview' => false,
		                            'showCaption' => true,
		                            'showRemove' => !true,
		                            'showUpload' => false
		                        ]
		                    ]);\n
		                    echo '<p>'. AmosSondaggi::t('amossondaggi', 'Puoi inserire un solo allegato, aggiungendone un altro questo sostituirà il precedente.').'</p>';
										}

	                  if(!empty(\$file_$idD) && strpos(\yii\helpers\Url::current(), '/frontend/compila?id') === false){
	                      echo AttachmentsList::widget([
	                        'model' => \$file_$idD,
	                        'attribute' =>  'domanda_$idD',
													'viewDeleteBtn' => !\$read
	                      ]); \n
	                  } \n

	                  echo \$form->field(\$model, 'domanda_$idD')->hiddenInput(['class' => 'no-evaluate-input', 'value' => 'file'])->label(false);

	                  ?>
	                </div>
	                    ";
						break;
					case 'file-multiple':
						$campi[] = ( ! empty( $introduzione ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo\">$introduzione</div>" : '' )
						           . "<div class=\"col-xs-12 sondaggi-content_domanda\" id=\"div-domanda_$idD\" data-question_id=\"$idD\" " . ( $conditions_data ? "data-conditions=\"$conditions_data\" " : "" ) . ">"
						           . ( ! empty( $introduzioneCondizionata ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo testo-vincolato\">$introduzioneCondizionata</div>" : '' )
											 . ( ! empty( $allegati ) ? "<div class=\"col-lg-12 col-sm-12\">$allegati</div>" : '' )
						           . "<?php echo Html::tag('label', \$model->attributeLabels()['domanda_{$idD}']. '$tooltipHtml', ['class'=>'control-label']); ?>
	                    <?php
	                    if (!\$read) echo
	                    FileInput::widget([
	                        'name' => 'domanda_{$idD}[]',
	                          'pluginOptions' => [
	                            'showPreview' => false,
	                            'showCaption' => true,
	                            'showRemove' => true,
	                            'showUpload' => false
	                        ],
	                        'options' => ['multiple' => true, 'data' => ['domanda' => $idD]]
	                    ]);\n

	                  if(!empty(\$file_$idD)){
	                      echo AttachmentsList::widget([
	                        'model' => \$file_$idD,
	                        'attribute' =>  'domanda_$idD',
													'viewDeleteBtn' => !\$read
	                      ]); \n
	                  } \n

	                  echo \$form->field(\$model, 'domanda_$idD')->hiddenInput(['class' => 'no-evaluate-input', 'value' => 'file'])->label(false);

	                  ?>
	                </div>
	                    ";
						break;
					case 'date'://da implementare
						$campi[] = ( ! empty( $introduzione ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo\">$introduzione</div>" : '' )
						           . "<div class=\"col-lg-12 col-sm-12 sondaggi-content_domanda\" id=\"div-domanda_$idD\" data-question_id=\"$idD\" " . ( $conditions_data ? "data-conditions=\"$conditions_data\" " : "" ) . ">"
						           . ( ! empty( $introduzioneCondizionata ) ? "<div class=\"col-lg-12 col-sm-12 testo-introduttivo testo-vincolato\">$introduzioneCondizionata</div>" : '' )
											 . ( ! empty( $allegati ) ? "<div class=\"col-lg-12 col-sm-12\">$allegati</div>" : '' )
						           . "<?php echo \$form->field(\$model, 'domanda_$idD', ['options' => ['style' => \$read ? 'pointer-events: none;' : null, 'data' => ['domanda' => '{$idD}']]])->widget(DateControl::classname(), [ \n
	                          'options' => [ \n
	                                'id' => 'date_control_rispDomanda_$idD', \n
	                                'layout' => '{input} {picker} ' . (empty(\$model->domanda_$idD)? '' : '{remove}')], \n
	                                    'data' => ['question' => $idD] \n
	                        ]); ?> \n"
						           . "<script>"
						           . "$( document ).ready(function() {"
						           . "if($('#date_control_rispDomanda_$idD').val() == ''){
	                                $('#date_control_rispDomanda_$idD-disp-kvdate .input-group-addon.kv-date-remove').remove();
	                            } else {
	                                if($('#date_control_rispDomanda_$idD-disp-kvdate .input-group-addon.kv-date-remove').length == 0){
	                                    $('#date_control_rispDomanda_$idD-disp-kvdate').append('<span class=\"input-group-addon kv-date-remove\" title=\"Pulisci campo\"><i class=\"glyphicon glyphicon-remove\"></i></span>');
	                                    initDPRemove('date_control_rispDomanda_$idD-disp');
	                                }
	                            }"
						           . "$('#date_control_rispDomanda_$idD').change(function(){
	                            if($('#date_control_rispDomanda_$idD').val() == ''){
	                                $('#date_control_rispDomanda_$idD-disp-kvdate .input-group-addon.kv-date-remove').remove();
	                            } else {
	                                if($('#date_control_rispDomanda_$idD-disp-kvdate .input-group-addon.kv-date-remove').length == 0){
	                                    $('#date_control_rispDomanda_$idD-disp-kvdate').append('<span class=\"input-group-addon kv-date-remove\" title=\"Pulisci campo\"><i class=\"glyphicon glyphicon-remove\"></i></span>');
	                                    initDPRemove('date_control_rispDomanda_$idD-disp');
	                                }
	                            }
	                        });"
						           . "});"
						           . "</script>"
						           . "</div>";
						break;
					case 'img'://da implementare
						$campi[] = "";
						break;
					case 'img-multiple'://da implementare
						$campi[] = "";
						break;
					case 'custom'://da implementare
						$campi[] = "";
						break;
				}
			}
			if ( ! empty( $arrRispPreCond ) ) {
				$campi[] = $arrRispPreCond;
			}
		}

		return $campi;
	}

	/**
	 * Genera il codice javascript per le domande condizionate in modo da
	 * farle visualizzare o nascondere, il funzionamento è differenziato
	 * in base alla presenza di domande condizionate in questa pagina oppure
	 * in pagine precedenti
	 *
	 * @param integer $idD Id della domanda
	 *
	 * @return string Restituisce il codice javascript da inserire nella view
	 */
	public function generaJavascript( $idD, $utente = null ) {
		if ( ! $utente ) {
			$utente = \Yii::$app->getUser()->getId();
		}
		$tipoCondizioneLibera = null;
		$tipoCondizione       = null;
		$domanda              = SondaggiDomande::findOne( [ 'id' => $idD ] );
		$sondaggio            = $domanda->sondaggi_id;
		$tipo                 = SondaggiDomandeTipologie::findOne( $domanda->sondaggi_domande_tipologie_id )->html_type;
		$idPagina             = $domanda->getSondaggiDomandePagine()->one()['id'];
		$idPaginaCondizione   = $idPagina;
		$condizione           = SondaggiDomandeCondizionate::find()->andWhere( [ 'sondaggi_domande_id' => $domanda->id ] )->all();
		$condizioneLibera     = $domanda->domanda_condizionata_testo_libero;
		if ( ! empty( $condizioneLibera ) ) {
			$rispostaCondizioneLibera = SondaggiDomande::findOne( [ 'id' => $condizioneLibera ] );

			$idPaginaCondizioneLibera  = $rispostaCondizioneLibera->sondaggi_domande_pagine_id;
			$idDomandaCondizioneLibera = $rispostaCondizioneLibera->id;
			$tipoCondizioneLibera      = SondaggiDomandeTipologie::findOne( $rispostaCondizioneLibera->sondaggi_domande_tipologie_id )->html_type;
		}
		$rispostaCondizione = [];
		if ( ! empty( $condizione ) ) {
			foreach ( $condizione as $CD ) {
				$rispostaCD                                                                                         = SondaggiRispostePredefinite::findOne( [
					'id' => $CD->sondaggi_risposte_predefinite_id
				] );
				$idRispostaCondizione                                                                               = $rispostaCD->id;
				$idPaginaCondizione                                                                                 = $rispostaCD->getSondaggiDomande()->one()['sondaggi_domande_pagine_id'];
				$idDomandaCondizione                                                                                = $rispostaCD->getSondaggiDomande()->one()['id'];
				$tipoCondizione                                                                                     = SondaggiDomandeTipologie::findOne( $rispostaCD->getSondaggiDomande()->one()['sondaggi_domande_tipologie_id'] )->html_type;
				$rispostaCondizione[ $idPaginaCondizione ][ $tipoCondizione ][ $idDomandaCondizione ]['risposte'][] = $idRispostaCondizione;
			}
		}

		$javascript = "<script type=\"text/javascript\">\n "
		              . "$(document).ready(function () { ";

		if ( ! empty( $rispostaCondizione ) ) {
			foreach ( $rispostaCondizione as $paginaId => $condTipo ) {
				if ( $idPagina == $paginaId ) {
					foreach ( $condTipo as $tipo => $rispDom ) {
						$arrRisp = [];
						foreach ( $rispDom as $dom => $risposte ) {
							foreach ( $risposte['risposte'] as $risp ) {
								$arrRisp[] = $risp;
							}
						}
						$arrRisposte = '["' . implode( '","', $arrRisp ) . '"]';
					}
				}
				if ( ! empty( $condizione ) ) {
					$idDomConds = [];
					$idRisConds = [];
					foreach ( $condTipo as $k => $v ) {
						foreach ( $v as $k2 => $v2 ) {
							$idDomConds[] = $k2;
							foreach ( $v2['risposte'] as $v3 ) {
								$idRisConds[] = $v3;
							}
						}
					}

					$javascript .= "$('#div-domanda_$idD').hide();"
					               . "<?php \n"
					               . "if(!isset(\$utente)){\n"
					               . "\$utente = Yii::\$app->getUser()->getId();\n"
					               . "}\n"
					               . "\$sessione = SondaggiRisposteSessioni::findOne(['id' => \$idSessione]);\n"
					               . "\$risposteDate = SondaggiRisposte::find()->"
					               . "andWhere(['sondaggi_risposte_sessioni_id' => \$idSessione])"
					               . "->andWhere(['in', 'sondaggi_domande_id', [" . implode( ',', $idDomConds ) . "]])"
					               . "->andWhere(['in', 'sondaggi_risposte_predefinite_id', [" . implode( ',', $idRisConds ) . "]]);\n"
					               . "if(\$risposteDate->count() > 0){\n"
					               . "?>\n"
					               . "$(document).ready(function () {"
					               . "$('#div-domanda_$idD').show();"
					               . "});"
					               . "<?php\n"
					               . "} else {\n"
					               . "?>\n"
					               . "$(document).ready(function () {"
					               . "$('#div-domanda_$idD').hide();"
					               . "});"
					               . "<?php\n"
					               . "}\n"
					               . "?>\n";
				}
			}
		}

		$javascript .= "\n});\n</script>\n";

		return $javascript;
	}

	/**
	 * Prende il template
	 */
	public function getTemplate() {
		return null;
	}

	public function render( $template, $params = [] ) {
		$view                = new View();
		$params['generator'] = $this;

		return $view->renderFile( $this->getTemplatePath() . '/' . $template, $params, $this );
	}

	/**
	 * @return string the root path of the template files that are currently being used.
	 * @throws InvalidConfigException if [[template]] is invalid
	 */
	public function getTemplatePath() {
		if ( isset( $this->templates[ $this->template ] ) ) {
			return $this->templates[ $this->template ];
		} else {
			$this->templates['default'] = $this->defaultTemplate();

			return $this->templates['default'];
		}
	}

	/**
	 * Returns the root path to the default code template files.
	 * The default implementation will return the "templates" subdirectory of the
	 * directory containing the generator class file.
	 * @return string the root path to the default code template files.
	 */
	public function defaultTemplate() {
		$class = new ReflectionClass( $this );

		return dirname( $class->getFileName() ) . '/default';
	}

	/**
	 * Genera le label del model
	 *
	 * @param SondaggiDomandePagine $pagina L'active record della pagina del sondaggio
	 *
	 * @return array L'array con le labels del model
	 */
	public function generaLabel( SondaggiDomandePagine $pagina ) {
		$labels  = [];
		$domande = $pagina->getSondaggiDomandes();
		foreach ( $domande->all() as $Domanda ) {
			$labels[] = "'domanda_" . $Domanda['id'] . "' => AmosSondaggi::t('amossondaggi', '" . addslashes( $Domanda['domanda'] ) . "')";
		}

		return $labels;
	}

	/**
     * Genera le funzioni per validare i dati
     */
    public function generaRuleMinMax($domanda, $min = null, $max = null)
    {
        $minMaxRule = "";
        if (!empty($min) && !empty($max)) {
            $minMaxRule = "[['".$domanda."'], 'open20\\amos\\sondaggi\\validators\\Cardinality', 'min' => $min, 'max' => $max]";
        } else if (!empty($min) && empty($max)) {
            $minMaxRule = "[['".$domanda."'], 'open20\\amos\\sondaggi\\validators\\Cardinality', 'min' => $min]";
        } else if (empty($min) && !empty($max)) {
            $minMaxRule = "[['".$domanda."'], 'open20\\amos\\sondaggi\\validators\\Cardinality', 'min' => 0, 'max' => $max]";
        }

        return $minMaxRule;
    }

    /**
	 * Genera gli attributi del model.
	 *
	 * @param open20\amos\sondaggi\models\SondaggiDomandePagine $pagina
	 *
	 * @return array L'array degli attributi del model
	 */
	public function generateAttributi( SondaggiDomandePagine $pagina ) {
		$attributi = [];
		$domande   = $pagina->getSondaggiDomandes();
		foreach ( $domande->all() as $Domanda ) {
			$attributi[]     = "public \$domanda_" . $Domanda->id;
			$condizionata    = $Domanda->domanda_condizionata;
			$domCondizione   = $Domanda->getSondaggiRispostePredefinitesCondizionate()->one();
			$padreCondizione = null;
			if ( ! empty( $domCondizione ) ) {
				$padreCondizione = $domCondizione->sondaggiDomande;
			}
			if ( $condizionata > 0 && ! empty( $padreCondizione ) && $padreCondizione->abilita_ordinamento_risposte == 1 ) {
				if ( ! empty( $domCondizione ) ) {
					$idRispC = $domCondizione->id;
					$text    = "public \$ord_risp_{$padreCondizione->id}_{$idRispC}";
					if ( ! in_array( $text, $attributi ) ) {
						$attributi[] = $text;
					}
				}
			}
		}

		return $attributi;
	}

	/**
	 * Genera la funzione di salvataggio della pagina
	 *
	 * @param integer $pagina Id della pagina per cui generare la funzione di salvataggio
	 */
	public function generaSave( $pagina ) {
		$Pagina      = SondaggiDomandePagine::findOne( [ 'id' => $pagina ] );
		$domande     = $Pagina->getSondaggiDomandes();
		$salvataggio = [];
		foreach ( $domande->all() as $Domanda ) {
			$tipo            = SondaggiDomandeTipologie::findOne( [ 'id' => $Domanda['sondaggi_domande_tipologie_id'] ] )->html_type;
			$condizionata    = $Domanda->domanda_condizionata;
			$domCondizione   = $Domanda->getSondaggiRispostePredefinitesCondizionate()->one();
			$padreCondizione = null;
			if ( ! empty( $domCondizione ) ) {
				$padreCondizione = $domCondizione->sondaggiDomande;
			}
			$ordinamento = "";
			if ( $condizionata > 0 && ! empty( $padreCondizione ) && $padreCondizione->abilita_ordinamento_risposte == 1 ) {
				if ( ! empty( $domCondizione ) ) {
					$idRispC     = $domCondizione->id;
					$ordinamento = "\$risposta->ordinamento = \$this->ord_risp_{$padreCondizione->id}_{$idRispC};";
				}
			}
			if ( $tipo != 'file' && $tipo != 'file-multiple' ) {
				$salvataggio[] = "SondaggiRisposte::deleteAll(['sondaggi_domande_id' => {$Domanda['id']}, 'sondaggi_risposte_sessioni_id' => \$sessione]);\n";
			}
			$salvataggio[] = "if (!is_array(\$this->domanda_{$Domanda['id']}) && \$this->domanda_{$Domanda['id']} != NULL) {"
			                 . "\$this->domanda_{$Domanda['id']} = [\$this->domanda_{$Domanda['id']}];\n"
			                 . "}\n";
			if (!empty($Domanda->parent_id)) {
				switch($tipo) {
					case 'select':
						$salvataggio[] = "if (is_array(\$this->domanda_{$Domanda['id']})) {\n"
													 . "foreach (\$this->domanda_{$Domanda['id']} as \$key => \$Risposta) {\n"
													 . "\$risposta = new SondaggiRisposte();\n"
													 . "\$risposta->sondaggi_domande_id = {$Domanda['id']};\n"
													 . "\$risposta->column = \$key;\n"
													 . "\$risposta->sondaggi_risposte_sessioni_id = \$sessione;\n"
													 . "\$risposta->sondaggi_risposte_predefinite_id = \$Risposta;\n"
													 . "$ordinamento"
													 . "if(\$accesso){\n"
													 . "\$risposta->sondaggi_accessi_servizi_id = \$accesso;\n"
													 . "}\n"
													 . "\$risposta->save();\n"
													 . "}\n"
													 . "}\n";
							break;
						case 'string':
						case 'text':
							$salvataggio[] = "if (is_array(\$this->domanda_{$Domanda['id']})) {\n"
														 . "foreach (\$this->domanda_{$Domanda['id']} as \$key => \$Risposta) {\n"
														 . "\$risposta = new SondaggiRisposte();\n"
														 . "\$risposta->sondaggi_domande_id = {$Domanda['id']};\n"
														 . "\$risposta->column = \$key;\n"
														 . "\$risposta->sondaggi_risposte_sessioni_id = \$sessione;\n"
														 . "\$risposta->risposta_libera = \$Risposta;\n"
														 . "$ordinamento"
														 . "if(\$accesso){\n"
														 . "\$risposta->sondaggi_accessi_servizi_id = \$accesso;\n"
														 . "}\n"
														 . "\$risposta->save();\n"
														 . "}\n"
														 . "}\n";
							 break;
					case 'radio':
						$salvataggio[] = "if (is_array(\$this->domanda_{$Domanda['id']})) {\n"
														 . "foreach (\$this->domanda_{$Domanda['id']} as \$Risposta) {\n"
														 . "\$risposta = new SondaggiRisposte();\n"
														 . "\$risposta->sondaggi_domande_id = {$Domanda['id']};\n"
														 . "\$risposta->sondaggi_risposte_sessioni_id = \$sessione;\n"
														 . "\$risposta->sondaggi_risposte_predefinite_id = \$Risposta;\n"
														 . "$ordinamento"
														 . "if(\$accesso){\n"
														 . "\$risposta->sondaggi_accessi_servizi_id = \$accesso;\n"
														 . "}\n"
														 . "\$risposta->save();\n"
														 . "}\n"
														 . "}\n";
						break;
				}
			} else {
				switch ( $tipo ) {
					case 'checkbox':
						$salvataggio[] = "if (is_array(\$this->domanda_{$Domanda['id']})) {\n"
						                 . "foreach (\$this->domanda_{$Domanda['id']} as \$Risposta) {\n"
						                 . "\$risposta = new SondaggiRisposte();\n"
						                 . "\$risposta->sondaggi_domande_id = {$Domanda['id']};\n"
						                 . "\$risposta->sondaggi_risposte_sessioni_id = \$sessione;\n"
						                 . "\$risposta->sondaggi_risposte_predefinite_id = \$Risposta;\n"
						                 . "$ordinamento"
						                 . "if(\$accesso){\n"
						                 . "\$risposta->sondaggi_accessi_servizi_id = \$accesso;\n"
						                 . "}\n"
						                 . "\$risposta->save();\n"
						                 . "}\n"
						                 . "}\n";
						break;
					case 'radio':
						$salvataggio[] = "if (is_array(\$this->domanda_{$Domanda['id']})) {\n"
						                 . "foreach (\$this->domanda_{$Domanda['id']} as \$Risposta) {\n"
						                 . "\$risposta = new SondaggiRisposte();\n"
						                 . "\$risposta->sondaggi_domande_id = {$Domanda['id']};\n"
						                 . "\$risposta->sondaggi_risposte_sessioni_id = \$sessione;\n"
						                 . "\$risposta->sondaggi_risposte_predefinite_id = \$Risposta;\n"
						                 . "$ordinamento"
						                 . "if(\$accesso){\n"
						                 . "\$risposta->sondaggi_accessi_servizi_id = \$accesso;\n"
						                 . "}\n"
						                 . "\$risposta->save();\n"
						                 . "}\n"
						                 . "}\n";
						break;
					case 'select':
						$salvataggio[] = "if (is_array(\$this->domanda_{$Domanda['id']})) {\n"
						                 . "foreach (\$this->domanda_{$Domanda['id']} as \$Risposta) {\n"
						                 . "\$risposta = new SondaggiRisposte();\n"
						                 . "\$risposta->sondaggi_domande_id = {$Domanda['id']};\n"
						                 . "\$risposta->sondaggi_risposte_sessioni_id = \$sessione;\n"
						                 . "\$risposta->sondaggi_risposte_predefinite_id = \$Risposta;\n"
						                 . "$ordinamento"
						                 . "if(\$accesso){\n"
						                 . "\$risposta->sondaggi_accessi_servizi_id = \$accesso;\n"
						                 . "}\n"
						                 . "\$risposta->save();\n"
						                 . "}\n"
						                 . "}\n";
						break;
					case 'select-multiple':
						$salvataggio[] = "if (is_array(\$this->domanda_{$Domanda['id']})) {\n"
						                 . "foreach (\$this->domanda_{$Domanda['id']} as \$Risposta) {\n"
						                 . "\$risposta = new SondaggiRisposte();\n"
						                 . "\$risposta->sondaggi_domande_id = {$Domanda['id']};\n"
						                 . "\$risposta->sondaggi_risposte_sessioni_id = \$sessione;\n"
						                 . "\$risposta->sondaggi_risposte_predefinite_id = \$Risposta;\n"
						                 . "$ordinamento"
						                 . "if(\$accesso){\n"
						                 . "\$risposta->sondaggi_accessi_servizi_id = \$accesso;\n"
						                 . "}\n"
						                 . "\$risposta->save();\n"
						                 . "}\n"
						                 . "}\n";
						break;
					case 'string':
						$salvataggio[] = "if (is_array(\$this->domanda_{$Domanda['id']})) {\n"
						                 . "foreach (\$this->domanda_{$Domanda['id']} as \$Risposta) {\n"
						                 . "\$risposta = new SondaggiRisposte();\n"
						                 . "\$risposta->sondaggi_domande_id = {$Domanda['id']};\n"
						                 . "\$risposta->sondaggi_risposte_sessioni_id = \$sessione;\n"
						                 . "\$risposta->risposta_libera = \$Risposta;\n"
						                 . "$ordinamento"
						                 . "if(\$accesso){\n"
						                 . "\$risposta->sondaggi_accessi_servizi_id = \$accesso;\n"
						                 . "}\n"
						                 . "\$risposta->save();\n"
						                 . "}\n"
						                 . "}\n";
						break;
					case 'text':
						$salvataggio[] = "if (is_array(\$this->domanda_{$Domanda['id']})) {\n"
						                 . "foreach (\$this->domanda_{$Domanda['id']} as \$Risposta) {\n"
						                 . "\$risposta = new SondaggiRisposte();\n"
						                 . "\$risposta->sondaggi_domande_id = {$Domanda['id']};\n"
						                 . "\$risposta->sondaggi_risposte_sessioni_id = \$sessione;\n"
						                 . "\$risposta->risposta_libera = \$Risposta;\n"
						                 . "$ordinamento"
						                 . "if(\$accesso){\n"
						                 . "\$risposta->sondaggi_accessi_servizi_id = \$accesso;\n"
						                 . "}\n"
						                 . "\$risposta->save();\n"
						                 . "}\n"
						                 . "}\n";
						break;
					case 'date':
						$salvataggio[] = "if (is_array(\$this->domanda_{$Domanda['id']})) {\n"
						                 . "foreach (\$this->domanda_{$Domanda['id']} as \$Risposta) {\n"
						                 . "\$risposta = new SondaggiRisposte();\n"
						                 . "\$risposta->sondaggi_domande_id = {$Domanda['id']};\n"
						                 . "\$risposta->sondaggi_risposte_sessioni_id = \$sessione;\n"
						                 . "\$risposta->risposta_libera = \$Risposta;\n"
						                 . "$ordinamento"
						                 . "if(\$accesso){\n"
						                 . "\$risposta->sondaggi_accessi_servizi_id = \$accesso;\n"
						                 . "}\n"
						                 . "\$risposta->save();\n"
						                 . "}\n"
						                 . "}\n";
						break;
					case 'file':
						$salvataggio[] = "if (is_array(\$this->domanda_{$Domanda['id']})) {\n"
						                 . "foreach (\$this->domanda_{$Domanda['id']} as \$risposta) {\n"
						                 . "\$risposta = SondaggiRisposte::find()->andWhere(['sondaggi_domande_id' => {$Domanda['id']}, 'sondaggi_risposte_sessioni_id' => \$sessione])->one();\n"
						                 . "if(empty(\$risposta)){\$risposta = new SondaggiRisposte();}\n"
														 . "\$risposta->sondaggi_domande_id = {$Domanda['id']};\n"
														 . "\$risposta->sondaggi_risposte_sessioni_id = \$sessione;\n"
														 . "if(\$accesso){\n"
														 . "\$risposta->sondaggi_accessi_servizi_id = \$accesso;\n"
														 . "}\n"
														 . "\$risposta->save();\n"
						                 . "}\n"
						                 . "}\n";
						break;
					case 'file-multiple':
						$salvataggio[] = "if (is_array(\$this->domanda_{$Domanda['id']})) {\n"
						                 . "foreach (\$this->domanda_{$Domanda['id']} as \$Risposta) {\n"
						                 . "\$risposta = SondaggiRisposte::find()->andWhere(['sondaggi_domande_id' => {$Domanda['id']}, 'sondaggi_risposte_sessioni_id' => \$sessione])->one();\n"
						                 . "if(empty(\$risposta)){\$risposta = new SondaggiRisposte();}\n"
						                 . "\$files = UploadedFile::getInstancesByName(\"domanda_{$Domanda['id']}\");\n"
						                 . "foreach(\$files as \$file){\n"
						                 . "\$file->saveAs(\Yii::\$app->getModule('attachments')->getUserDirPath(\"domanda_{$Domanda['id']}\") . \$file->name);\n"
						                 . "\$dir = \Yii::\$app->getModule('attachments')->getUserDirPath(\"domanda_{$Domanda['id']}\");\n"
						                 . "\Yii::\$app->getModule('attachments')->attachFile(\$dir .\$file->name , \$risposta, \"domanda_{$Domanda['id']}\", true, true);\n"
						                 . "}\n"
						                 . "\$risposta->sondaggi_domande_id = {$Domanda['id']};\n"
						                 . "\$risposta->sondaggi_risposte_sessioni_id = \$sessione;\n"
						                 . "if(\$accesso){\n"
						                 . "\$risposta->sondaggi_accessi_servizi_id = \$accesso;\n"
						                 . "}\n"
						                 . "\$risposta->save();\n"
						                 . "\$attachfiles = File::find()->andWhere(['model' => get_class(new SondaggiRisposte()), 'attribute' => \"domanda_{$Domanda['id']}\"])->all();\n"
						                 . "foreach(\$attachfiles as \$attachfile){\n"
						                 . "\$attachfile->itemId = \$risposta->id;\n"
						                 . "\$attachfile->attribute = \"domanda_{$Domanda['id']}\";\n"
						                 . "\$attachfile->save(false);\n"
						                 . "} \n"
						                 . "\n"
						                 . "}\n"
						                 . "}\n";
						break;
					case 'img'://da implementare
						break;
					case 'img-multiple'://da implementare
						break;
					case 'custom'://da implementare
						break;
				}
			}
		}
		$salvataggio[] = "if(\$completato){\n"
		                 . "\$Sessione = SondaggiRisposteSessioni::findOne(['id' => \$sessione]);\n"
		                 . "\$Sessione->completato = 1;\n"
		                 . "\$Sessione->end_date = date('Y-m-d H:i:s');\n"
		                 . "\$Sessione->save();\n"
		                 . "}\n";

		return $salvataggio;
	}
}
