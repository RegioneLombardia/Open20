<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use app\modules\uikit\Module;
use app\modules\backendobjects\frontend\blockgroups\ContenutoGroup;


final class LinkPanelNewBlock extends BaseUikitBlock {

    /**
     * @inheritdoc
     */
    protected $component = "linkpanelnew";

    /**
     * @inheritdoc
     */
    public function blockGroup() {
        return ContenutoGroup::class;
    }

    /**
     * @inheritdoc
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_backend_linkpanel');
    }

    /**
     * @inheritdoc
     */
    public function icon() {
        return 'link';
    }

    /**
     * @inheritdoc
     */
    public function admin() {
        if (strlen($this->getVarValue('text_content'))) {
            $button = $this->frontend();
            $button = str_replace("<a", "<button", $button);
            $button = str_replace("</a>", "</button>", $button);
            return $button;

        } else {
            return '<div><span class="block__empty-text">' . Yii::t('backendobjects', 'block_module_backend_linkpanel_no_content') . '</span></div>';
        }
    }
    
    public function getContrastColor($hexcolor){
        
        $r = hexdec(substr($hexcolor, 1, 2));
        $g = hexdec(substr($hexcolor, 3, 2));
        $b = hexdec(substr($hexcolor, 5, 2));
        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
        
        return ($yiq >= 128) ? '#343a40' : 'white';
    }
    
    public function extraVars(){
        return [
            'style' => $this->getStyle(),                   
        ];
    }
    
    public function getStyle(){
        
        $return = '';
        
        $background = $this->getVarValue('button_background');
        $btnFill=$this->getVarValue('button_filling');
        $color = '';

        $style = [];
        if($background){
            if($btnFill=='outline'){
                $style[] = "color:$background";
                $style[] = "border:1px solid$background";
                $style[] = "baground-color:#fff";
            }else{
                $style[] = "background-color:$background";
                $style[] = "color:{$this->getContrastColor($background)}";
            }
            
          
           
                
            $return = implode(';',$style);
            
        }

        return $return;
    }

}
