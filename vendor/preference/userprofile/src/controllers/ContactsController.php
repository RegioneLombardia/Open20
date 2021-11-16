<?php

namespace preference\userprofile\controllers;

use open20\amos\core\controllers\BackendController;
use open20\amos\core\utilities\Email;
use preference\userprofile\models\FormContatti;
use preference\userprofile\utility\EmailUtility;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\HtmlPurifier;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    preference\userprofile\controllers
 * @category   CategoryName
 */

/**
 * Description of BaseController
 *
 */
class ContactsController extends BackendController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['settings'],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['PC_REGISTERD_USER'],
                    ],
                    [
                        'actions' => [
                            'contacts',
                            'quick-registration',
                        ],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],


                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'contacts' => ['post', 'get'],
                    'quick-registration' => ['post', 'get'],
                ],
            ],
        ];
    }

    public function init()
    {
        // Layout Bootstrap Italia
        $this->setUpLayout('@vendor/open20/design/src/views/layouts/bi-main-layout');
        //$this->view->params['customClassMainContent'] = 'container';

        parent::init();
    }

    /**
     *
     * @return void
     */
    public function actionContacts()
    {
        $toEmail = isset(Yii::$app->params['assistance']['email'])? Yii::$app->params['assistance']['email']: null;
        $model = new FormContatti();

        if (is_null($toEmail)) {
            Yii::$app->session->addFlash('warning', 'Email di assistenza non impostata');
        }
//        VarDumper::dump($toEmail, $depth = 10, $highlight = true);

        $ok = null;
        if (Yii::$app->request->post() && !is_null($toEmail)) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                // invio email
//                $tipoMessaggio = isset($model->elencoTipiMessaggio[$model->tipoMessaggio]) ? $model->elencoTipiMessaggio[$model->tipoMessaggio] : '';

                $corpoMail = 'Nome: ' . $model->nome . ' <br>
                Cognome: ' . $model->cognome . ' <br>
                E-mail: ' . $model->email . ' <br>
                Messaggio:<hr>
                '. str_replace("\r", "<br />", HtmlPurifier::process($model->messaggio)) .'<hr>';

                $mailModule = Yii::$app->getModule("email");
                $mailModule->defaultLayout = "layout_without_header_and_footer";
                $ok  = Email::sendMail(
                    Yii::$app->params['supportEmail'],
                    $toEmail,
                    'Preference Centre Regione Lombardia - Segnalazione malfunzionamento',
                    $corpoMail
                );

                if ($ok) {
//                    Yii::$app->session->addFlash('success', 'Gentile utente, la tua segnalazione è stata inviata. Ti risponderemo quanto prima');
                    $model = new FormContatti();
                }

            }

            // VarDumper::dump( $model->errors, $depth = 10, $highlight = true);
        }
//        VarDumper::dump( $model->errors, $depth = 10, $highlight = true);

        return $this->render("contacts", [
            'model' => $model,
            'ok' => $ok
        ]);
    }

    public function actionQuickRegistration($email = 'stefano.pavani+RAPIDA@open20.it')
    {
        // $email = 'michele.zucchini+RAPIDA@open20.it';

        EmailUtility::sendUserMailQuickRegistration($email, 'Lombardia Informa: registrazione rapida', $email, 'Password2020');
    }
}
