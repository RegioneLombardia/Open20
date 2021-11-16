<?php

use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\seo\models\SeoData;
use yii\db\Migration;

/**
 * Class m181024_125423_populate_seo_data */
class m181024_125423_populate_seo_data extends Migration {

    public function safeUp() {
        $totsave = 0;
        $totnotsave = 0;
        try {
            foreach (DiscussioniTopic::find()
                    ->orderBy(['id' => SORT_ASC])
                    ->all() as $discussione) {

                $seoData = SeoData::findOne([
                            'classname' => $discussione->className(),
                            'content_id' => $discussione->id
                ]);

                if (is_null($seoData)) {
                    $seoData = new SeoData();
                    $pars = [];
                    $pars = ['pretty_url' => $discussione->slug,
                        'meta_title' => '',
                        'meta_description' => '',
                        'meta_keywords' => '',
                        'og_title' => '',
                        'og_description' => '',
                        'og_type' => '',
                        'unavailable_after_date' => '',
                        'meta_robots' => '',
                        'meta_googlebot' => ''
                    ];
                    $seoData->aggiornaSeoData($discussione, $pars);
                    $totsave++;
                } else {
                    $totnotsave++;
                }
            }
            \yii\helpers\Console::stdout("Records Seo_data save: $totsave\n\n");
            \yii\helpers\Console::stdout("Records Seo_data already present: $totnotsave\n\n");
        } catch (Exception $ex) {
            \yii\helpers\Console::stdout("Module Seo not configured " . $ex->getMessage());
        }
        return true;
    }

    public function safeDown() {
        $totdel = 0;
        try {
            foreach (DiscussioniTopic::find()
                    ->orderBy(['id' => SORT_ASC])
                    ->all() as $discussione) {

                $where = " classname LIKE '" . addslashes(addslashes($discussione->className())) . "' AND content_id = " . $discussione->id;
                $this->delete(SeoData::tableName(), $where);

                $totdel++;
            }
            \yii\helpers\Console::stdout("Records Seo_data delete: $totdel\n\n");
        } catch (Exception $ex) {
            \yii\helpers\Console::stdout("Module Seo not configured " . $ex->getMessage());
        }
        return true;
    }

}
