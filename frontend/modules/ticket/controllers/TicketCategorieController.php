<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\controllers\base
 * @category   CategoryName
 */

namespace frontend\modules\ticket\controllers;

use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\utility\TicketUtility;
use Yii;
use yii\helpers\Url;

/**
 * Class TicketCategorieController
 * TicketCategorieController implements the CRUD actions for TicketCategorie model.
 *
 * @property \open2\amos\ticket\models\TicketCategorie $model
 * @property \open2\amos\ticket\models\search\TicketCategorieSearch $modelSearch
 *
 * @package open2\amos\ticket\controllers\base
 */
class TicketCategorieController extends \open2\amos\ticket\controllers\TicketCategorieController
{



    /**
     * Lists all TicketCategorie models.
     * @param string|null $layout
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = null)
    {
      if (!\Yii::$app->user->isGuest) {
              $this->view->params['titleSection'] = AmosTicket::t('amosticket', 'Categorie');
              $this->view->params['labelLinkAll'] = AmosTicket::t('amosticket', 'Tutte le FAQ');
              $this->view->params['urlLinkAll']   = '/'.AmosTicket::getModuleName().'/ticket-faq/index';; //TODO
              $this->view->params['titleLinkAll'] = AmosTicket::t('amosticket', 'Visualizza la lista delle FAQ');
          }
        Url::remember();
		
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        $this->view->params['forceBreadcrumbs'][] = [
               'label' => AmosTicket::t('amosticket', 'FAQ'),
               'url' => '/ticket/ticket/index',
               'route' => '/ticket/ticket/index',
        ];
        $this->view->params['forceBreadcrumbs'][] = ['label' => AmosTicket::t('amosticket', 'Categorie')];
        return parent::actionIndex();
    }
    /**
     * @return array
     */
    public static function getManageLinks()
    {
        return TicketUtility::getManageLink();
    }
}
