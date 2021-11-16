<?php

namespace app\modules\cmsapi\cart\controllers;

use app\modules\cmsapi\cart\positions\RoomPosition;
use luya\web\Controller;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class CmsapiController extends Controller
{

    /**
     * @return \yii\base\Module|null
     */
    public function getModule()
    {
        return \open20\amos\shop\Module::getInstance();
    }

    public function actionAdd()
    {
        //Set right format
        Yii::$app->response->format = Response::FORMAT_JSON;

        $cart = $this->getModule()->getCart();

        /**
         * @var $post array
         */
        $post = Yii::$app->request->post();

        if (isset($post['room']) && isset($post['quantity'])) {
            $roomArray = (array) json_decode($post['room']);

            $position = new RoomPosition();
            $position->load($roomArray);

            $cart->update($position, $post['quantity']);

            return ['status' => 'success'];
        }

        return ['error' => Yii::t('Dati mancanti per l\'inserimento nel carrello')];
    }

    public function actionGet()
    {
        return $this->getModule()->getCart();
    }
}
