<?php

namespace luya\bootstrap4\grid;

use luya\bootstrap4\widgets\LinkPager;

/**
 * GridView Bootstrap4 Widget.
 *
 * @since 1.0.0
 */
class GridView extends \yii\grid\GridView
{
    /**
     * Apply class `table-responsive` if enabled.
     */
    public $responsive = false;
    
    /**
     * @var array the HTML attributes for the grid table element.
     */
    public $tableOptions = ['class' => 'table table-striped table-bordered'];
    
    /**
     * @inheritdoc
     */
    public $pager = ['class' => LinkPager::class];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if ($this->responsive) {
            $this->tableOptions['class'] = $this->tableOptions['class'] . ' table-responsive';
        }
    }
}
