<?php



namespace app\modules\seo\frontend\behaviors;

use luya\web\View;
use Yii;
use yii\base\Behavior;
use yii\base\Event;


class LuyaSeoBehavior extends Behavior {
    
    private $sender;

    public function events() {
        return [
            View::EVENT_BEFORE_RENDER => 'attachSeoMetadata'
        ];
    }

    public function attachSeoMetadata(Event $event) {
        $this->sender = $event->sender;
           
        $ogTitle = Yii::$app->menu->current->getProperty('ogTitle');
        $ogDescription = Yii::$app->menu->current->getProperty('ogDescription');
        $ogType = Yii::$app->menu->current->getProperty('ogType');
        $ogImageUrl = Yii::$app->menu->current->getProperty('ogImageUrl');
        $metaRobots = self::stringifyArrayProperty(Yii::$app->menu->current->getProperty('metaRobots'));
        $metaGooglebot = self::stringifyArrayProperty(Yii::$app->menu->current->getProperty('metaGooglebot'));
        


        if ($ogTitle) {
            $this->sender->registerMetaTag(['name' => 'og:title', 'content' => $ogTitle], 'fbTitle');
            $this->sender->registerMetaTag(['property' => 'og:title', 'content' => $ogTitle], 'fbTitle');
        }
        if ($ogDescription) {
            $this->sender->registerMetaTag(['name' => 'og:description', 'content' => $ogDescription], 'fbDescription');
            $this->sender->registerMetaTag(['property' => 'og:description', 'content' => $ogDescription], 'fbDescription');
        }
        if ($ogType) {
            $this->sender->registerMetaTag(['name' => 'og:type', 'content' => $ogType], 'ogType');
            $this->sender->registerMetaTag(['property' => 'og:type', 'content' => $ogType], 'ogType');
        }
        if ($ogImageUrl) {
            $this->sender->registerMetaTag(['name' => 'og:image', 'content' => $ogImageUrl], 'ogImage');
            $this->sender->registerMetaTag(['property' => 'og:image', 'content' => $ogImageUrl], 'ogImage');
        }
        if ($metaRobots) {
            $this->sender->registerMetaTag(['name' => 'robots', 'content' => $metaRobots], 'metaRobots');
            $this->sender->registerMetaTag(['property' => 'robots', 'content' => $metaRobots], 'metaRobots');
        }
        if ($metaGooglebot) {
            $this->sender->registerMetaTag(['name' => 'googlebot', 'content' => $metaGooglebot],'metaGoogleBot');
            $this->sender->registerMetaTag(['property' => 'googlebot', 'content' => $metaGooglebot],'metaGoogleBot');
        }
    }
    
    private static function stringifyArrayProperty($arrayProperty){
        if(!$arrayProperty){
            return null;
        }
        
        $properties = json_decode($arrayProperty->value);
        $values = [];
        foreach($properties as $property){
            $values[] = $property->value;
        }
        return count($values) > 0 ? implode(',',$values) : null;
    }

}
