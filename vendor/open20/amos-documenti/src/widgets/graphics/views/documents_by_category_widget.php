<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsByCategory;

/**
 * @var yii\web\View $this
 * @var WidgetGraphicsDocumentsByCategory $widget
 */

echo $this->render('fullsize/documents_by_category_widget', [
    'widget' => $widget,
]);
