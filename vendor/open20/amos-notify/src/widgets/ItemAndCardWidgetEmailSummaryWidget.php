<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\widgets
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\widgets;

use open20\amos\core\forms\ItemAndCardHeaderWidget;

/**
 * Class ItemAndCardWidgetEmailSummaryWidget
 * @package open20\amos\notificationmanager\widgets
 */
class ItemAndCardWidgetEmailSummaryWidget extends ItemAndCardHeaderWidget
{
    /**
     * @var string $layout Widget view
     */
    public $layout = "@vendor/open20/amos-notify/src/views/email/item_and_card_header_widget_mail.php";
    
    /**
     * @var bool $showPrevalentPartnershipAndTargets
     */
    public $showPrevalentPartnershipAndTargets = false;
    
    /**
     * @var bool $absoluteUrlAvatar
     */
    public $absoluteUrlAvatar = true;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setPublicationDateNotPresent(true);
        parent::init();
    }
}
