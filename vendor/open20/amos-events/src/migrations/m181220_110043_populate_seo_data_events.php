<?php

use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\seo\models\SeoData;
use yii\db\Migration;

/**
 * Class m181220_110043_populate_seo_data_events */
class m181220_110043_populate_seo_data_events extends Migration {

    public function safeUp() {
        $eventtotsave = 0;
        $eventtotnotsave = 0;
        try {
            /** @var Event $eventModel */
            $eventModel = AmosEvents::instance()->createModel('Event');
            foreach ($eventModel::find()
                    ->orderBy(['id' => SORT_ASC])
                    ->all() as $event) {
                /** @var Event $event */

                $seoData = SeoData::findOne([
                            'classname' => $event->className(),
                            'content_id' => $event->id
                ]);

                if (is_null($seoData)) {
                    $seoData = new SeoData();
                    $pars = [];
                    $pars = ['pretty_url' => $event->title,
                        'meta_title' => '',
                        'meta_description' => '',
                        'meta_keywords' => '',
                        'og_title' => '',
                        'og_description' => '',
                        'og_type' => '',
                        'unavailable_after_date' => '',
                        'meta_robots' => '',
                        'meta_googlebot' => ''];
                    $seoData->aggiornaSeoData($event, $pars);
                    $eventtotsave++;
                } else {
                    $eventtotnotsave++;
                }
            }
            \yii\helpers\Console::stdout("Records Seo_data News save: $eventtotsave\n\n");
            \yii\helpers\Console::stdout("Records Seo_data News already present: $eventtotnotsave\n\n");
        } catch (Exception $ex) {
            \yii\helpers\Console::stdout("Error transaction News " . $ex->getMessage());
        }
        return true;
    }

    public function safeDown() {
        $eventtotdel = 0;
        try {
            /** @var Event $eventModel */
            $eventModel = AmosEvents::instance()->createModel('Event');
            foreach ($eventModel::find()
                    ->orderBy(['id' => SORT_ASC])
                    ->all() as $event) {
                /** @var Event $event */

                $where = " classname LIKE '" . addslashes(addslashes($event->className())) . "' AND content_id = " . $event->id;
                $this->delete(SeoData::tableName(), $where);

                $eventtotdel++;
            }
            \yii\helpers\Console::stdout("Records Seo_data delete: $eventtotdel\n\n");
        } catch (Exception $ex) {
            \yii\helpers\Console::stdout("Module Seo not configured " . $ex->getMessage());
        }
        return true;           
    }
}
