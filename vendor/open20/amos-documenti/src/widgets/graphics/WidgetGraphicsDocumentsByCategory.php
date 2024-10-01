<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics
 * @category   CategoryName
 */

namespace open20\amos\documenti\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiCategorie;
use open20\amos\documenti\models\search\DocumentiSearch;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;
use yii\db\ActiveQuery;

/**
 * Class WidgetGraphicsDocumentsByCategory
 * @package open20\amos\documenti\widgets\graphics
 */
class WidgetGraphicsDocumentsByCategory extends WidgetGraphic
{
    /**
     * @var AmosDocumenti $documentsModule
     */
    protected $documentsModule = null;

    /**
     * @var array $documentsCountByCategories
     */
    public $documentsCountByCategories = [];

    /**
     * @var DocumentiCategorie[] $documentsCategories
     */
    public $documentsCategories = [];

    /**
     * @var Documenti[] $documents
     */
    public $documents = [];

    /**
     * @var string $basicListUrl
     */
    protected $basicListUrl = '/documenti/documenti/own-interest-documents';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->documentsModule = AmosDocumenti::instance();

        parent::init();

        $this->setCode('DOCUMENTS_BY_CATEGORY_GRAPHIC');
        $this->setLabel(AmosDocumenti::t('amosdocumenti', '#widget_graphic_documents_by_category_label'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#widget_graphic_documents_by_category_description'));

        $this->documentsCategories = $this->getDocumentCategories();
        $this->documents = $this->findDocuments();

        $this->makeDocumentsCountByCategory();
    }

    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        return $this->render(
            '@vendor/open20/amos-documenti/src/widgets/graphics/views/documents_by_category_widget',
            [
                'widget' => $this,
            ]
        );
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function makeDocumentsCountByCategory()
    {
        foreach ($this->documentsCategories as $documentCategory) {
            $this->documentsCountByCategories[$documentCategory->id] = 0;
        }

        foreach ($this->documents as $document) {
            if (isset($this->documentsCountByCategories[$document->documenti_categorie_id])) {
                $this->documentsCountByCategories[$document->documenti_categorie_id] += 1;
            }
        }
    }

    /**
     * @return DocumentiCategorie[]
     * @throws \yii\base\InvalidConfigException
     */
    protected function getDocumentCategories()
    {
        /** @var DocumentiCategorie $documentiCategorieModel */
        $documentiCategorieModel = $this->documentsModule->createModel('DocumentiCategorie');

        /** @var ActiveQuery $query */
        $query = $documentiCategorieModel::find();

        /** @var DocumentiCategorie[] $documentCategories */
        $documentCategories = $query->all();

        return $documentCategories;
    }

    /**
     * @return Documenti[]
     * @throws \yii\base\InvalidConfigException
     */
    protected function findDocuments()
    {
        /** @var DocumentiSearch $documentiSearchModel */
        $documentiSearchModel = $this->documentsModule->createModel('DocumentiSearch');
        $documentiSearchModel->setNotifier(new NotifyWidgetDoNothing());

        /** @var Documenti[] $documents */
        $documents = $documentiSearchModel->searchOwnInterest([])->getModels();

        return $documents;
    }

    /**
     * This method returns the basic documents list url.
     * @return array
     */
    public function getBasicUrl()
    {
        return [$this->basicListUrl];
    }

    /**
     * This method returns the list url with the category filter.
     * @param int $documentsCategoryId
     * @return array
     */
    public function makeDocumentsByCategoryUrl($documentsCategoryId)
    {
        return [
            $this->basicListUrl,
            'DocumentiSearch[documenti_categorie_id][]' => $documentsCategoryId
        ];
    }
}
