<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\forms
 * @category   CategoryName
 */

namespace open20\amos\community\widgets;

use open20\amos\community\models\Community;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\news\models\News;
use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use open20\amos\core\widget\WidgetAbstract;

/**
 * Class PublishedContentsWidget
 * @package open20\amos\core\forms
 */
class CommunityPublishedContentsWidget extends Widget
{

    /**
     * @var string classname of the model of the listed objects
     */
    public $modelCommunity;
    /**
     * @var string classname of the model of the listed objects
     */
    public $modelContent;

    /**
     * @var string label for the model of the listed objects
     */
    public $modelLabel;


    public $frameworkIcons = AmosIcons::DASH;

    public $iconsContents = [
        'News' => 'feed',
        'Event' => 'feed',
        'DiscussioniTopic' => 'comment',
        'Documenti' => 'file-text-o',
        'PartnershipProfiles' => 'lightbulb-o',
        'Risultati' => 'gears',
        'ShowcaseProjectProposal' => 'gears',
        'Sondaggi' => 'sondaggi'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {

        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS){
            $this->frameworkIcons = AmosIcons::IC;

            $this->iconsContents = [
                'News' => 'news',
                'Event' => 'eventi',
                'DiscussioniTopic' => 'disc',
                'Documenti' => 'fatture',
                'PartnershipProfiles' => 'propostecollaborazione',
                'Result' => 'risultati',
                'Initiative' => 'iniziative',
                'Sondaggi' => 'sondaggi',
            ];
        }

        /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
        $object = Yii::createObject($this->modelContent);
        $this->modelLabel = $object->getGrammar()->getModelLabel();

    }


    /**
     * @inheritdoc
     */
    public function run()
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        $count = 0;

        /** @var \open20\amos\cwh\query\CwhActiveQuery $cwhActiveQuery */
        $cwhActiveQuery = null;

        if (isset($moduleCwh)) {
            $query = new \open20\amos\cwh\query\CwhActiveQuery($this->modelContent);
            $query->filterByPublicationNetwork(Community::getCwhConfigId(), $this->modelCommunity->id);
            $count = $query->count();

            if(empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] != WidgetAbstract::ENGINE_ROWS){
                $count = '(' . $count . ')';
            }
        }
        $model = new \ReflectionClass($this->modelContent);
        $shortclassname = $model->getShortName();

        $icons = isset($this->iconsContents[$shortclassname]) ? AmosIcons::show($this->iconsContents[$shortclassname], [], $this->frameworkIcons) : '';
        return  Html::tag('div',
                $icons .
                Html::tag('span', $count, ['class' => 'counter']) .
                Html::tag('span', $this->modelLabel, ['class' => 'model-label']),
                ['class' => 'content-widget-item']);
    }


}