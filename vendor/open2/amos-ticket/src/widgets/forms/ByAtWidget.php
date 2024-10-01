<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\widgets\forms
 * @category   CategoryName
 */

namespace open2\amos\ticket\widgets\forms;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open2\amos\ticket\AmosTicket;
use yii\base\Widget;
use yii\db\ActiveRecord;

/**
 * Class ByAtWidget
 *
 * The widget requires only one parameter: the model
 *
 * @package open2\amos\ticket\widgets\forms
 */
class ByAtWidget extends Widget
{
    const CREATED_TYPE = 'created';
    const UPDATED_TYPE = 'updated';

    /**
     * @var string $layout Widget layout
     */
    public $layout = "{beginContainerSection}\n{byAtSection}\n{endContainerSection}";

    /**
     * @var array $containerOptions Default to []
     */
    public $containerOptions = [];

    /**
     * @var array $createdSectionOptions Default to []
     */
    public $createdSectionOptions = [];

    /**
     * @var array $sectionOptions Default to []
     */
    public $sectionOptions = [];

    /**
     * @var array $updatedSectionOptions Default to []
     */
    public $updatedSectionOptions = [];

    /**
     * @string $byAt
     */
    private $byAt = null;

    /**
     * @string $byAtLabel
     */
    private $byAtLabel = null;

    /**
     * @var \open20\amos\core\record\Record $model
     */
    private $model = null;

    /**
     * @var bool $isTooltip If true convert the widget into an info tooltip
     */
    public $isTooltip = false;

    /**
     * @var array $tooltipParams Used for passing params to tooltip begin container
     */
    private $tooltipParams = [];

    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        if (is_null($this->model)) {
            throw new \Exception('CreatedUpdatedWidget: model required');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $fieldAt = $this->byAt . '_at';
        if (!$this->model[$fieldAt]) {
            return '';
        }
        if ($this->isTooltip) {
            $tooltipContent = $this->renderSections("{createdSection}\n{updatedSection}");
            $this->tooltipParams['title'] = $tooltipContent;
            $content = $this->renderSections("{beginContainerSection}\n{endContainerSection}");
        } else {
            $content = $this->renderSections($this->layout);
        }
        return $content;
    }

    /**
     * @param $subject
     * @return null|string|string[]
     */
    private function renderSections($subject, $params = [])
    {
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);
            return $content === false ? $matches[0] : $content;
        }, $subject);
        return $content;
    }

    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{summary}`, `{items}`.
     * @return string|boolean the rendering result of the section, or false if the named section is not supported.
     */
    protected function renderSection($name, $params = [])
    {
        switch ($name) {
            case '{beginContainerSection}':
                return $this->renderBeginContainerSection();
            case '{createdSection}':
                return $this->renderCreatedSection();
            case '{updatedSection}':
                return $this->renderUpdatedSection();
            case '{byAtSection}':
                return $this->renderByAtSection();
            case '{endContainerSection}':
                return $this->renderEndContainerSection();
            default:
                return false;
        }
    }

    /**
     * This method render the beginning part of the container.
     * @return string
     */
    protected function renderBeginContainerSection()
    {
        if ($this->isTooltip) {
            $sectionContent = Html::beginTag('span', [
                    'title' => $this->tooltipParams['title'],
                    'data-toggle' => 'tooltip',
                    'data-html' => 'true',
                    'class' => 'amos-tooltip',
                ]) . AmosIcons::show('info-circle', [], 'dash');
        } else {
            $sectionContent = Html::beginTag('div', $this->containerOptions);
        }
        return $sectionContent;
    }

    /**
     * This method render the created section.
     * @return string
     */
    protected function renderByAtSection()
    {
        $fieldAt = $this->byAt . '_at';
        //$fieldBy = $this->byAt.'_by';
        $createdAt = $this->getModel()[$fieldAt];
        $sectionContent = Html::beginTag('p', $this->sectionOptions);
        $sectionContent .= Html::beginTag('strong');
        $sectionContent .= AmosTicket::t('amosticket', $this->byAtLabel) . ': ';
        $sectionContent .= Html::endTag('strong');
        $sectionContent .= AmosTicket::t('amosticket', 'da') . ' ' . $this->retrieveUserNameAndSurname('byAt') . ' ';
        $sectionContent .= AmosTicket::t('amosticket', 'il') . ' ' . \Yii::$app->getFormatter()->asDate($createdAt) . ' ';
        $sectionContent .= AmosTicket::t('amosticket', 'alle ore') . ' ' . \Yii::$app->getFormatter()->asTime($createdAt);
        $sectionContent .= Html::endTag('p');
        return $sectionContent;
    }

    /**
     * This method render the created section.
     * @return string
     */
    protected function renderCreatedSection()
    {
        $fieldAt = 'created_at';
        $createdAt = $this->getModel()[$fieldAt];
        $sectionContent = Html::beginTag('p', $this->createdSectionOptions);
        $sectionContent .= Html::beginTag('strong');
        $sectionContent .= BaseAmosModule::tHtml('amoscore', 'Creazioneee') . ': ';
        $sectionContent .= Html::endTag('strong');
        $sectionContent .= BaseAmosModule::tHtml('amoscore', 'da') . ' ' . $this->retrieveUserNameAndSurname(self::CREATED_TYPE) . ' ';
        $sectionContent .= BaseAmosModule::tHtml('amoscore', 'il') . ' ' . \Yii::$app->getFormatter()->asDate($createdAt) . ' ';
        $sectionContent .= BaseAmosModule::tHtml('amoscore', 'alle ore') . ' ' . \Yii::$app->getFormatter()->asTime($createdAt);
        $sectionContent .= Html::endTag('p');
        return $sectionContent;
    }

    /**
     * This method render the updated section.
     * @return string
     */
    protected function renderUpdatedSection()
    {
        $updatedAt = $this->getModel()->updated_at;
        $sectionContent = Html::beginTag('p', $this->updatedSectionOptions);
        $sectionContent .= Html::beginTag('strong');
        $sectionContent .= BaseAmosModule::tHtml('amoscore', 'Ultima modifica') . ': ';
        $sectionContent .= Html::endTag('strong');
        $sectionContent .= BaseAmosModule::tHtml('amoscore', 'da') . ' ' . $this->retrieveUserNameAndSurname(self::UPDATED_TYPE) . ' ';
        $sectionContent .= BaseAmosModule::tHtml('amoscore', 'il') . ' ' . \Yii::$app->getFormatter()->asDate($updatedAt) . ' ';
        $sectionContent .= BaseAmosModule::tHtml('amoscore', 'alle ore') . ' ' . \Yii::$app->getFormatter()->asTime($updatedAt);
        $sectionContent .= Html::endTag('p');
        return $sectionContent;
    }

    /**
     * This method render the end part of the container.
     * @return string
     */
    protected function renderEndContainerSection()
    {
        $endTag = ($this->isTooltip ? 'span' : 'div');
        $sectionContent = Html::endTag($endTag);
        return $sectionContent;
    }

    /**
     * This method creates a string that contains the name and surname of the user whose ID is contained in the parameter.
     * @param string $type Field of the model that contains a user id (eg. created_by, updated_by, ...)
     * @return string
     */
    private function retrieveUserNameAndSurname($type = '')
    {
        /** @var UserProfile $userProfile */
        $userProfile = null;
        switch ($type) {
            case self::CREATED_TYPE:
                $userProfile = $this->model->getCreatedUserProfile()->one();
                break;
            case self::UPDATED_TYPE:
                $userProfile = $this->model->getUpdatedUserProfile()->one();
                break;
            default:
                $userProfile = $this->getByUserProfile()->one();
                break;
        }
        $nameSurname = (!is_null($userProfile) ? $userProfile->getNomeCognome() : '-');
        return $nameSurname;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getByUserProfile()
    {
        $adminInstalled = \Yii::$app->getModule('admin');
        $model = $this->getModel();
        if ($adminInstalled) {
            $fieldBy = $this->byAt . '_by';
            $modelClass = \open20\amos\admin\AmosAdmin::instance()->createModel('UserProfile');
            return $model->hasOne($modelClass::className(), ['user_id' => $fieldBy]);
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @return ActiveRecord
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param ActiveRecord $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return ActiveRecord
     */
    public function getByAt()
    {
        return $this->byAt;
    }

    /**
     * @param String $byAt
     */
    public function setByAt($byAt)
    {
        $this->byAt = $byAt;
    }

    /**
     * @return ActiveRecord
     */
    public function getByAtLabel()
    {
        return $this->byAtLabel;
    }

    /**
     * @param String $byAtLabel
     */
    public function setByAtLabel($byAtLabel)
    {
        $this->byAtLabel = $byAtLabel;
    }
}
