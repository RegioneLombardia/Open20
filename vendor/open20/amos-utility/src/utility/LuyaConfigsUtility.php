<?php

namespace open20\amos\utility\utility;

use open20\amos\admin\models\UserProfile;
use open20\amos\mobile\bridge\modules\v1\models\User as AmosUser;
use luya\admin\file\Item;
use luya\admin\models\Lang;
use luya\admin\models\UserLogin;
use luya\cms\models\Block;
use luya\cms\models\Nav;
use luya\cms\models\NavItem;
use luya\cms\models\NavItemModule;
use luya\cms\models\NavItemPage;
use luya\cms\models\NavItemPageBlockItem;
use luya\cms\models\NavItemRedirect;
use yii\base\ErrorException;
use yii\helpers\Json;

class LuyaConfigsUtility
{
    public $imageAttributes = ['image', 'imageId', 'accordion_image', 'imageModal', 'backgroundImg'];
    public $blockWithImages = ['\app\modules\uikit\blocks\LayoutSectionBlock', '\trk\uikit\blocks\ImageBlock', '\app\modules\uikit\blocks\LinkPanelBlock',
        '\app\modules\uikit\blocks\GalleryPanelBlock', '\trk\uikit\blocks\SliderBlock', '\luya\bootstrap3\blocks\ImageBlock',
        '\trk\uikit\blocks\GalleryBlock', '\luya\bootstrap3\blocks\ImageTextBlock', '\trk\uikit\blocks\SlideshowBlock', '\app\modules\uikit\blocks\SlideshowBlock',
        '\app\modules\uikit\blocks\ModalPanelBlock', '\trk\uikit\blocks\ListBlock', '\trk\uikit\blocks\PanelBlock', '\trk\uikit\blocks\GridBlock', '\trk\uikit\blocks\AccordionBlock', '\app\modules\uikit\blocks\AccordionPlusBlock',
        '\app\modules\uikit\blocks\HeroBannerBlock', '\app\modules\uikit\blocks\ThumbSliderBlock'
    ];
    public $blocksWihMultipleImages = ['\app\modules\uikit\blocks\ThumbSliderBlock', '\app\modules\uikit\blocks\AccordionPlusBlock', '\trk\uikit\blocks\AccordionBlock', '\trk\uikit\blocks\GridBlock', '\trk\uikit\blocks\ListBlock', '\trk\uikit\blocks\GalleryBlock', '\app\modules\uikit\blocks\GalleryPanelBlock', '\trk\uikit\blocks\SliderBlock', '\trk\uikit\blocks\SlideshowBlock', '\app\modules\uikit\blocks\SlideshowBlock'];
    public $blocksWihAttachments = ['\app\modules\uikit\blocks\AttachmentsBlock', '\app\modules\uikit\blocks\DocumentAttachmentsBlock'];
    public $attachmentAttributes = ['attachment'];
    const EXPORT_BASE_PATH = '/web/uploads';

    public $errorMessages = [];
    public $errorDangerMessages = [];

    /**
     * @param $file
     * @param $nav_container_id
     * @param $nav_item_id
     * @param $versionName
     * @param $nav_item_sub_container
     * @return bool
     * @throws \luya\Exception
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function import($file, $nav_container_id = 1, $nav_item_id = null, $versionName = 'new-version', $parent_nav_item_id = null)
    {
//        pr($parent_nav_item_id); die;
        $langDefault = \luya\admin\models\Lang::find()->andWhere(['is_default' => 1])->one();
        $zip = new \ZipArchive();
        $zip->open($file);
        $exportFilePath = \Yii::getAlias('@app') . self::EXPORT_BASE_PATH;
        $extract = $zip->extractTo($exportFilePath);
        $fileExport = '';
        if ($extract) {
            $fileExport = \Yii::getAlias('@app') . self::EXPORT_BASE_PATH . '/export_page.txt';
        }
        $zip->close();

        $this->loginLuya(\Yii::$app->user->id);
        $content = file_get_contents($fileExport);
        $configPageLuya = unserialize($content);
        $cmsBlock = $configPageLuya[Block::className()];
        $currentLanguages = $configPageLuya['currentLanguage'];
        $languagesLuya = $configPageLuya[Lang::className()];
        if(!empty(\Yii::$app->storage)) {
            $storage = \Yii::$app->storage;
        }

        //errori importazioni per lingua
        $countLanguages = count($currentLanguages);
        if ($countLanguages > 1 && !empty($nav_item_id)) {
            $this->errorDangerMessages[] = "Non è possibile importare il file zip esportato con ita e eng all'interno di una versione. E' necessario importarlo come nuova pagina";
            return false;
        }


        //salvo le immagini ed allegati che preso dallo zip
        $imagesToImport = [];
        $images = $configPageLuya['image_files'];
        $attachments = $configPageLuya['attachments_files'];
        if ($storage) {
            //immagini
            foreach ($images as $pageBlockId => $fileElements) {
                foreach ($fileElements as $attribute => $files) {
                    foreach ($files as $imageId => $filename) {
                        $sourceFile = \Yii::getAlias('@app') . self::EXPORT_BASE_PATH . '/' . $filename;
                        if (file_exists($sourceFile)) {
                            $file = $storage->addFile($sourceFile, $filename);
                            $image = $storage->addImage($file->id);
                            if (!is_null($image)) {
                                unlink($sourceFile);
                                $imagesToImport[$pageBlockId][$attribute][$imageId] = $image->id;
                            }
                        }
                    }

                }
            }
            //allegati
            foreach ($attachments as $pageBlockId => $fileElements) {
                foreach ($fileElements as $attribute => $files) {
                    foreach ($files as $fileId => $filename) {
                        $sourceFile = \Yii::getAlias('@app') . self::EXPORT_BASE_PATH . '/' . $filename;
                        if (file_exists($sourceFile)) {
                            $file = $storage->addFile($sourceFile, $filename);
                            if (!is_null($file)) {
                                unlink($sourceFile);
                                $imagesToImport[$pageBlockId][$attribute][$fileId] = $file->id;
                            }
                        }
                    }

                }
            }
        }

        //se sto importando i blocchi dentro una nuova versione di una pagina scelta precedentemente
        if (!empty($nav_item_id)) {
            $navItem = NavItem::findOne($nav_item_id);
            if ($navItem) {
                $this->savePageVersion($configPageLuya, $navItem, $versionName, $imagesToImport, $cmsBlock);
                \Yii::$app->session->addFlash('success', "Creata nuova versione");
            }
            return true;
        }

        //NAV
        $nav = new Nav();
        $nav->load($configPageLuya, Nav::className());
        $nav->nav_container_id = $nav_container_id;
        $nav->is_hidden = true;
        $nav->is_offline = true;
        //seleziono pagina padre
        if (!empty($parent_nav_item_id)) {
            $parentNavItem = NavItem::findOne($parent_nav_item_id);
            if ($parentNavItem) {
                $nav->parent_nav_id = $parentNavItem->nav_id;
            }
        } else {
            $nav->parent_nav_id = 0;
        }

        if ($nav->save(false)) {
            $langs = [];

            //NAV ITEM - pagina con alias e proprietà
            if (!empty($configPageLuya[NavItem::className()])) {
                foreach ($configPageLuya[NavItem::className()] as $navItemImport) {
                    //NAV ITEM REDIRECT
                    $navItemRedirect = $this->importNavItemRedirect($configPageLuya, $navItemImport['id']);
                    //COUNTINA con NAV ITEM
                    $tmpNavItem[NavItem::className()] = $navItemImport;
                    $navItem = new NavItem();
                    $navItem->load($tmpNavItem, NavItem::className());
                    $navItem->nav_id = $nav->id;
                    $langs[] = $navItem->lang_id;
                    $navItem->alias = $navItemImport['alias'] . '-copy-' . date('d-m-y-His');
                    if (!empty($navItemRedirect)) {
                        $navItem->nav_item_type_id = $navItemRedirect->id;
                    }
                    $navItem->detachBehaviors();
                    $navItem->save(false);

                    //NAV ITEM PAGE - versione della pagina
                    if (!empty($configPageLuya[NavItemPage::className()][$navItemImport['id']])) {
                        $navItemPage = new NavItemPage();
                        $tmpNavItemPage[NavItemPage::className()] = $configPageLuya[NavItemPage::className()][$navItemImport['id']];
                        $navItemPageImportId = $tmpNavItemPage[NavItemPage::className()]['id'];
                        $navItemPage->load($tmpNavItemPage, NavItemPage::className());
                        $navItemPage->nav_item_id = $navItem->id;
                        $navItemPage->version_alias .= ' ' . date('d-m-y H:i:s');
                        if ($navItemPage->save(false)) {
                            $navItem->nav_item_type_id = $navItemPage->id; // versione della pagina
                            $navItem->save(false);

                            //NAV ITEM BLOC ITEM - blocchi che costituiscono la versione della pagina
                            if (!empty($configPageLuya[NavItemPageBlockItem::className()][$navItemPageImportId])) {
                                $blockStructure = [];
                                $blocksToImport = $configPageLuya[NavItemPageBlockItem::className()][$navItemPageImportId];
                                foreach ($blocksToImport as $importBlock) {
                                    $blockStructure = $this->importBlockRecursive($configPageLuya, $importBlock, $navItemPage, $navItemPageImportId, $imagesToImport, $blockStructure, $cmsBlock);
                                }
                            }
                        }
                    }

                }
            }
        }
        //se sto creando una nuova pagina in inglese senza creare la pagina di default in ita, ne creo una vuota
        if (!in_array($langDefault->id, $langs)) {
            $firstNavItem = reset($configPageLuya[NavItem::className()]);
            $tmpNavItem[NavItem::className()] = $firstNavItem;
            $emptyNavitem = new NavItem();
            $emptyNavitem->load($tmpNavItem, NavItem::className());
            $emptyNavitem->nav_id = $nav->id;
            $emptyNavitem->lang_id = $langDefault->id;
            $emptyNavitem->save(false);

            $emptyNavItemPage = new NavItemPage();
            $emptyNavItemPage->nav_item_id = $emptyNavitem->id;
            $emptyNavItemPage->version_alias .= "Versione iniziale";
            $emptyNavItemPage->layout_id = 1;
            $emptyNavItemPage->save(false);
            $emptyNavitem->nav_item_type_id = $emptyNavItemPage->id;
            $emptyNavitem->save(false);
        }

        //distruggo i file rimasti dopo l'importazione
        if (file_exists($fileExport)) {
            unlink($fileExport);
        }

        foreach ($images as $pageBlockId => $fileElements) {
            foreach ($fileElements as $attribute => $files) {
                foreach ($files as $imageId => $filename) {
                    if (file_exists($exportFilePath . $filename)) {
                        unlink($exportFilePath . $filename);
                    }
                }
            }
        }
        return true;
    }

    /**
     * @param $configPageLuya
     * @param $navItemImportId
     * @return NavItemRedirect|null
     */
    public function importNavItemRedirect(&$configPageLuya, $navItemImportId)
    {
        if (!empty($configPageLuya[NavItemRedirect::className()][$navItemImportId])) {
            $tmpNavItemRedirect[NavItemRedirect::className()] = $configPageLuya[NavItemRedirect::className()][$navItemImportId];
            $navItemRedirect = new NavItemRedirect();
            $navItemRedirect->load($tmpNavItemRedirect, NavItemRedirect::className());
            if ($configPageLuya[NavItemRedirect::className()][$navItemImportId]['type'] == 1) {
                if (!empty($configPageLuya[NavItemRedirect::className()][$navItemImportId]['alias_redir'])) {
                    $navItemRedir = NavItem::find()->andWhere(['alias' => $configPageLuya[NavItemRedirect::className()][$navItemImportId]['alias_redir']])->one();
                    if ($navItemRedirect) {
                        $navItemRedirect->value = $navItemRedir->id;
                    }
                }
            }
            $navItemRedirect->save(false);
            return $navItemRedirect;
        }
        return null;
    }

    /**
     * @param $configPageLuya
     * @param $importBlock
     * @param $navItemPage
     * @param $navItemPageImportId
     * @param $imagesToImport
     * @param $blockStructure
     * @return mixed
     */
    public function importBlockRecursive(&$configPageLuya, $importBlock, $navItemPage, $navItemPageImportId, $imagesToImport, $blockStructure, $cmsBlocks)
    {
//        try {
        $tmpNavItemBlock[NavItemPageBlockItem::className()] = $importBlock;
        $block = new NavItemPageBlockItem();
        $block->load($tmpNavItemBlock, NavItemPageBlockItem::className());
        $block->nav_item_page_id = $navItemPage->id;

        if (!empty($cmsBlocks[$block->block_id])) {
            $cmsBlock = Block::find()->andWhere(['class' => $cmsBlocks[$block->block_id]])->one();
            if ($cmsBlock) {
                $block->block_id = $cmsBlock->id;

                //STRUTTURA AD ALBERO DEI BLOCCHI
                //assegno i prev_id in modo che venga rispettata la struttura ad albero originale
                // se il prev_id del blocco è già dentro $blockStructure vuol dire che è stato già creato, se non c'è vado a crearli ricorsivamente
                if ($importBlock['prev_id'] != 0) {
                    if (!empty($blockStructure[$importBlock['prev_id']])) {
                        $block->prev_id = $blockStructure[$importBlock['prev_id']]; //la struttura dei blocchi in pagina
                    } else {
                        if (!empty($configPageLuya[NavItemPageBlockItem::className()][$navItemPageImportId][$importBlock['prev_id']])) {
                            $blockStructure = $this->importBlockRecursive($configPageLuya, $configPageLuya[NavItemPageBlockItem::className()][$navItemPageImportId][$importBlock['prev_id']], $navItemPage, $navItemPageImportId, $imagesToImport, $blockStructure, $cmsBlocks);
                            //dopo aver salvato il blocco che mancanva lo aggiungo come prev
                            if (!empty($blockStructure[$importBlock['prev_id']])) {
                                $block->prev_id = $blockStructure[$importBlock['prev_id']];
                            }
                        }
                    }
                } else {
                    $block->prev_id = 0;
                }


                //se  il blocco non è stato già ancora creato alora creo le immagini e salvo il blocco
                if (empty($blockStructure[$importBlock['id']])) {
                    // ----------    SALVA IMMAGINI ---------------
                    $values = Json::decode($block->json_config_values);
                    //singola immagine
                    foreach ($this->imageAttributes as $attribute) {
                        if (!empty($values[$attribute]) && !empty($imagesToImport[$importBlock['id']][$attribute])) {
                            $imageId = $values[$attribute];
                            $values[$attribute] = $imagesToImport[$importBlock['id']][$attribute][$imageId];
                        }
                    }
                    //molte immagini
                    if (!empty($values['items']) && !empty($imagesToImport[$importBlock['id']])) {
                        foreach ($values['items'] as $key => $item) {
                            foreach ($this->imageAttributes as $attribute) {
                                if (!empty($item[$attribute]) && !empty($imagesToImport[$importBlock['id']][$attribute])) {
                                    $imageId = $item[$attribute];
                                    $values['items'][$key][$attribute] = $imagesToImport[$importBlock['id']][$attribute][$imageId];
                                }
                            }
                        }
                    }
                    // ----------    SALVA ALLEGATI ---------------
                    //singolo allegato
                    foreach ($this->attachmentAttributes as $attribute) {
                        if (!empty($values[$attribute]) && !empty($imagesToImport[$importBlock['id']][$attribute])) {
                            $fileId = $values[$attribute];
                            $values[$attribute] = $imagesToImport[$importBlock['id']][$attribute][$fileId];
                        }
                    }
                    //molti allegati
                    if (!empty($values['items']) && !empty($imagesToImport[$importBlock['id']])) {
                        foreach ($values['items'] as $key => $item) {
                            foreach ($this->attachmentAttributes as $attribute) {
                                if (!empty($item[$attribute]) && !empty($imagesToImport[$importBlock['id']][$attribute])) {
                                    $fileId = $item[$attribute];
                                    $values['items'][$key][$attribute] = $imagesToImport[$importBlock['id']][$attribute][$fileId];
                                }
                            }
                        }
                    }

                    $block->json_config_values = Json::encode($values);

                    // ----------    SALVA BLOCCO ---------------
                    $block->save(false);
                    $blockStructure[$importBlock['id']] = $block->id;
                }
//        }catch (ErrorException $e){
//            $this->errorMessages[] = $e->getMessage();
//        }
//        catch (\Exception $e){
//            $this->errorMessages[] = $e->getMessage();
//        }
//        catch (\Error $e){
//            $this->errorMessages[] = $e->getMessage();
//        }
            } else {
                $this->errorMessages[] = "Il blocco " . $cmsBlocks[$block->block_id] . " non esiste";
            }
        }
        return $blockStructure;

    }

    /**
     * @param $configPageLuya
     * @param $navItem
     * @param $versionName
     * @param $imagesToImport
     * @return void
     */
    public function savePageVersion($configPageLuya, $navItem, $versionName, $imagesToImport, $cmsBlock)
    {
        if (!empty($configPageLuya[NavItemPage::className()])) {
            foreach ($configPageLuya[NavItemPage::className()] as $navItemPageImport) {
                $navItemPage = new NavItemPage();
                $tmpNavItemPage[NavItemPage::className()] = $navItemPageImport;
                $navItemPageImportId = $tmpNavItemPage[NavItemPage::className()]['id'];
                $navItemPage->load($tmpNavItemPage, NavItemPage::className());
                $navItemPage->nav_item_id = $navItem->id;
                $navItemPage->version_alias = $versionName . ' ' . date('d-m-Y H:i');
                if ($navItemPage->save(false)) {
                    //NAV ITEM BLOCK ITEM - blocchi che costituiscono la versione della pagina
                    if (!empty($configPageLuya[NavItemPageBlockItem::className()][$navItemPageImportId])) {
                        $blockStructure = [];
                        foreach ($configPageLuya[NavItemPageBlockItem::className()][$navItemPageImportId] as $importBlock) {
                            $blockStructure = $this->importBlockRecursive($configPageLuya, $importBlock, $navItemPage, $navItemPageImportId, $imagesToImport, $blockStructure, $cmsBlock);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $nav_id
     * @return void|\yii\console\Response|\yii\web\Response
     */
    public function export($nav_id, $nav_item_id = null, $version = null)
    {
        $errorMessages = [];
        $export = [];
        $attachments = [];
        $images = [];

        $blocksWihImages = $this->blockWithImages;
        $blocksWihMultipleImages = $this->blocksWihMultipleImages;
        $blocksWihAttachments = $this->blocksWihAttachments;

        // NAV
        $nav = Nav::findOne($nav_id);

        $export[get_class($nav)] = $nav->attributes;
        $query = $nav->getNavItems();
        if (!empty($nav_item_id)) {
            $query->andWhere(['cms_nav_item.id' => $nav_item_id]);
        }
        $navItems = $query->all();

        //LINGUE
        $languages = Lang::find()->all();
        foreach ($languages as $lang) {
            $export[Lang::className()][$lang->id] = $lang->short_code;
        }


        //NAV ITEM
        /** @var  $navItem NavItem */
        foreach ($navItems as $navItem) {
            if (!empty($navItem)) {
                //scelgo la versione
                if (!empty($version)) {
                    $navItem->nav_item_type_id = $version;
                }
                $export['currentLanguage'][] = $navItem->lang->short_code;

                $export[get_class($navItem)][] = $navItem->attributes;
                $navItemPage = NavItemPage::find()
                    ->andWhere(['nav_item_id' => $navItem->id, 'id' => $navItem->nav_item_type_id])->one();

                //VERSIONE PAGINA
                if ($navItemPage) {
                    $export[get_class($navItemPage)][$navItem->id] = $navItemPage->attributes;

                    $blockItems = NavItemPageBlockItem::find()->andWhere(['nav_item_page_id' => $navItemPage->id])
                        ->orderBy('prev_id ASC, sort_index ASC')
                        ->all();

                    //BLOCCHI DELLA VERSIONE
                    foreach ($blockItems as $blockItem) {
                        $export[get_class($blockItem)][$navItemPage->id][$blockItem->id] = $blockItem->attributes;
                        $block = $blockItem->block;
                        $export[Block::className()][$block->id] = $block->class;

                        //ESPORTA IMMAGINI
                        $this->exportImages($block, $blockItem, $blocksWihImages, $blocksWihMultipleImages, $images, $export);


                        //ESPORTA ALLEGATI
                        $this->exportAttachments($block, $blockItem, $blocksWihAttachments, $attachments, $export);
                    }

                    $navItemModule = NavItemModule::findOne($navItem->nav_item_type_id);
//
                    if ($navItemModule) {
                        $export[get_class($navItemModule)][$navItem->id] = $navItemModule->attributes;
                    }

                }
                // ESPORTA REDIRECT
                $navItemRedirect = NavItemRedirect::findOne($navItem->nav_item_type_id);
                if ($navItemRedirect) {
                    $redirectAttributes = $navItemRedirect->attributes;
                    if (!empty($redirectAttributes) && $redirectAttributes['type'] == 1) {
                        $navitemRedir = NavItem::findOne($redirectAttributes['value']);
                        if ($navitemRedir) {
                            $redirectAttributes['alias_redir'] = $navitemRedir->alias;
                        }
                    }
                    $export[get_class($navItemRedirect)][$navItem->id] = $redirectAttributes;
                }
            }
        }
        //prefix  per lingua
        $postFix = '';

        if (!empty($export['currentLanguage'])) {
            $countLanguages = count($export['currentLanguage']);
            if ($countLanguages == 1) {
                $currentLang = $export['currentLanguage'][0];
                $postFix = '_' . $currentLang;
            } else if ($countLanguages > 1) {
                $postFix = '_all_languages';
            }
        }
//        pr($export['image_files']); die;

        //  SALVO FILE CON LA STRUTTURA SERIALIZZATA DELL PAGINA
        $encodedExport = serialize($export);
        $name = "export_page.txt";
        $exportFilePath = \Yii::getAlias('@app') . self::EXPORT_BASE_PATH . '/' . $name;
        $myfile = fopen($exportFilePath, "w+") or die("Unable to open file!");
        fwrite($myfile, $encodedExport);
        fclose($myfile);

        //CREO LO ZIP CON IL FILE DI ESPORTAZIONE E LE IMMAGINI
        $zipName = "export_page_$nav_id" . $postFix;
        $pathZip = $this->createZip($images, $attachments, $exportFilePath, $name, $zipName, $errorMessages);
        $this->showWarningAlert($errorMessages);

        if (file_exists($pathZip)) {
            \Yii::$app->response->sendFile($pathZip);
            unlink($pathZip);
            return;
        } else {
            pr('non esisite');
        }
        die;
    }


    public function exportImages($block, $blockItem, $blocksWihImages, $blocksWihMultipleImages, &$images, &$export)
    {
        if ($block && in_array($block->class, $blocksWihImages)) {
            $configValues = json_decode($blockItem->json_config_values);
            //blocchi con tante immagini
            if (in_array($block->class, $blocksWihMultipleImages)) {
                if (!empty($configValues->items)) {
                    foreach ($configValues->items as $item) {
                        foreach ($this->imageAttributes as $attrImage) {
                            $this->setImageConfigExport($blockItem, $item, $attrImage, $images, $export);
                        }
                    }
                }
                //blocchi con una sola immagine
            }
            if (in_array($block->class, $blocksWihImages)) {
                foreach ($this->imageAttributes as $attrImage) {
                    $this->setImageConfigExport($blockItem, $configValues, $attrImage, $images, $export);
                }
            }
        }
    }

    /**
     * @param $blockItem
     * @param $item
     * @param $attrImage
     * @param $files
     * @param $export
     * @return void
     */
    public function setImageConfigExport($blockItem, $item, $attrImage, &$images, &$export)
    {
        if (!empty($item->$attrImage)) {
            if (!empty(\Yii::$app->storage)) {
                $image = \Yii::$app->storage->getImage($item->$attrImage);
                $file = $image->getFile();
                $images[$blockItem->id][$attrImage][$image->id] = $image;
                $export['image_files'][$blockItem->id][$attrImage][$image->id] = $blockItem->id . '_' . $file->name;
            }
        }
    }

    /**
     * @param $block
     * @param $blockItem
     * @param $blockWithAttachments
     * @param $attachments
     * @param $export
     * @return void
     */
    public function exportAttachments($block, $blockItem, $blockWithAttachments, &$attachments, &$export)
    {
        if ($block && in_array($block->class, $blockWithAttachments)) {
            $configValues = json_decode($blockItem->json_config_values);
            //blocchi con tanti allegati
            if (!empty($configValues->items)) {
                foreach ($configValues->items as $item) {
                    foreach ($this->attachmentAttributes as $attrAttachment) {
                        $this->setAttachmentConfigExport($blockItem, $item, $attrAttachment, $attachments, $export);
                    }
                }
            }

            //blocchi con una solo allegato
            foreach ($this->attachmentAttributes as $attrAttachment) {
                $this->setAttachmentConfigExport($blockItem, $configValues, $attrAttachment, $attachments, $export);
            }
        }
    }

    /**
     * @param $blockItem
     * @param $item
     * @param $attrAttachment
     * @param $attachments
     * @param $export
     * @return void
     */
    public function setAttachmentConfigExport($blockItem, $item, $attrAttachment, &$attachments, &$export)
    {
        if (!empty($item->$attrAttachment)) {
            if (!empty(\Yii::$app->storage)) {
                $file = \Yii::$app->storage->getFile($item->$attrAttachment);
            }
            $attachments[$blockItem->id][$attrAttachment][$file->id] = $file;
            $export['attachments_files'][$blockItem->id][$attrAttachment][$file->id] = $blockItem->id . '_' . $file->name;
        }
    }


    public function showWarningAlert($errorMessages)
    {
        if (!empty($errorMessages)) {
            $message = implode("<br>", $errorMessages);
            \Yii::$app->session->addFlash('warning', $message);
        }
    }


    /**
     * @param $imageFiles
     * @param $attachmentFiles
     * @param $exportFile
     * @param $exportFilename
     * @param $zipname
     * @param $errorMessages
     * @return string
     * @throws \Exception
     */
    public function createZip($imageFiles, $attachmentFiles, $exportFile, $exportFilename, $zipname, &$errorMessages)
    {
        $filePath = \Yii::getAlias('@app') . self::EXPORT_BASE_PATH . '/' . $zipname . '.zip';

        $zip = new \ZipArchive();
        if ($zip->open($filePath, \ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Cannot create a zip file');
        }

        //AGGIUNGO LE IMMAGINI ALLO ZIP
        foreach ($imageFiles as $blockItemId => $files) {
            foreach ($files as $attribute => $images) {
                foreach ($images as $imageId => $image) {
                    $file = $image->getFile();
                    /** @var $file Item */
                    if (file_exists($image->getServerSource())) {
                        $zip->addFile($image->getServerSource(), $blockItemId . '_' . $file->name);
                    } else {
                        $errorMessages[] = "Il file <strong>{$file->name}</strong> non esisite";
                    }
                }
            }
        }
        //AGGIUNGO GLI ALLEGATI ALLO ZIP
        foreach ($attachmentFiles as $blockItemId => $files) {
            foreach ($files as $attribute => $attachments) {
                foreach ($attachments as $fileId => $file) {
                    /** @var $file Item */
                    if (file_exists($file->getServerSource())) {
                        $zip->addFile($file->getServerSource(), $blockItemId . '_' . $file->name);
                    } else {
                        $errorMessages[] = "Il file <strong>{$file->name}</strong> non esisite";
                    }
                }
            }
        }
        //AGGIUNGO LA STRUTTURA DELLA PAGINA ALLO ZIP
        $zip->addFile($exportFile, $exportFilename);
        $zip->close();

        //unlink files
        unlink($exportFile);

        return $filePath;

    }

    /**
     * @param $id
     * @return int
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function loginLuya($id)
    {

        $amosuser = \open20\amos\mobile\bridge\modules\v1\models\User::findOne($id);
        $user = $this->getCmsUser($amosuser);
        if (!is_null($user)) {
            \Yii::$app->adminuser->idParam = '__luyaAdmin_id';
            if ($user && \Yii::$app->adminuser->login($user)) {

                $user->updateAttributes([
                    'force_reload' => false,
                    'login_attempt' => 0,
                    'login_attempt_lock_expiration' => null,
                    'auth_token' => \Yii::$app->security->hashData(\Yii::$app->security->generateRandomString(),
                        $user->password_salt),
                ]);
                // kill prev user logins
                UserLogin::updateAll(['is_destroyed' => true],
                    ['user_id' => $user->id]);

                // create new user login
                $login = new UserLogin([
                    'auth_token' => $user->auth_token,
                    'user_id' => $user->id,
                    'is_destroyed' => false,
                ]);
                $login->save();

                // refresh user online list
//                UserOnline::refreshUser($user, 'login');
            }
        }
        return $user->id;
    }

    /**
     *
     * @param  $amosuser AmosUser
     */
    public function getCmsUser(AmosUser $amosuser)
    {

        $user = \luya\admin\models\User::findOne(['email' => $amosuser->email]);

        if (is_null($user)) {
            /* @var $userProfile UserProfile */
            $userProfile = $amosuser->userProfile;
            $user = new \luya\admin\models\User();
            $user->firstname = $userProfile->nome;
            $user->lastname = $userProfile->cognome;
            $user->email = $amosuser->email;
            $salt = \Yii::$app->security->generateRandomString();
            $pw = \Yii::$app->security->generatePasswordHash('' . $salt);
            $user->password = $pw;
            $user->password_salt = $salt;
            $user->title = 1;
            $user->is_deleted = false;
            $user->save();


            /* var $command yii\db\Command */
            $command = \Yii::$app->db->createCommand();
            $defaultCmsGroup = \Yii::$app->params['defaultCmsGroup'];
            $group_id = 1;

            if (\Yii::$app->user->can('ASSISTENZA_EVENTI')) {
                $group_id = 3;
            } else if ($defaultCmsGroup) {
                $res = \Yii::$app->db->createCommand('Select * from admin_group where id = ' . $defaultCmsGroup)->queryAll();
                if (!empty($res)) {
                    $group_id = $defaultCmsGroup;
                }
            }


            $command->insert('{{%admin_user_group}}',
                [
                    'user_id' => $user->id,
                    'group_id' => $group_id,
                ])->execute();
        }
        return $user;
    }


}