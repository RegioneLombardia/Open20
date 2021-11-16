<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\community\models\Community;
use lajax\translatemanager\models\LanguageSource;
use lajax\translatemanager\models\LanguageTranslate;
/**
 * Class m170303_095333_init_widget_to_validate_communities
 */
class m170303_095333_init_widget_to_validate_communities extends \yii\db\Migration
{
    /**
     * @var array $widgets This array contains all widgets configurations.
     */
    protected $widget;

    const TABLE = '{{%language_source}}';

    private $tableName;
    private $translate = [
        [
            'category' => 'amoscommunity',
            'value' => 'View the list of communities to validate',
            'translation' => 'Visualizza la lista delle community da validare'
        ],
        [
            'category' => 'amoscommunity',
            'value' => 'To validate',
            'translation' => 'Da validare'
        ],
        [
            'category' => 'amoscommunity',
            'value' => 'Communities to validate',
            'translation' => 'Community da validare'
        ],
        [
            'category' => 'amoscommunity',
            'value' => 'Community status will be set to editing in progress. Keep the community visible while editing it?',
            'translation' => 'Lo stato della community sarà impostato a modifica in corso. Si desidera che la community rimanga visibile durante la modifica?'
        ],
    ];

    /**
     *
     * @param string $category
     * @param string $value
     * @param string $translation
     */
    private function saveLanguage($category, $value, $translation)
    {
        $languageSource = LanguageSource::find()->where(['category' => $category, 'message' => $value])->one();
        if ($languageSource == null) {
            $languageSource = new LanguageSource();
            $languageSource->category = $category;
            $languageSource->message = $value;
            $languageSource->insert();
        }
        /** @var LanguageTranslate $languageTranslate */
        $languageTranslate = LanguageTranslate::find()->where(['id' => $languageSource->id, 'language' => 'it-IT'])->one();
        if ($languageTranslate == null) {
            $languageTranslate = new LanguageTranslate();
            $languageTranslate->id = $languageSource->id;
            $languageTranslate->language = 'it-IT';
            $languageTranslate->translation = $translation;
            $languageTranslate->insert();
        } else {
            $languageTranslate->translation = $translation;
            $languageTranslate->save();
        }
    }

    /**
     */
    public function init()
    {
        $this->initWidgetsConfs();
        $this->tableName = $this->db->getSchema()->getRawTableName(self::TABLE);
        parent::init();
    }


    protected function initWidgetsConfs()
    {
        $this->widget = [
            'classname' => 'open20\amos\community\widgets\icons\WidgetIconToValidateCommunities',
            'type' => AmosWidgets::TYPE_ICON,
            'module' => Community::tableName(),

            'status' => AmosWidgets::STATUS_ENABLED,
            'child_of' => 'open20\amos\community\widgets\icons\WidgetIconCommunityDashboard',
            'update' => true
        ];
    }

    /**
     * @return bool
     */
    public function safeUp()
    {
        $allOk = true;
        $ok = $this->insertNewWidget($this->widget);
        if (!$ok) {
            $allOk = false;
        }
        if(Yii::$app->getModule('translatemanager')) {
            try {
                foreach ($this->translate as $lang) {
                    $this->saveLanguage($lang['category'], $lang['value'], $lang['translation']);
                }
            } catch (Exception $e) {
                echo "Errore durante la modifica della tabella " . $this->tableName . "\n";
                echo $e->getMessage() . "\n";
                $allOk = false;
            }
        }

        return $allOk;
    }

    /**
     * Metodo per l'inserimento della configurazione per un nuovo widget.
     *
     * @param array $newWidget Array chiave => valore contenente i dati da inserire nella tabella AmosWidgets.
     * @return boolean true
     */
    protected function insertNewWidget($newWidget)
    {
        $msg = "Widget " . $newWidget['classname'];
        if ($this->checkWidgetExist($newWidget['classname'])) {
            $msg .= " esistente. ";
            if (isset($newWidget['update'])) {
                $msg .= "Aggiorno...";
                $ok = $this->updateExistentWidget($newWidget);
                $msg .= ($ok ? 'OK' : 'ERRORE');
            } else {
                $msg .= "Skippo...";
            }
            echo $msg . "\n";
        } else {
            $newWidgetClean = $newWidget;
            if (isset($newWidget['update'])) {
                $newWidgetClean = [];
                foreach ($newWidget as $fieldName => $fieldValue) {
                    if (strcmp($fieldName, 'update') != 0) {
                        $newWidgetClean[$fieldName] = $fieldValue;
                    }
                }
            }
            $this->insert(AmosWidgets::tableName(), $newWidgetClean);
            echo $msg . " aggiunto.\n";
        }

        return true;
    }

    /**
     * This method check if a widget exists in the AmosWidgets table. Return true if the widget exists.
     *
     * @param string $classname The widget complete class name.
     *
     * @return bool Returns true if the widget exists. False otherwise.
     */
    protected function checkWidgetExist($classname)
    {
        /** @var \yii\db\ActiveQuery $query */
        $query = AmosWidgets::find()->andWhere(['classname' => $this->widget['classname'] ]);
        $oldWidgets = $query->count("classname");
        return ($oldWidgets > 0);
    }

    protected function updateExistentWidget($widgetData)
    {
        $widgetToUpdate = AmosWidgets::findOne($widgetData['classname']);
        if (is_null($widgetToUpdate)) {
            $widgetToUpdate = new AmosWidgets();
        }
        foreach ($widgetData as $fieldName => $fieldValue) {
            if (strcmp($fieldName, 'update') != 0) {
                $widgetToUpdate->$fieldName = $fieldValue;
            }
        }
        $widgetToUpdate->detachBehavior('auditTrailBehavior');
        return $widgetToUpdate->save(false);
    }

    /**
     * @return bool
     */
    public function safeDown()
    {
        $this->execute("SET foreign_key_checks = 0;");
        $where = ['classname' => $this->widget['classname'] ];
        $this->delete(AmosWidgets::tableName(), $where);
        $this->execute("SET foreign_key_checks = 1;");

        return true;
    }
}
