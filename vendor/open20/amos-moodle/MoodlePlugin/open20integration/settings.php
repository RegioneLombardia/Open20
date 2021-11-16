<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {

    // Create the new settings page
    // - in a local plugin this is not defined as standard, so normal $settings->methods will throw an error as
    // $settings will be NULL
    $settings = new admin_settingpage('local_open20integration', 'Integrazione Open 2.0');

    // Create 
    $ADMIN->add('localplugins', $settings);

    // Add a setting field to the settings for this page
    $settings->add(new admin_setting_configtext(
            // This is the reference you will use to your configuration
            'local_open20integration/secretkey',
            // This is the friendly title for the config, which will be displayed
            'Callback Secret Key',
            // This is helper text for this config field
            'Chiave segreta utilizzata per generare il token di autenticazione per le callback verso Open 2.0',
            // This is the default value
            '',
            // This is the type of Parameter this config is
            PARAM_TEXT
    ));
    
    $settings->add(new admin_setting_configtext(
            // This is the reference you will use to your configuration
            'local_open20integration/callbacks_url',
            // This is the friendly title for the config, which will be displayed
            'Callback Endpoint URL',
            // This is helper text for this config field
            'URL per le callback verso Open 2.0',
            // This is the default value
            '',
            // This is the type of Parameter this config is
            PARAM_TEXT
    ));
    
    $settings->add(new admin_setting_configtext(
            // This is the reference you will use to your configuration
            'local_open20integration/oauth_issuerid',
            // This is the friendly title for the config, which will be displayed
            'ID Open 2.0 OAuth issuer',
            // This is helper text for this config field
            'ID issuer OAuth Open2.0 configurato su Moodle',
            // This is the default value
            '',
            // This is the type of Parameter this config is
            PARAM_INT
    ));
}

