<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\behaviors;

use creocoder\taggable\TaggableBehavior as YiiTaggable;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Class TaggableInterestingBehavior
 *
 * @package open20\amos\cwh\behaviors
 */
class TaggableInterestingBehavior extends YiiTaggable
{
    /**
     * @var string separator for tags
     */
    public $tagValuesSeparatorAttribute = ',';
    public $tagValueNameAttribute = '';

    /**
     * @var bool $forceTagsSave
     */
    public $forceTagsSave = false;

    /**
     * @var string[]
     */
    private $_tagValues = [];
    private $pivot;

    public function __construct()
    {
        parent::__construct();
        $this->tagValueAttribute = 'id';
        $this->pivot = CwhTagOwnerInterestMm::className();
        $this->tagFrequencyAttribute = false;
    }

    /**
     * Returns tags.
     * @param boolean|null $asArray
     * @param int|null $content
     * @param int|null $root_id
     * @return array|mixed|string|void
     */
    public function getInterestTagValues($asArray = null, $content = null, $root_id = null)
    {
        if (!$content || !$root_id) {
            return;
        } else {
            if (!array_key_exists($content, $this->_tagValues)) {
                $this->_tagValues[$content][$root_id] = [];
            }

            if (!array_key_exists($root_id, $this->_tagValues[$content])) {
                $this->_tagValues[$content][$root_id] = [];
            }
        }

        if (!$this->owner->getIsNewRecord() && $content && $root_id && empty($this->_tagValues[$content][$root_id])) {
            $pivot = $this->pivot;
            $list = $pivot::find()->andWhere([
                    'classname' => get_class($this->owner),
                    'interest_classname' => $content,
                    'record_id' => $this->owner->getPrimaryKey(),
                    'root_id' => $root_id,
                ]
            );

            $this->_tagValues[$content][$root_id] = ArrayHelper::merge($this->_tagValues[$content][$root_id] ? explode(',', $this->_tagValues[$content][$root_id]) : [], []);
            foreach ($list->all() as $record) {
                if ($content && $root_id && $record->tag_id) {
                    $this->_tagValues[$content][$root_id][] = $record->tag_id;
                }
            }
        }

        if ($asArray === null) {
            $asArray = $this->tagValuesAsArray;
        }

        if ($asArray) {
            return ($this->_tagValues[$content][$root_id] === null ? [] : $this->_tagValues[$content][$root_id]);
        } else {
            $tagString = '';
            if (!empty($this->_tagValues[$content][$root_id])) {
                if (is_array($this->_tagValues[$content][$root_id])) {
                    $tagString = implode($this->tagValuesSeparatorAttribute, $this->_tagValues[$content][$root_id]);
                } else {
                    $tagString = $this->_tagValues[$content][$root_id];
                }
            }
            return $tagString;
        }
    }

    /**
     * @return array
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_FIND => 'eventFind',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'eventSave',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'eventSave',
            BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'eventBeforeValidate',
        ];
    }

    public function eventFind()
    {

    }

    public function eventBeforeValidate()
    {

    }

    /**
     *
     */
    public function eventSave()
    {
        $moduleTag = Yii::$app->getModule('tag');
        if (isset($moduleTag)) {
            if ($this->forceTagsSave) {
                $this->_tagValues = $this->owner->interestTagValues;
            } else {
                if (!isset($_POST[$this->owner->formName()]) || !isset($_POST[$this->owner->formName()]['interestTagValues'])) {
                    return;
                }
                $this->_tagValues = $_POST[$this->owner->formName()]['interestTagValues'];
            }

//        $flagSimpleChoise =false;
//        if (isset($this->_tagValues['simple-choice']) && !empty($this->_tagValues['simple-choice'])) {
//            $flagSimpleChoise = true;
//        }

            if ($this->_tagValues !== null) {
                $class = \open20\amos\tag\models\Tag::className();
                $rows = [];

                $user = Yii::$app->get('user', false);
                $timestamp = new Expression('NOW()');
                $userId = $user && !$user->isGuest ? $user->id : null;

                foreach ($this->_tagValues as $content => $tags) {
                    if (!$this->owner->getIsNewRecord()) {
                        $this->beforeDeleteContent($content);
                    }
                    foreach ($tags as $root => $id_tags) {
                        $array_tags = $this->filterTagValues($id_tags);
                        foreach ($array_tags as $id_tag) {
                            if ($this->tagFrequencyAttribute !== false) {
                                /** @var ActiveRecord $tag */
                                $tag = $class::findOne([$this->tagValueAttribute => $id_tag]);

                                if ($tag === null) {
                                    $tag = new $class();
                                    $tag->setAttribute($this->tagValueAttribute, $id_tag);
                                }
                                $frequency = $tag->getAttribute($this->tagFrequencyAttribute);
                                $tag->setAttribute($this->tagFrequencyAttribute, ++$frequency);
                                $tag->save();
                            }
                            $rows[] = [
                                get_class($this->owner),
                                $content,
                                $this->owner->getPrimaryKey(),
                                $id_tag,
                                $root,
                                $timestamp,
                                $timestamp,
                                $userId,
                                $userId
                            ];
                        }
                    }
                }

                if (!empty($rows)) {
                    $pivot = $this->pivot;
                    $this->owner->getDb()
                        ->createCommand()
                        ->batchInsert($pivot::tableName(), [
                            'classname',
                            'interest_classname',
                            'record_id',
                            'tag_id',
                            'root_id',
                            'created_at',
                            'updated_at',
                            'created_by',
                            'updated_by'
                        ], $rows)
                        ->execute();
                }
            }
        }
    }

    /**
     * @param $content
     */
    public function beforeDeleteContent($content)
    {
        $moduleTag = Yii::$app->getModule('tag');
        if (isset($moduleTag)) {
            $pivot = $this->pivot;
            if ($this->tagFrequencyAttribute !== false) {
                $list = $pivot::findAll([
                    'classname' => get_class($this->owner),
                    'record_id' => $this->owner->getPrimaryKey()
                ]);
                $class = \open20\amos\tag\models\Tag::className();
                foreach ($list as $record) {
                    $tag = $class::findOne([$this->tagValueAttribute => $record->tag_id]);
                    if ($tag) {
                        $frequency = $tag->getAttribute($this->tagFrequencyAttribute);
                        $tag->setAttribute($this->tagFrequencyAttribute, --$frequency);

                        $tag->save();
                    }
                }
            }
            $user = Yii::$app->get('user', false);
            $timestamp = new Expression('NOW()');
            $userId = $user && !$user->isGuest ? $user->id : null;
            $connection = $this->owner->getDb();
            $connection->createCommand()->update($pivot::tableName(),
                ['deleted_at' => $timestamp, 'deleted_by' => $userId], [
                    'interest_classname' => $content,
                    'classname' => get_class($this->owner),
                    'record_id' => $this->owner->getPrimaryKey()
                ])->execute();
        }
    }

    /**
     * Filters tags.
     * @param string|string[] $values
     * @return string[]
     */
    public function filterTagValues($values)
    {
        return array_unique(preg_split(
            '/\s*,\s*/u',
            preg_replace('/\s+/u', ' ', is_array($values) ? implode(',', $values) : $values),
            -1,
            PREG_SPLIT_NO_EMPTY
        ));
    }
}
