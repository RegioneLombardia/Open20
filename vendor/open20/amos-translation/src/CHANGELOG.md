# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
- Added configuration to enable  to send cookie without security
- Added configuration to set cookie for the second level domain
### Added
- Automatic configuration of the fields of the db reading from an attribute of the AmosModule valued in the plugins installed on the platform
- Added default language to translate when the record in the translation request is empty
- Added rollback for the source record if the language to translate is not the same of the default language
- Handled the exception in case of missing model translations
- Added params to disable translation user permission on the translation record
- Added params enableLabelTranslationField, if set to true it enables the display of the translatable fields in the forms
- Added params templateTranslationField, template of translation field in the form, near the label
- Added params templateTranslationAltField, template of translation alt field in the form, near the $templateTranslationField
- Added params translationLabelField, this string will be parsed by the "eval()" function instead of $tempalteTransaltionField, by default {translation}
- Added params translationLabelAltField, this string will be parsed by the "eval()" function instead of $templateTranslationAltField, by default {altTranslation}
- Added params labelTranslationField, this string is the html code that will be used in the labels to represent a translatable field    