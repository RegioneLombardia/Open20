<?php

namespace open20\amos\comments\models\base;

use open20\amos\comments\AmosComments;
use Yii;

/**
 * This is the base-model class for table "comment_blacklist".
 *
 * @property integer $id
 * @property string $word
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  CommentBlacklist extends \open20\amos\core\record\Record
{
    /**
     * @var bool $isSearch
     */
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment_blacklist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['word'], 'required'],
            [['word'], 'string', 'max' => 255],
            [['word'], 'unique'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'word' => AmosComments::t('amoscomments', 'Termine'),
            'created_at' => AmosComments::t('amoscomments', 'Data di creazione'),
            'updated_at' => AmosComments::t('amoscomments', 'Ultima modifica'),
            'deleted_at' => AmosComments::t('amoscomments', 'Data di eliminazione'),
            'created_by' => AmosComments::t('amoscomments', 'Creato da'),
            'updated_by' => AmosComments::t('amoscomments', 'Modificato da'),
            'deleted_by' => AmosComments::t('amoscomments', 'Eliminato da'),
        ];
    }
}
