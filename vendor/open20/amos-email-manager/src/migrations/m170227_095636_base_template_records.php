<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

use open20\amos\emailmanager\models\EmailTemplate;
use yii\db\Migration;

class m170227_095636_base_template_records extends Migration
{
    const TABLE = '{{%email_template}}';

    private $tableName;

    public function up()
    {
        try {
            $template = new EmailTemplate;
            $template->name = "layout_default";
            $template->subject = "{{subject}}";
            $template->heading = "{{heading}}";
            $template->message = "{{#heading}}<h1>{{{heading}}}</h1>{{/heading}}{{{message}}}";
            $template->insert();

            $template = new EmailTemplate;
            $template->name = "layout_fancy";
            $template->subject = "{{contents}}";
            $template->heading = "{{contents}}";
            $template->message = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" rel=\"font-size: 14px;\" style=\"font-size: 14px;\"><tbody><tr><td align=\"center\" valign=\"top\">{{#heading}}\r\n                        {{/heading}}\r\n		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" align=\"center\" style=\"background-color: rgb(253, 253, 253); border-width: 1px; border-style: solid; border-color: rgb(214, 214, 214); border-radius: 6px !important;\"><tbody><tr><td align=\"center\" valign=\"top\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"background-color: rgb(57, 71, 85); color: rgb(255, 255, 255); border-bottom-width: 0px; border-bottom-style: initial; font-family: Arial; font-weight: bold; line-height: 100%; vertical-align: middle; border-top-left-radius: 6px !important; border-top-right-radius: 6px !important;\" bgcolor=\"#557da1\"><tbody><tr><td><h1 style=\"color: rgb(255, 255, 255); margin-bottom: 0px; padding: 28px 24px; font-family: Arial; font-size: 30px; line-height: 150%;\">{{{heading}}}</h1></td></tr></tbody></table></td></tr><tr><td align=\"center\" valign=\"top\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\"><tbody><tr><td valign=\"top\" style=\"border-radius: 6px !important;\"><table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" width=\"100%\"><tbody><tr><td valign=\"top\">\r\n								{{{contents}}}\r\n							</td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" align=\"center\"><tbody><tr><td><p style=\"color: rgb(115, 115, 115); font-family: Arial; font-size: 12px; line-height: 150%;\"><br><span style=\"font-size:16px;font-weight:bold;\">Open Innovation 2.0</span><b><br><a href=\"mailto:example@dom.ain\">example@dom.ain</a><br><a href=\"http://www.dom.ain\">www.dom.ain</a><br></b>\r\n				</p><b></b></td></tr></tbody></table></td></tr></tbody></table>";
            $template->insert();
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }

    }

    public function down()
    {
        echo "m170227_095636_base_template_records cannot be reverted.\n";

        return false;
    }

    public function init()
    {
        parent::init();
        $this->tableName = $this->db->getSchema()->getRawTableName(self::TABLE);
    }
}
