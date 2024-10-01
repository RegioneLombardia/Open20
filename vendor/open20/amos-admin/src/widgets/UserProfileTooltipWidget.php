<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 25/07/2019
 * Time: 11:18
 */

namespace open20\amos\admin\widgets;


use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;use yii\base\Widget;

class UserProfileTooltipWidget extends Widget
{
    public $model;
    public $position = 'right';

    /**
     *
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        $model = $this->model;
        $tooltip = $this->generateTooltipText();
        $text = "{$model->surnameName}<span class=\"tooltip-field m-l-10\">
                                <span title=\"\" data-toggle=\"tooltip\" data-placement=\"{$this->position}\" data-original-title=\"$tooltip\" aria-describedby=\"tooltip{$model->id}\">
                                    <span class=\"am am-info\">
                                    </span>
                                </span>
                                <div class=\"tooltip fade top\" role=\"tooltip\" id=\"tooltip{$model->id}\" style=\"top: -47px; left: 590.281px; display: none;\"><div class=\"tooltip-arrow\" style=\"left: 50%;\"></div>
                                    <div class=\"tooltip-inner\">$tooltip</div>
                                </div>
                            </span>";
        return $text;

    }

    /**
     * @param $model
     * @return array
     */
    public function fieldsProfile($model){
        $enabledFields = [];
        $adminModule = \Yii::$app->getModule(AmosAdmin::getModuleName());
        $enabledFields []=  [
            'label' => AmosAdmin::t('amosadmin', 'Cognome e nome'),
            'value' => $model->surnameName
        ] ;
        if(
            ($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_VIEW)) &&
            ($adminModule->confManager->isVisibleField('presentazione_breve', ConfigurationManager::VIEW_TYPE_VIEW))
            && $model->presentazione_breve
           ){
            $enabledFields []=  [
                'label' => $model->getAttributeLabel('presentazione_breve'),
                'value' => $model->presentazione_breve
            ] ;
         }
         if (
                    ($adminModule->confManager->isVisibleBox('box_presentazione_personale', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                    ($adminModule->confManager->isVisibleField('presentazione_personale', ConfigurationManager::VIEW_TYPE_VIEW))
                    && $model->presentazione_personale
         ) {
            $enabledFields []=  [
                'label' => $model->getAttributeLabel('presentazione_personale'),
                'value' => $model->presentazione_personale
            ] ;
        }
        if ($adminModule->confManager->isVisibleField('email', ConfigurationManager::VIEW_TYPE_VIEW)){
            $enabledFields []=  [
                'label' => $model->user->getAttributeLabel('email'),
                'value' => $model->user->email
            ] ;
        }
        if ($adminModule->confManager->isVisibleField('telefono', ConfigurationManager::VIEW_TYPE_VIEW)){
            $enabledFields []=  [
                'label' => $model->getAttributeLabel('telefono'),
                'value' => $model->telefono
            ] ;
        }
        if ($adminModule->confManager->isVisibleField('email_pec', ConfigurationManager::VIEW_TYPE_VIEW)){
            $enabledFields []=  [
                'label' => $model->getAttributeLabel('email_pec'),
                'value' => $model->email_pec
            ] ;
        }
        if (
                    ($adminModule->confManager->isVisibleBox('box_prevalent_partnership', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                    ($adminModule->confManager->isVisibleField('prevalent_partnership_id', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                    !empty($model->prevalent_partnership_id)
        ){
            $enabledFields []=  [
                'label' => AmosAdmin::t('amosadmin', 'Partnership'),
                'value' => $model->prevalentPartnership->name
            ] ;
        }
        return $enabledFields;
    }

    /**
     * @return string
     */
    public function generateTooltipText(){
        $fields = $this->fieldsProfile($this->model);
        $text = '';
        $i = 0;
        foreach ($fields as $field){
            if($i !== 0){
                $text .= ', ';
            }
            $text .= $field['label'].': '.$field['value']."\n\r";
            $i++;
        }
        return $text;
    }


}