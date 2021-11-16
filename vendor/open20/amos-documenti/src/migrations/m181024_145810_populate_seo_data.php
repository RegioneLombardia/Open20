<?php

use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\seo\models\SeoData;
use yii\db\Migration;

/**
 * Class m181024_145810_populate_seo_data */
class m181024_145810_populate_seo_data extends Migration {

    public function safeUp() {
        $totsave = 0;
        $totnotsave = 0;
        try {
            /** @var Documenti $documentiModel */
            $documentiModel = AmosDocumenti::instance()->createModel('Documenti');
            foreach ($documentiModel::find()
                    ->orderBy(['id' => SORT_ASC])
                    ->all() as $document) {
                /** @var Documenti $document */
                $seoData = SeoData::findOne([
                            'classname' => $document->className(),
                            'content_id' => $document->id
                ]);

                if (is_null($seoData)) {
                    $seoData = new SeoData();
                    $pars = [];
                    $pars = ['pretty_url' => yii\helpers\Inflector::slug($document->titolo),
                        'meta_title' => '',
                        'meta_description' => '',
                        'meta_keywords' => '',
                        'og_title' => '',
                        'og_description' => '',
                        'og_type' => '',
                        'unavailable_after_date' => '',
                        'meta_robots' => '',
                        'meta_googlebot' => ''];
                    $seoData->aggiornaSeoData($document, $pars);
                    $totsave++;
                } else {
                    $totnotsave++;
                }
            }
            \yii\helpers\Console::stdout("Records Seo_data Documenti save: $totsave\n\n");
            \yii\helpers\Console::stdout("Records Seo_data Documenti already present: $totnotsave\n\n");
        } catch (Exception $ex) {
            \yii\helpers\Console::stdout("Error transaction Documenti " . $ex->getMessage());
        }
        return true;
    }

    public function safeDown() {
        $totdel = 0;
        try {
            /** @var Documenti $documentiModel */
            $documentiModel = AmosDocumenti::instance()->createModel('Documenti');
            foreach ($documentiModel::find()
                    ->orderBy(['id' => SORT_ASC])
                    ->all() as $document) {
                /** @var Documenti $document */
                $where = " classname LIKE '" . addslashes(addslashes($document->className())) . "' AND content_id = " . $document->id;
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
