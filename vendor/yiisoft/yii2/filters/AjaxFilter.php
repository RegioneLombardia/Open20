<?php
/**
 */

namespace yii\filters;

use Yii;
use yii\base\ActionFilter;
use yii\web\BadRequestHttpException;
use yii\web\Request;

/**
 * AjaxFilter allow to limit access only for ajax requests.
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => 'yii\filters\AjaxFilter',
 *             'only' => ['index']
 *         ],
 *     ];
 * }
 * ```
 *
 * @since 2.0.13
 */
class AjaxFilter extends ActionFilter
{
    /**
     * @var string the message to be displayed when request isn't ajax
     */
    public $errorMessage = 'Request must be XMLHttpRequest.';
    /**
     * @var Request the current request. If not set, the `request` application component will be used.
     */
    public $request;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->request === null) {
            $this->request = Yii::$app->getRequest();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if ($this->request->getIsAjax()) {
            return true;
        }

        throw new BadRequestHttpException($this->errorMessage);
    }
}
