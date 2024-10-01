<?php

namespace open20\amos\documenti\widgets;

use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use yii\base\Widget;
use yii\helpers\VarDumper;

class ItemDocumentCardWidget extends Widget
{
    private $model;
    private $dateSyncDrive;
    private $type;
    private $size;
    private $date;
    private $customTooltipInfo;
    private $fileName;
    private $nameSurname;
    private $category;
    private $community;
    private $widthColumn;
    private $allegatiNum;
    private $actionModify;
    private $actionDelete;
    private $actionView;
    private $newPubblication;
    private $versionFile;
    private $link_document;
    private $fileUrl;
    private $title;

    /**
     * 'row' render views/hierarchical_document_list_cms, 'card' render views/hierarchical_document_card_cms
     * @var string $typeView
     */
    public $typeView = 'row';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->typeView == 'row') {
            $view = '@vendor/open20/amos-documenti/src/widgets/views/hierarchical_document_list_cms';
        } else {
            $view = '@vendor/open20/amos-documenti/src/widgets/views/hierarchical_document_card_cms';
        }
        return $this->render($view, [
                'widget' => $this,
        ]);
    }
    
    /**
     * @return Documenti
     */
    function getModel()
    {
        return $this->model;
    }

    function getDateSyncDrive()
    {
        return $this->dateSyncDrive;
    }

    function getType()
    {
        return $this->type;
    }

    function getSize()
    {
        return $this->size;
    }

    function getDate()
    {
        return $this->date;
    }

    function getCustomTooltipInfo()
    {
        return $this->customTooltipInfo;
    }

    function getFileName()
    {
        return $this->fileName;
    }

    function getNameSurname()
    {
        return $this->nameSurname;
    }

    function getCategory()
    {
        return $this->category;
    }

    function getCommunity()
    {
        return $this->community;
    }

    function getWidthColumn()
    {
        return $this->widthColumn;
    }

    function getAllegatiNum()
    {
        return $this->allegatiNum;
    }

    function getActionModify()
    {
        return $this->actionModify;
    }

    function getActionDelete()
    {
        return $this->actionDelete;
    }

    function getActionView()
    {
        return $this->actionView;
    }

    function getNewPubblication()
    {
        return $this->newPubblication;
    }

    function getVersionFile()
    {
        return $this->versionFile;
    }

    function getLink_document()
    {
        return $this->link_document;
    }

    function getFileUrl()
    {
        return $this->fileUrl;
    }

    function getTitle()
    {
        return $this->title;
    }

    function setModel($model)
    {
        $this->model = $model;
    }

    function setDateSyncDrive($dateSyncDrive)
    {
        $this->dateSyncDrive = $dateSyncDrive;
    }

    function setType($type)
    {
        $this->type = $type;
    }

    function setSize($size)
    {
        $this->size = $size;
    }

    function setDate($date)
    {
        $this->date = $date;
    }

    function setCustomTooltipInfo($customTooltipInfo)
    {
        $this->customTooltipInfo = $customTooltipInfo;
    }

    function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    function setNameSurname($nameSurname)
    {
        $this->nameSurname = $nameSurname;
    }

    function setCategory($category)
    {
        $this->category = $category;
    }

    function setCommunity($community)
    {
        $this->community = $community;
    }

    function setWidthColumn($widthColumn)
    {
        $this->widthColumn = $widthColumn;
    }

    function setAllegatiNum($allegatiNum)
    {
        $this->allegatiNum = $allegatiNum;
    }

    function setActionModify($actionModify)
    {
        $this->actionModify = $actionModify;
    }

    function setActionDelete($actionDelete)
    {
        $this->actionDelete = $actionDelete;
    }

    function setActionView($actionView)
    {
        $this->actionView = $actionView;
    }

    function setNewPubblication($newPubblication)
    {
        $this->newPubblication = $newPubblication;
    }

    function setVersionFile($versionFile)
    {
        $this->versionFile = $versionFile;
    }

    function setLink_document($link_document)
    {
        $this->link_document = $link_document;
    }

    function setFileUrl($fileUrl)
    {
        $this->fileUrl = $fileUrl;
    }

    function setTitle($title)
    {
        $this->title = $title;
    }
}