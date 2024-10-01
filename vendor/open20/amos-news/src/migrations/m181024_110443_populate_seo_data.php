<?php

use open20\amos\news\models\News;
use open20\amos\seo\models\SeoData;
use yii\db\Migration;

/**
 * Class m181024_110443_populate_seo_data */
class m181024_110443_populate_seo_data extends Migration {

    public function safeUp() {
        $newstotsave = 0;
        $newstotnotsave = 0;
        try {
            foreach (News::find()
                    ->orderBy(['id' => SORT_ASC])
                    ->all() as $news) {

                $seoData = SeoData::findOne([
                            'classname' => $news->className(),
                            'content_id' => $news->id
                ]);

                if (is_null($seoData)) {
                    $seoData = new SeoData();
                    $pars = [];
                    $pars = ['pretty_url' => $news->slug,
                        'meta_title' => '',
                        'meta_description' => '',
                        'meta_keywords' => '',
                        'og_title' => '',
                        'og_description' => '',
                        'og_type' => '',
                        'unavailable_after_date' => '',
                        'meta_robots' => '',
                        'meta_googlebot' => ''];
                    $seoData->aggiornaSeoData($news, $pars);
                    $newstotsave++;
                } else {
                    $newstotnotsave++;
                }
            }
            \yii\helpers\Console::stdout("Records Seo_data News save: $newstotsave\n\n");
            \yii\helpers\Console::stdout("Records Seo_data News already present: $newstotnotsave\n\n");
        } catch (Exception $ex) {
            \yii\helpers\Console::stdout("Error transaction News " . $ex->getMessage());
        }
        return true;
    }

    public function safeDown() {
        $newstotdel = 0;
        try {
            foreach (News::find()
                    ->orderBy(['id' => SORT_ASC])
                    ->all() as $news) {

                $where = " classname LIKE '" . addslashes(addslashes($news->className())) . "' AND content_id = " . $news->id;
                $this->delete(SeoData::tableName(), $where);

                $newstotdel++;
            }
            \yii\helpers\Console::stdout("Records Seo_data delete: $newstotdel\n\n");
        } catch (Exception $ex) {
            \yii\helpers\Console::stdout("Module Seo not configured " . $ex->getMessage());
        }
        return true;           
    }

}
