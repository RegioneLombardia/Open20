<?php
namespace app\modules\cms\error;

use Yii;

class ErrorHandler extends \yii\web\ErrorHandler {
   
    public $transferException = false;
    
   protected function renderException($exception)
   {
        $redirectPage = 500;
        if ($exception->statusCode == 404) {
            $redirectPage = 404;
        }
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
            // reset parameters of response to avoid interference with partially created response data
            // in case the error occurred while sending the response.
            $response->isSent = false;
            $response->stream = null;
            $response->data = null;
            $response->content = null;
        } else {
            $response = new Response();
        }
        $result = \Yii::$app->controller->redirect(["/$redirectPage"]);
        
        $response->send();
   }
     
    
}
