<?php
namespace app\modules\cms\data;

use yii\data\ArrayDataProvider;
use yii\data\Pagination;


class CmsArrayDataProvider extends ArrayDataProvider
{
    private $paginator = null;

    public function getPaginator()
    {
        return $this->paginator;
    }

    public function setPaginator(Pagination $pager)
    {
        $this->paginator = $pager;
    }
}