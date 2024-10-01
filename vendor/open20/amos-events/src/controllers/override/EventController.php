<?php

namespace open20\amos\events\controllers\override;

class EventController extends \open20\amos\events\controllers\EventController 
{
    

    /**
     * Lists all Event models.
     * @param string|null $layout
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = NULL)
    {
        return $this->redirect(['/events/event/all-events']);
        
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->searchCalendarView(Yii::$app->request->getQueryParams()));
        $this->setListViewsParams();
        $this->setTitleAndBreadcrumbs(AmosEvents::t('amosevents', 'Events'));
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        return parent::actionIndex();
    }



}
