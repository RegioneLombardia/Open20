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
use open20\amos\core\record\Record;
use open20\amos\report\AmosReport;
use open20\amos\report\utilities\ReportUtil;
use Yii;
use yii\base\Widget;

/**
 * Class ReportWidget
 * @package open20\amos\report\widgets
 */
class ReportFlagWidget extends Widget
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
     * @var Record
     */
    public $model;


    public $permissionName = null;

    private $hasPermission = false;

    /**
     * widget initialization
     */
    public function init()
    {
        parent::init();

        if (is_null($this->model)) {
            throw new \Exception(BaseAmosModule::t('amosreport', 'Missing Model'));
        } else {
            $this->modelClassName = $this->model->classname();
            $this->context_id = $this->model->id;
        }

        if(!is_null($this->permissionName)){
            $this->hasPermission = Yii::$app->user->can($this->permissionName, ['model' => $this->model]);
        } else {
            $this->hasPermission = (Yii::$app->user->can(strtoupper($this->model->formName(). '_UPDATE'),  ['model' => $this->model])
                || \Yii::$app->user->can( $this->modelClassName. '_UPDATE', ['model' => $this->model]));
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

        if($this->hasPermission) {
            return $content 
                . ReportsListModalWidget::widget([
                    'model' => $this->model
                ]);
        }
        
        return '';
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

        return Html::tag(
            'div',
            Html::a(
                AmosIcons::show(
                    'flag', 
                    ['class' => 'am-2']
                ), 
                null, 
                [
                    'id' => 'load_reports_list_from_flag-' . $this->context_id,
                    'title' => AmosReport::t('amosreport', '#view_reports_list'),
                ]
            ),
            [
                'class' => 'reportflag-widget' 
                    . (count(ReportUtil::retrieveUnreadReports($this->modelClassName, $this->context_id)) > 0 
                        ? ' unread-report' 
                        : ''
                    )
            ]
        );

    }

}