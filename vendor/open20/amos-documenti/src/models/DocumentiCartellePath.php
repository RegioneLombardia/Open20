<?php

namespace open20\amos\documenti\models;

use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\core\record\Record;
use yii\helpers\StringHelper;
/**
 * This is the model class for table "admin_storage_folder".
 *
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property int $timestamp_create
 * @property int $is_deleted
 *
 */
final class DocumentiCartellePath extends Record 
{
    
    const PATH_SEPARATOR = '/';
    const MAX_CHARACTER = 15;
    const START_LEVEL = 1;

    /**
     * @inheritdoc
     */
    public function init() {
        // call parent
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'documenti_cartelle_path';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_doc_folder', 'level'], 'required'],
            [['id_doc_folder', 'level','id_folder'], 'integer'],
        ];
    }
    
    /**
     * funzione che crea il path dei documenti già inseriti a db
     *
     * @param [type] $doc
     * @param [type] $level
     * @param [type] $parent_array
     * @return void
     */
    public static function generatePath($doc,$level,$parent_array){
        if ($doc) {
	        $parent_array[$doc->id] = $doc->parent_id;
            if($doc->parent_id){
                $documento = Documenti::findOne($doc->parent_id);
                $level++;
                return self::generatePath($documento,$level,$parent_array);
            }
        }

        return $parent_array;
    }
	
    /**
     *
     * @param [type] $result
     * @param [type] $doc_id
     * @return void
     */
    public static function savePath($result,$doc_id){
        $level = count($result);
        try{
            foreach ($result as $key => $value){
                $documentoPath = new DocumentiCartellePath();
                $documentoPath->id_doc_folder = $doc_id;
                $documentoPath->level = $level;
                $documentoPath->id_folder = $value;
                $documentoPath->save();
                $level--;
            }
        } catch (\Exception $e){
            Yii::$app->getSession()->addFlash(
                'danger',
                AmosDocumenti::tHtml(
                    'amosdocumenti',
                    'Si &egrave; verificato un errore durante il salvataggio'
                )
            );
        }
    }
    
    /**
     *
     * @param [type] $document
     * @return void
     */
    public static function getPath($document){
        $result = self::generatePath($document,self::START_LEVEL,[]);
        $stringa = self::PATH_SEPARATOR;
        $documenti = AmosDocumenti::Instance();
        ksort($result);
        foreach ($result as $key => $value){
            if($value){
                $path = Documenti::findOne($value);
                if ($documenti->truncateGetPath) {
                    $stringa .= StringHelper::truncate(
                            $path->titolo,
                            self::MAX_CHARACTER,
                            '...',
                            null,
                            false
                        )
                        . self::PATH_SEPARATOR;
                } else {
                    $stringa .= $path->titolo . self::PATH_SEPARATOR;
                }
            }
        }
        return $stringa;
    }

}
