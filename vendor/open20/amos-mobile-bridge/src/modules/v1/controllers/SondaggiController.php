<?php

namespace open20\amos\mobile\bridge\modules\v1\controllers;

use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\SondaggiParser;
use open20\amos\sondaggi\models\search\SondaggiSearch;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiRisposte;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use Exception;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use yii\rest\Controller;

class SondaggiController extends DefaultController {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviours = parent::behaviors();

        return ArrayHelper::merge($behaviours, [
                    'verbFilter' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'sondaggi-list' => ['get'],
                            'sondaggi-detail' => ['get'],
                        ],
                    ],
        ]);
    }

    /**
     * 
     * @return type
     * @throws Exception
     */
    private function loadCwh() {
        $cwh = null;

        $cwh = Yii::$app->getModule('cwh');
        if (is_null($cwh)) {
            throw new Exception('CWH not present');
        }

        return $cwh;
    }

    /**
     * 
     * @return string
     */
    public function actionSondaggiList($offset = null, $limit = null, $from_date = null, $to_date = null) {
        $list = [];
        try {
            $params = [];
            $search = new SondaggiSearch();
            if (!is_null($offset)) {
                $params['offest'] = $offset;
            }
            if (!is_null($limit)) {
                $params['limit'] = $limit;
            }

            $cwh = $this->loadCwh();
            $cwh->resetCwhScopeInSession();
            $dataProvider = $search->searchOwnInterest($params);
            $query = $dataProvider->query;
            //$query->joinWith('sondaggiRisposteSessionis')->andwhere([SondaggiRisposteSessioni::tableName().'.completato' => null]);
            $dataProvider->query = $query;
            $listModel = $dataProvider->getModels();
            foreach ($listModel as $model) {
                $list[] = SondaggiParser::parseItem($model);
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $list;
    }

    /**
     * 
     * @param integer $event_id
     * @return string
     */
    public function actionSondaggiDetail($sondaggi_id) {
        $ditail = [];

        try {
            $model = Sondaggi::findOne(['id' => $sondaggi_id]);
            $ditail = SondaggiParser::parseItem($model);
            $pages = $model->sondaggiDomandePagines;
            $ditail['pages'] = [];
            foreach ($pages as $page) {
                $thePage = SondaggiParser::parsePageItem($page);
                $thePage['questions'] = [];
                $questions = $page->sondaggiDomandes;
                foreach ($questions as $question) {
                    $theQuestion = SondaggiParser::parseQuestionItem($question);
                    $reponses = $question->sondaggiRispostePredefinites;
                    $theQuestion['options'] = [];
                    foreach ($reponses as $response) {
                        $theResponse = SondaggiParser::parseDefaultResponseItem($response);
                        $theQuestion['options'][] = $theResponse;
                    }
                    $thePage['questions'][] = $theQuestion;
                }

                $ditail['pages'][] = $thePage;
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $ditail;
    }

    /**
     * 
     * @return type
     */
    public function actionAddSondaggio() {

        $result = ['success' => true];

        try {
            //Request params
            $bodyParams = \Yii::$app->getRequest()->getBodyParams();
            //Record interested
            if (!empty($bodyParams['id'])) {
                $record = Sondaggi::findOne(['id' => $bodyParams['id']]);

                $q = SondaggiRisposteSessioni::find();
                $q->andWhere([SondaggiRisposteSessioni::tableName() . '.sondaggi_id' => $record->id])
                        ->andWhere([SondaggiRisposteSessioni::tableName() . '.user_id' => \Yii::$app->user->id])
                        ->andWhere([SondaggiRisposteSessioni::tableName() . '.completato' => 0]);
                $session = $q->one();
                if (is_null($session)) {
                    $session = new SondaggiRisposteSessioni();
                    $session->begin_date = date('Y-m-d H:i:s');
                    $session->end_date = null;
                    $session->sondaggi_id = $record->id;
                    $session->user_id = \Yii::$app->user->id;
                    if (!$session->save()) {
                        return $this->echoError($session);
                    }
                }
                $lastPage = $this->lastPageId($record);

                if (isset($bodyParams['pages'])) {
                    foreach ($bodyParams['pages'] as $page) {
                        if (isset($page['questions'])) {
                            foreach ($page['questions'] as $question) {
                                $response = null;
                                if ($question['type'] == 'MULTISELECT' || $question['type'] == 'SELECT') {
                                    foreach ($question['options'] as $option) {
                                        if ($option['checked'] == true) {
                                            $response = $this->addSondaggiRisposte($question, $session, $question['value'], $option['id']);
                                        }
                                    }
                                } else {
                                    $response = $this->addSondaggiRisposte($question, $session, $question['value'], null);
                                }

                                if ($response->hasErrors()) {
                                    return $this->echoError($response);
                                }
                            }
                        }
                        if ($page['id'] == $lastPage) {
                            $session->end_date = date('Y-m-d H:i:s');
                            $session->completato = 1;
                            $session->save();
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $result;
    }

    /**
     * 
     * @return array
     */
    private function echoError($record) {
        $errors = $record->getErrors();
        $firstError = reset($errors);

        return [
            'error' => true,
            'message' => reset($firstError),
            'more' => $errors
        ];
    }

    /**
     * 
     * @param type $question
     * @param type $session
     * @return type
     */
    private function addSondaggiRisposte($question, $session, $value_text, $value_predefinite) {
        $response = new SondaggiRisposte();
        $response->sondaggi_domande_id = $question['id'];
        $response->sondaggi_risposte_sessioni_id = $session->id;
        $response->risposta_libera = $value_text;
        $response->sondaggi_risposte_predefinite_id = $value_predefinite;
        $response->save();
        return $response;
    }

    /**
     * 
     * @param type $sondaggio
     * @return type
     */
    private function lastPageId($sondaggio) {
        return $sondaggio->getSondaggiDomandePagines()->max('id');
    }

}
