<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\report\migrations
 * @category   Migration
 */

use open20\amos\core\migration\AmosMigrationTranslations;

/**
 * Class m170615_132412_add_report_translations_IT
 */
class m170615_132412_add_report_translations_IT extends AmosMigrationTranslations
{
    /**
     * @inheritdoc
     */
    protected function setTranslations()
    {
        return [
            self::LANG_IT => [
                [
                    'category' => 'amoscore',
                    'source' => 'Reports',
                    'translation' => 'Segnalazioni'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Errors',
                    'translation' => 'Errori'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Inappropriate contents',
                    'translation' => 'Contenuti inappropriati'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Your report has been correctly sent.',
                    'translation' => 'La tua segnalazione è stata inviata correttamente.'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Close',
                    'translation' => 'Chiudi'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Error occured while creating the report. Please, try again later.',
                    'translation' => 'Errore durante la creazione della segnalazione. Si prega di riprovare più tardi.'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'All reports',
                    'translation' => 'Tutte le segnalazioni'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'content',
                    'translation' => 'contenuto'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'sent a report for the',
                    'translation' => 'ha inviato una segnalazione per il'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'with the following information:',
                    'translation' => 'con le seguenti informazioni:'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'You are receiving this notification because you are the creator/validator of the content',
                    'translation' => 'Ricevi questa notifica in quanto creatore/validatore del contenuto'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Context ID',
                    'translation' => 'ID contenuto'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Report Type',
                    'translation' => 'Tipo Segnalazione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Description',
                    'translation' => 'Descrizione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'report status',
                    'translation' => 'stato segnalazione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Report creator ID',
                    'translation' => 'ID creatore segnalazione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Report read at',
                    'translation' => 'Data lettura segnalazione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Report read by user',
                    'translation' => 'Utente lettore segnalazione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Created at',
                    'translation' => 'Data creazione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Modified at',
                    'translation' => 'Data modifica'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Deleted at',
                    'translation' => 'Data cancellazione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Created by',
                    'translation' => 'Creato da'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Modified by',
                    'translation' => 'Modificato da'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Deleted by',
                    'translation' => 'Cancellato da'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Report Type name',
                    'translation' => 'Nome tipo segnalazione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Report Type description',
                    'translation' => 'Descrizione tipo segnalazione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Report',
                    'translation' => 'Segnalazione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Enter name of the report type',
                    'translation' => 'Inserisci il nome del tipo di segnalazione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Description...',
                    'translation' => 'Descrizione...'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'The fields marked with ',
                    'translation' => 'I campi contrassegnati con '
                ],
                [
                    'category' => 'amosreport',
                    'source' => ' are required',
                    'translation' => ' sono obbligatori'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Send',
                    'translation' => 'Invia'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Cancel',
                    'translation' => 'Annulla'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Missing Model',
                    'translation' => 'Modello Mancante'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Missing Context Id',
                    'translation' => 'Id del contenuto mancante'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'You can report errors or contents that you consider inappropriate and, if necessary, ask for correction',
                    'translation' => 'Puoi segnalare errori o contenuti che ritieni inappropriati e, se necessario, chiedere una rettifica'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Specify below the type and the description of your report.<br/>You can report errors or contents that you consider inappropriate and, if necessary, ask for correction.',
                    'translation' => 'Indica di seguito il tipo e la descrizione della tua segnalazione.<br/>Puoi segnalare errori o contenuti che ritieni non appropriati e richiedere una rettifica, se necessario.'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Max. 300 characters',
                    'translation' => 'Max. 300 caratteri'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Content title',
                    'translation' => 'Titolo del contenuto'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Photo',
                    'translation' => 'Foto'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Name',
                    'translation' => 'Nome'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'name',
                    'translation' => 'nome'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Report type',
                    'translation' => 'Tipo segnalazione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Read confirmation',
                    'translation' => 'Conferma lettura'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Report read confirmation',
                    'translation' => 'Conferma lettura segnalazione'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'The report has been updated',
                    'translation' => 'La segnalazione è stata aggiornata'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Do you confirm report read?',
                    'translation' => 'Confermi la lettura della segnalazione?'
                ],
                [
                    'category' => 'amosreport',
                    'source' => 'Confirm',
                    'translation' => 'Conferma'
                ],
            ]
        ];
    }
}
