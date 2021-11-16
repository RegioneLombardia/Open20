<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

namespace open20\amos\emailmanager\interfaces;

interface ManagerInterface
{
    public function setTemplateType($templateType);

    public function getTemplateType();

    public function setTemplatePath($path);

    public function getTemplatePath();

    public function setDefaultTemplate($tamplate);

    public function getDefaultTemplate();

    public function setDefaultLayout($layout);

    public function getDefaultLayout();
}
