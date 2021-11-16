<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

use open20\design\assets\BootstrapItaliaDesignAsset;
$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
?>

<div class="document-list-container">
    <div class="row flex-wrap">
        
            <?= 
                $this->render(
                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-item',
                        [
                            'date' => '2012-12-12',
                            'description' => 'Lorem ipsum dolor sit amet ipsum dolor sit amet ipsum dolor sit amet',
                            'title' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                            'category' => 'Categoria di test',
                            'image' => 'https://picsum.photos/1600/900?random=25',
                            'type' => 'pdf',
                            'size' => '350',
                            'nameSurname' => 'Mario Rossi',
                            'community' => 'Community Common',
                            'url' => 'url/del/dettaglio',
                            'fileUrl' => 'url/del/documento',
                            'fileName' => 'nome_del_documento.pdf',
                            'newPubblication' => true,
                        ]
                    );
            ?>
            <?= 
                $this->render(
                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-item',
                        [
                            'date' => '2012-12-12',
                            'description' => 'Lorem ipsum dolor sit amet ipsum dolor sit amet ipsum dolor sit amet',
                            'title' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                            'category' => 'Categoria di test',
                            'image' => 'https://picsum.photos/1600/900?random=25',
                            'type' => 'pdf',
                            'size' => '350',
                            'nameSurname' => 'Mario Rossi',
                            'community' => 'Community Common',
                            'url' => 'url/del/dettaglio',
                            'fileUrl' => 'url/del/documento',
                            'fileName' => 'nome_del_documento.pdf',
                        ]
                    );
            ?>
        
    </div>
    
</div>