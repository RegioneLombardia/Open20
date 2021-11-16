<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation
 * @category   CategoryName
 */

namespace open20\amos\translation\components;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\db\Schema;

class Generator extends \yii\gii\generators\model\Generator
{
    /**
     * @var bool whether to overwrite (extended) model classes, will be always created, if file does not exist
     */
    public $generateModelClass = false;

    /**
     * @var null string for the table prefix, which is ignored in generated class name
     */
    public $tablePrefix = null;

    /**
     * @var array key-value pairs for mapping a table-name to class-name, eg. 'prefix_FOObar' => 'FooBar'
     */
    public $tableNameMap = [];
    protected $classNames2;
    public $classnameTarget;

    /**
     * @var array for new rules
     */
    public $newRules = [];

    /**
     * @var array per relazioni aggiuntive
     */
    public $otherRelations = [];
    public $workflow;

    /**
     *
     * @var array per le colonne rappresentative
     */
    public $representingColumn;

    /**
     * 
     */
    public $pluginName;

    /**
     *
     * @var string $generatedClassName 
     */
    public $generatedClassName;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Amos Model';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates an ActiveRecord class and base class for the specified database table.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
            [['generateModelClass'], 'boolean'],
            [['tablePrefix'], 'safe'],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(), [
            'generateModelClass' => 'Generate Model Class',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(
            parent::hints(),
            [
            'generateModelClass' => 'This indicates whether the generator should generate the model class, this should usually be done only once. The model-base class is always generated.',
            'tablePrefix' => 'Custom table prefix, eg <code>app_</code>.<br/><b>Note!</b> overrides <code>yii\db\Connection</code> prefix!',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['model.php'];
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        try {
            $files     = [];
            $relations = $this->generateRelations();

            $db = $this->getDbConnection();
            foreach ($this->getTableNames() as $tableName) {

                $className   = $this->generateClassName($tableName);
                $relations   = $this->createRelations($relations, $tableName, $className);
                $tableSchema = $db->getTableSchema($tableName);
                $params      = [
                    'tableName' => $tableName,
                    'className' => $className,
                    'tableSchema' => $tableSchema,
                    'labels' => $this->generateLabels($tableSchema),
                    'rules' => $this->generateRules($tableSchema),
                    'relations' => isset($relations[$tableName]) ? $relations[$tableName] : [],
                    'otherRelations' => $this->otherRelations,
                    'classnameTarget' => $this->classnameTarget,
                    'newRules' => $this->newRules,
                    'ns' => $this->ns,
                    'representingColumn' => $this->representingColumn,
                    'pluginName' => $this->pluginName
                ];

                $files[] = new CodeFile(
                    Yii::getAlias('@'.str_replace('\\', '/', $this->ns)).'/'.$className.'.php',
                    $this->render('model.php', $params)
                );
            }
            return $files;
        } catch (\Exception $e) {
            pr($e->getMessage());
            die;
            return null;
        }
    }

    protected function createRelations($relation, $tableName, $modelClass)
    {
        $ret        = [];
        $len        = strlen($modelClass);
        $modelClass = substr($modelClass, 0, ($len - 11));
        if (isset($relation[$tableName][$modelClass][0])) {
            $posA                                 = strpos($relation[$tableName][$modelClass][0], 'Translation::');
            $posB                                 = $posA + 11;
            $pre                                  = substr($relation[$tableName][$modelClass][0], 0, $posA);
            $post                                 = substr($relation[$tableName][$modelClass][0], $posB);
            $relation[$tableName][$modelClass][0] = $pre.$post;
            $ret                                  = $relation;
        }
        return $ret;
    }

    /**
     * Generates a class name from the specified table name.
     *
     * @param string $tableName the table name (which may contain schema prefix)
     *
     * @return string the generated class name
     */
    protected function generateClassName($tableName, $useSchemaName = null)
    {

        #Yii::trace("Generating class name for '{$tableName}'...", __METHOD__);
        if (isset($this->classNames2[$tableName])) {
            #Yii::trace("Using '{$this->classNames2[$tableName]}' for '{$tableName}' from classNames2.", __METHOD__);
            return $this->classNames2[$tableName];
        }

        if (isset($this->tableNameMap[$tableName])) {
            Yii::trace("Converted '{$tableName}' from tableNameMap.", __METHOD__);
            return $this->classNames2[$tableName] = $this->tableNameMap[$tableName];
        }

        if (($pos = strrpos($tableName, '.')) !== false) {
            $tableName = substr($tableName, $pos + 1);
        }

        $db         = $this->getDbConnection();
        $patterns   = [];
        $patterns[] = "/^{$this->tablePrefix}(.*?)$/";
        $patterns[] = "/^(.*?){$this->tablePrefix}$/";
        $patterns[] = "/^{$db->tablePrefix}(.*?)$/";
        $patterns[] = "/^(.*?){$db->tablePrefix}$/";

        if (strpos($this->tableName, '*') !== false) {
            $pattern = $this->tableName;
            if (($pos     = strrpos($pattern, '.')) !== false) {
                $pattern = substr($pattern, $pos + 1);
            }
            $patterns[] = '/^'.str_replace('*', '(\w+)', $pattern).'$/';
        }

        $className = $tableName;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $tableName, $matches)) {
                $className = $matches[1];
                Yii::trace("Mapping '{$tableName}' to '{$className}' from pattern '{$pattern}'.", __METHOD__);
                break;
            }
        }

        $returnName = Inflector::id2camel($className, '_');
        Yii::trace("Converted '{$tableName}' to '{$returnName}'.", __METHOD__);
        if (empty($this->generatedClassName)) {
            return $this->classNames2[$tableName] = $returnName;
        } else {
            return $this->classNames2[$tableName] = $this->generatedClassName;
        }
    }

    protected function generateRelations()
    {
        try {
            $relations = parent::generateRelations();

            // inject namespace
            $ns = "\\{$this->classnameTarget}";
            foreach ($relations AS $model => $relInfo) {
                foreach ($relInfo AS $relName => $relData) {
                    $relations[$model][$relName][0] = preg_replace(
                        '/(has[A-Za-z0-9]+\()([a-zA-Z0-9]+::)/', '$1__NS__$2', $relations[$model][$relName][0]
                    );
                    $relations[$model][$relName][0] = str_replace('__NS__', $ns, $relations[$model][$relName][0]);
                }
            }
            return $relations;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generates validation rules for the specified table.
     * @param \yii\db\TableSchema $table the table schema
     * @return array the generated validation rules
     */
    public function generateRules($table)
    {
        $types   = [];
        $lengths = [];
        foreach ($table->columns as $column) {
            if ($column->autoIncrement) {
                continue;
            }
            if (!$column->allowNull && $column->defaultValue === null) {
                $types['required'][] = $column->name;
            }
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case 'double': // Schema::TYPE_DOUBLE, which is available since Yii 2.0.3
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][]  = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $types['safe'][]    = $column->name;
                    break;
                default: // strings
                    if ($column->size > 0) {
                        $lengths[$column->size][] = $column->name;
                    } else {
                        $types['string'][] = $column->name;
                    }
            }
        }
        $rules = [];
        foreach ($types as $type => $columns) {
            $rules[] = "[['".implode("', '", $columns)."'], '$type']";
        }
        foreach ($lengths as $length => $columns) {
            $rules[] = "[['".implode("', '", $columns)."'], 'string', 'max' => $length]";
        }

        $db = $this->getDbConnection();

        // Unique indexes rules
        try {
            $uniqueIndexes = $db->getSchema()->findUniqueIndexes($table);
            foreach ($uniqueIndexes as $uniqueColumns) {
                // Avoid validating auto incremental columns
                if (!$this->isColumnAutoIncremental($table, $uniqueColumns)) {
                    $attributesCount = count($uniqueColumns);

                    if ($attributesCount === 1) {
                        $rules[] = "[['".$uniqueColumns[0]."'], 'unique']";
                    } elseif ($attributesCount > 1) {
                        $labels      = array_intersect_key($this->generateLabels($table), array_flip($uniqueColumns));
                        $lastLabel   = array_pop($labels);
                        $columnsList = implode("', '", $uniqueColumns);
                        $rules[]     = "[['$columnsList'], 'unique', 'targetAttribute' => ['$columnsList'], 'message' => 'The combination of ".implode(', ',
                                $labels)." and $lastLabel has already been taken.']";
                    }
                }
            }
        } catch (NotSupportedException $e) {
            // doesn't support unique indexes information...do nothing
        }

        // Exist rules for foreign keys
        foreach ($table->foreignKeys as $refs) {
            $refTable       = $refs[0];
            $refTableSchema = $db->getTableSchema($refTable);
            if ($refTableSchema === null) {
                // Foreign key could point to non-existing table: https://github.com/yiisoft/yii2-gii/issues/34
                continue;
            }
            $refClassName     = $this->generateClassName($refTable);
            $pos              = strlen($refClassName) - 11;
            $refClassName     = substr($refClassName, 0, $pos);
            unset($refs[0]);
            $attributes       = implode("', '", array_keys($refs));
            $targetAttributes = [];
            foreach ($refs as $key => $value) {
                $targetAttributes[] = "'$key' => '$value'";
            }

            $targetAttributes = implode(', ', $targetAttributes);
            $rules[]          = "[['$attributes'], 'exist', 'skipOnError' => true, 'targetClass' => ".(isset($this->classnameTarget)
                    ? "\\".$this->classnameTarget : "")."$refClassName::className(), 'targetAttribute' => [$targetAttributes]]";
        }

        return $rules;
    }
}