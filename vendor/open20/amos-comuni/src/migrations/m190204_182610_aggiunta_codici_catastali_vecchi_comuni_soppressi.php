<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */

/**
 * Class m180903_105010_aggiunta_codici_catastali_vecchi_comuni_soppressi
 * Questa migration permette di aggiungere i codici catastali dei vecchi comuni soppressi
 * nei campi della tabella amos-comuni, dato che i comuni soppressi piu' vecchi, nei file
 * ISTAT importati, non hanno l'indicazione del codice catastale.
 */
class m190204_182610_aggiunta_codici_catastali_vecchi_comuni_soppressi extends \yii\db\Migration
{

    private function updateValues($arrayVariazioni) {

        foreach($arrayVariazioni as $istatCodiciCatastali) {
            /**
             * Aggiorno il codice catastale contenuto in $istatCodiciCatastali[1]
             * per ogni comune con codice istat (id) contenuto in $istatCodiciCatastali[0]
             */
            $this->update('istat_nazioni', ['iso2' => $istatCodiciCatastali[1], 'iso3' => $istatCodiciCatastali[2]], ['id' => $istatCodiciCatastali[0]]);
        }
        $this->update('istat_nazioni', ['iso2' => 'IT', 'iso3' => 'ITA'], ['id' => 1]);

    }

    /**
     * @return bool
     */
    public function safeUp()
    {
        $this->addColumn('istat_nazioni', 'iso3', $this->string()->after('codice_catastale'));
        $this->addColumn('istat_nazioni', 'iso2', $this->string()->after('codice_catastale'));

        /**
         * Lista di comuni a cui aggiungere il relativo codice catastale.
         * Per aggiungere i codici catastali, di seguito e' presente una coppia formata da:
         * [<codice_istat>, <codice_catastale>]
         *
         * I dati sono stati estratti dal sito dell'agenzia delle entrate, il file con i
         * sorgenti (e i dati relativi a tutti i comuni, estratti il 31-08-2018) e' presente
         * in src/data-sources/comuni-agenzia-entrate.csv
         */
        $this->updateValues([
            ['100', 'IT', 'ITA'],
            ['201', 'AL', 'ALB'],
            ['202', 'AD', 'AND'],
            ['203', 'AT', 'AUT'],
            ['206', 'BE', 'BEL'],
            ['209', 'BG', 'BGR'],
            ['212', 'DK', 'DNK'],
            ['214', 'FI', 'FIN'],
            ['215', 'FR', 'FRA'],
            ['216', 'DE', 'DEU'],
            ['219', 'UK', 'GBR'],
            ['220', 'GR', 'GRC'],
            ['221', 'IE', 'IRL'],
            ['223', 'IS', 'ISL'],
            ['225', 'LI', 'LIE'],
            ['226', 'LU', 'LUX'],
            ['227', 'MT', 'MLT'],
            ['229', 'MC', 'MCO'],
            ['231', 'NO', 'NOR'],
            ['232', 'NL', 'NLD'],
            ['233', 'PL', 'POL'],
            ['234', 'PT', 'PRT'],
            ['235', 'RO', 'ROU'],
            ['236', 'SM', 'SMR'],
            ['239', 'ES', 'ESP'],
            ['240', 'SE', 'SWE'],
            ['241', 'CH', 'CHE'],
            ['243', 'UA', 'UKR'],
            ['244', 'HU', 'HUN'],
            ['245', 'RU', 'RUS'],
            ['246', 'VA', 'VAT'],
            ['247', 'EE', 'EST'],
            ['248', 'LV', 'LVA'],
            ['249', 'LT', 'LTU'],
            ['250', 'HR', 'HRV'],
            ['251', 'SI', 'SVN'],
            ['252', 'BA', 'BIH'],
            ['253', 'MK', 'MKD'],
            ['254', 'MD', 'MDA'],
            ['255', 'SK', 'SVK'],
            ['256', 'BY', 'BLR'],
            ['257', 'CZ', 'CZE'],
            ['270', 'ME', 'MNE'],
            ['271', 'RS', 'SRB'],
            ['272', NULL, 'KOS'],
            ['301', 'AF', 'AFG'],
            ['302', 'SA', 'SAU'],
            ['304', 'BH', 'BHR'],
            ['305', 'BD', 'BGD'],
            ['306', 'BT', 'BTN'],
            ['307', 'MM', 'MMR'],
            ['309', 'BN', 'BRN'],
            ['310', 'KH', 'KHM'],
            ['311', 'LK', 'LKA'],
            ['314', 'CN', 'CHN'],
            ['315', 'CY', 'CYP'],
            ['319', 'KP', 'PRK'],
            ['320', 'KR', 'KOR'],
            ['322', 'AE', 'ARE'],
            ['323', 'PH', 'PHL'],
            ['324', 'PS', 'PSE'],
            ['326', 'JP', 'JPN'],
            ['327', 'JO', 'JOR'],
            ['330', 'IN', 'IND'],
            ['331', 'ID', 'IDN'],
            ['332', 'IR', 'IRN'],
            ['333', 'IQ', 'IRQ'],
            ['334', 'IL', 'ISR'],
            ['335', 'KW', 'KWT'],
            ['336', 'LA', 'LAO'],
            ['337', 'LB', 'LBN'],
            ['338', 'TL', 'TLS'],
            ['339', 'MV', 'MDV'],
            ['340', 'MY', 'MYS'],
            ['341', 'MN', 'MNG'],
            ['342', 'NP', 'NPL'],
            ['343', 'OM', 'OMN'],
            ['344', 'PK', 'PAK'],
            ['345', 'QA', 'QAT'],
            ['346', 'SG', 'SGP'],
            ['348', 'SY', 'SYR'],
            ['349', 'TH', 'THA'],
            ['351', 'TR', 'TUR'],
            ['353', 'VN', 'VNM'],
            ['354', 'YE', 'YEM'],
            ['356', 'KZ', 'KAZ'],
            ['357', 'UZ', 'UZB'],
            ['358', 'AM', 'ARM'],
            ['359', 'AZ', 'AZE'],
            ['360', 'GE', 'GEO'],
            ['361', 'KG', 'KGZ'],
            ['362', 'TJ', 'TJK'],
            ['363', 'TW', 'TWN'],
            ['364', 'TM', 'TKM'],
            ['401', 'DZ', 'DZA'],
            ['402', 'AO', 'AGO'],
            ['404', 'CI', 'CIV'],
            ['406', 'BJ', 'BEN'],
            ['408', 'BW', 'BWA'],
            ['409', 'BF', 'BFA'],
            ['410', 'BI', 'BDI'],
            ['411', 'CM', 'CMR'],
            ['413', 'CV', 'CPV'],
            ['414', 'CF', 'CAF'],
            ['415', 'TD', 'TCD'],
            ['417', 'KM', 'COM'],
            ['418', 'CG', 'COG'],
            ['419', 'EG', 'EGY'],
            ['420', 'ET', 'ETH'],
            ['421', 'GA', 'GAB'],
            ['422', 'GM', 'GMB'],
            ['423', 'GH', 'GHA'],
            ['424', 'DJ', 'DJI'],
            ['425', 'GN', 'GIN'],
            ['426', 'GW', 'GNB'],
            ['427', 'GQ', 'GNQ'],
            ['428', 'KE', 'KEN'],
            ['429', 'LS', 'LSO'],
            ['430', 'LR', 'LBR'],
            ['431', 'LY', 'LBY'],
            ['432', 'MG', 'MDG'],
            ['434', 'MW', 'MWI'],
            ['435', 'ML', 'MLI'],
            ['436', 'MA', 'MAR'],
            ['437', 'MR', 'MRT'],
            ['438', 'MU', 'MUS'],
            ['440', 'MZ', 'MOZ'],
            ['441', 'NA', 'NAM'],
            ['442', 'NE', 'NER'],
            ['443', 'NG', 'NGA'],
            ['446', 'RW', 'RWA'],
            ['448', 'ST', 'STP'],
            ['449', 'SC', 'SYC'],
            ['450', 'SN', 'SEN'],
            ['451', 'SL', 'SLE'],
            ['453', 'SO', 'SOM'],
            ['454', 'ZA', 'ZAF'],
            ['455', 'SD', 'SDN'],
            ['456', 'SZ', 'SWZ'],
            ['457', 'TZ', 'TZA'],
            ['458', 'TG', 'TGO'],
            ['460', 'TN', 'TUN'],
            ['461', 'UG', 'UGA'],
            ['463', 'CD', 'COD'],
            ['464', 'ZM', 'ZMB'],
            ['465', 'ZW', 'ZWE'],
            ['466', 'ER', 'ERI'],
            ['467', 'SS', 'SSD'],
            ['503', 'AG', 'ATG'],
            ['505', 'BS', 'BHS'],
            ['506', 'BB', 'BRB'],
            ['507', 'BZ', 'BLZ'],
            ['509', 'CA', 'CAN'],
            ['513', 'CR', 'CRI'],
            ['514', 'CU', 'CUB'],
            ['515', 'DM', 'DMA'],
            ['516', 'DO', 'DOM'],
            ['517', 'SV', 'SLV'],
            ['518', 'JM', 'JAM'],
            ['519', 'GD', 'GRD'],
            ['523', 'GT', 'GTM'],
            ['524', 'HT', 'HTI'],
            ['525', 'HN', 'HND'],
            ['527', 'MX', 'MEX'],
            ['529', 'NI', 'NIC'],
            ['530', 'PA', 'PAN'],
            ['532', 'LC', 'LCA'],
            ['533', 'VC', 'VCT'],
            ['534', 'KN', 'KNA'],
            ['536', 'US', 'USA'],
            ['602', 'AR', 'ARG'],
            ['604', 'BO', 'BOL'],
            ['605', 'BR', 'BRA'],
            ['606', 'CL', 'CHL'],
            ['608', 'CO', 'COL'],
            ['609', 'EC', 'ECU'],
            ['612', 'GY', 'GUY'],
            ['614', 'PY', 'PRY'],
            ['615', 'PE', 'PER'],
            ['616', 'SR', 'SUR'],
            ['617', 'TT', 'TTO'],
            ['618', 'UY', 'URY'],
            ['619', 'VE', 'VEN'],
            ['701', 'AU', 'AUS'],
            ['703', 'FJ', 'FJI'],
            ['708', 'KI', 'KIR'],
            ['712', 'MH', 'MHL'],
            ['713', 'FM', 'FSM'],
            ['715', 'NR', 'NRU'],
            ['719', 'NZ', 'NZL'],
            ['720', 'PW', 'PLW'],
            ['721', 'PG', 'PNG'],
            ['725', 'SB', 'SLB'],
            ['727', 'WS', 'WSM'],
            ['730', 'TO', 'TON'],
            ['731', 'TV', 'TUV'],
            ['732', 'VU', 'VUT'],
            ['902', 'NC', 'NCL'],
            ['904', 'MF', 'MAF'],
            ['905', 'EH', 'ESH'],
            ['906', 'BL', 'BLM'],
            ['908', 'BM', 'BMU'],
            ['909', 'CK', 'COK'],
            ['910', 'GI', 'GIB'],
            ['911', 'KY', 'CYM'],
            ['917', 'AI', 'AIA'],
            ['920', 'PF', 'PYF'],
            ['924', 'FO', 'FRO'],
            ['925', 'JE', 'JEY'],
            ['926', 'AW', 'ABW'],
            ['928', 'SX', 'SXM'],
            ['934', 'GL', 'GRL'],
            ['939', NULL, NULL],
            ['940', 'GG', 'GGY'],
            ['958', 'FK', 'FLK'],
            ['959', 'IM', 'IMN'],
            ['964', 'MS', 'MSR'],
            ['966', 'CW', 'CUW'],
            ['972', 'PN', 'PCN'],
            ['980', 'PM', 'SPM'],
            ['983', 'SH', 'SHN'],
            ['988', 'TF', 'ATF'],
            ['992', 'TC', 'TCA'],
            ['994', 'VG', 'VGB'],
            ['997', 'WF', 'WLF'],
        ]);
        return true;
    }

    public function safeDown()
    {
       $this->dropColumn('istat_nazioni', 'iso2');
       $this->dropColumn('istat_nazioni', 'iso3');
    }

}