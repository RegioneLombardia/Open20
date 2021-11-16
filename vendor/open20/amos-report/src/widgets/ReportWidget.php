<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-report
 * @category   Widget
 */

namespace open20\amos\report\widgets;

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\report\AmosReport;
use yii\base\Widget;

/**
 * Class ReportWidget
 * @package open20\amos\report\widgets
 */
class ReportWidget extends Widget
{
    /**
     * @var string $modelClassName the current model processed className
     */
    public $modelClassName = '';

    /**
     * @var null
     */
    public $context_id = null;

    /**
     * @var string
     */
    public $layout = "{reportButton}";
    /**
     * @var array
     */
    public $renderSections = [];

    /**
     * @var
     */
    public $options = [];

    /**
     * @var string
     */
    public $title = '';

    /**
     * widget initialization
     */
    public function init()
    {
        parent::init();

        if (is_null($this->modelClassName)) {
            throw new \Exception(BaseAmosModule::t('amosreport', 'Missing Model'));
        }
        if (is_null($this->context_id)) {
            throw new \Exception(BaseAmosModule::t('amosreport', 'Missing Context Id'));
        }

    }


    /**
     * @return mixed
     */
    public function run()
    {
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);

            return $content === false ? $matches[0] : $content;
        }, $this->layout);
        $options = $this->options;

        return $this->render('report',[
            'widget' => $this,
            'content' => $content,
            'context_id' => $this->context_id,
            'className' => $this->modelClassName,
            'title' => $this->title
        ]);
    }

      /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{publisher}`, `{publisherAdv}`.
     * @return string|bool the rendering result of the section, or false if the named section is not supported.
     */
    public function renderSection($name)
    {
        if (isset($this->renderSections[$name]) && $this->renderSections[$name] instanceof \Closure) {
            return call_user_func($this->renderSections[$name], $this->model, $this);
        }
        switch ($name) {
            case '{reportButton}':
                return $this->renderReportButton();
            default:
                return false;
        }
    }

    /**
     * @return string
     */
    public function renderReportButton()
    {
        $href = 'javascript: void(0)';
        $button = Html::a(AmosIcons::show("flag", ["class" => "am-2"]), null , [
            'id' => 'load_form-'.$this->context_id,
            'title' => AmosReport::t('amosreport', 'You can report errors or contents that you consider inappropriate and, if necessary, ask for correction')
        ]);
//        $button = '<a href="' . $href . '" id="load_form" title="'. AmosReport::t('amosreport', 'Report').'">' . AmosIcons::show("flag", ["class" => "am-2"]) . '</a>';
        return $button;
    }


}