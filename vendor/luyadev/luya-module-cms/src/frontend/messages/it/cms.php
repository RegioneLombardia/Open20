<?php

return [
    'tb_composition' => 'Lingua',
    'tb_properties' => 'Proprietà',
    'tb_seo' => 'Ottimizzazione per i motori di ricerca',
    'tb_seo_title' => 'Titolo',
    'tb_seo_description' => 'Descrizione',
    'tb_seo_description_notfound' => 'Non è stata trovata nessuna descrizione per questa pagina!',
    'tb_seo_link' => 'Collegamento URL',
    'tb_seo_keywords' => 'Parole chiave',
    'tb_seo_keywords_notfound' => 'Nessuna parola chiave trovata! E\' necessario aggiungere delle parole chiave per analizzare il contenuto.',
    'tb_seo_warning' => 'Alcune delle tue parole chiave non sono state trovate nel contenuto, bisogna cambiare le tue parole chiave oppure aggiungere del contenuto che contenga le tue parole chiave',
    'tb_edit_alt' => 'Modifica questa pagina nell\'interfaccia del CMS',
    'tb_visible_not_alt' => 'Questa pagina NON è visibile ai visitatori',
    'tb_visible_alt' => 'Questa pagina è visibile ai visitatori',
    'block_html_html_label' => 'codice HTML',
    'block_html_no_content' => 'Non è stato aggiunto ancora alcun codice HTML.',
    'block_module_name' => 'Modulo',
    'block_module_modulename_label' => 'Nome del modulo',
    'block_module_modulecontroller_label' => 'Nome del Controller (senza il suffisso controller)',
    'block_module_moduleaction_label' => 'Nome dell\'Action (senza il suffisso action)',
    'block_module_moduleactionargs_label' => 'Argomenti dell\'Action (json: {"var":"value"})',
    'block_module_no_module' => 'Non è stato fornito alcun modulo.',
    'block_module_integration' => 'Integrazione modulo',
    'block_html_name' => 'HTML',
    'block_module_modulename_help' => 'Sono elencati solo i moduli che sono registrati nel file di configurazione.',
    'block_group_dev_elements' => 'Sviluppo',
    'block_group_layout_elements' => 'Layout',
    'block_group_basic_elements' => 'Elementi base',
    'block_group_project_elements' => 'Progetto',
    'block_group_text_elements' => 'Testi',
    'block_group_media_group' => 'Media',
    
    // 1.0.0
    'block_module_strictrender' => 'Rendering stretto',
    'block_module_strictrender_help' => 'Quando il rendering stretto è abilitato, il modulo eseguirà solo le rott previste (module, controller, action, params) senza elaborare azioni e rotte del controller.',
    'block_html_cfg_raw_label' => 'Rendering HTML nell\'Admin',

    // 3.4.0
    'tag_alias_readme' => 'The alias tag allows you to use aliases defined in your application as well as predefined aliases. As an example, you can use `alias[@web]` to link to images in the public html folder: `<img src="alias[@web]/image.jpg">`',
    'tag_menu_readme' => 'Generate a link to a menu item where the key is the page id (you can see the page ids when hovering over the site navigation in the administration).',
    'tag_page_readme' => 'Get the content of a full page or of a placeholder of a page. The first parameter is the page id (which you get by hovering over the site navigation in the administration): `page[1]`. If you only want to get the content of a placeholder of the cmslayout, use the second parameter: `page[1](placeholderName)`.',
    'block_mirror_language_name' => 'Mirror Language',
    'block_mirror_config_language_label' => 'Source Language',
    'block_mirror_admin_empty_language' => 'Configure a <b><span class="material-icons">edit</span> source language</b> to mirror its content for the current placeholder.',
    'block_mirror_admin_configured_language' => 'Mirroring this placeholder from <span class="material-icons">arrow_right_alt</span> <b>{name}</b>.',
];
