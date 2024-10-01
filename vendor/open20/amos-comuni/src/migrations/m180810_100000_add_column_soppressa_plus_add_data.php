<?php
use open20\amos\core\migration\AmosMigrationPermissions;


/**
 * Class m180810_100000_add_column_soppressa_plus_add_data*/
class m180810_100000_add_column_soppressa_plus_add_data extends AmosMigrationPermissions
{
    public function safeUp() {

        /**
         * Adding columns 'data_soppressione' and 'soppressa'
         */
        $this->addColumn('istat_nazioni','data_soppressione', $this->date()->comment('Data soppressione o variazione'));
        $this->addColumn('istat_nazioni','soppressa', $this->boolean()->notNull()->defaultValue(0)->comment('Nazione soppressa o variata'));

        /**
         * Adding a new continent for ISTAT 'codici catastali dipendenze'
         */
        $this->insert('istat_continenti', [
            'id' => 7,
            'nome' => 'Dipendenze',
        ]);

        /**
         * Data manually extracted from data-sources/amministrazionicomunali_net-ESTERI.xls file
         * Source: https://www.amministrazionicomunali.net/docs/file/ESTERI.XLS (2018-08-18 10:10AM)
         */
        $this->batchInsert(
            "istat_nazioni",
            [ 'id',  'nome',  'nome_inglese',  'codice_catastale',  'istat_continenti_id', 'data_soppressione', 'soppressa'],
            [
                [ '2000000',  'Cecoslovacchia',  'Czechoslovakia',  'Z105',  1, '1993-01-01', 1],
                [ '2000001',  'Germania Repubblica Democratica',  'German Democratic Republic',  'Z111',  1, '1990-10-03', 1],
                [ '2000002',  'Germania Repubblica Federale',  'Federal Republic of Germany',  'Z112',  1, '1990-10-03', 1],
                [ '2000003',  'Iugoslavia',  'Yugoslavia',  'Z118',  1,  '2003-01-04', 1],
                [ '2000004',  'U.R.S.S. - Unione Repubbliche Socialiste Sovietiche',  'U.S.S.R. - Union of Soviet Socialist Republics',  'Z135',  1, '1992-03-31', 1],
                [ '2000005',  'Georgia',  'Georgia',  'Z135',  3, '1994-01-01', 1],
                [ '2000006',  'Azerbaigian',  'Azerbaijan',  'Z141',  3, '1994-01-01', 1],
                [ '2000007',  'Kirghizistan',  'Kyrgyzstan',  'Z142',  3, '1994-01-01', 1],
                [ '2000008',  'Tagikistan',  'Tajikistan',  'Z147',  3, '1994-01-01', 1],
                [ '2000009',  'Turkmenistan',  'Turkmenistan',  'Z151',  3, '1994-01-01', 1],
                [ '2000010',  'Serbia e Montenegro',  'Serbia and Montenegro',  'Z157',  3, '2006-06-02', 1],
                [ '2000011',  'Arabia Meridionale, Federazione',  'South Arabia, Federation of',  'Z201',  3, '1975-12-31', 1],
                [ '2000012',  'Arabia Meridionale, Protettorato',  'South Arabia, Protectorate of',  'Z202',  3, '1975-12-31', 1],
                [ '2000013',  'Malaysia',  'Malaysia',  'Z230',  3, '1965-08-09', 1],
                [ '2000014',  'Ryukyu (Isole)',  'Ryukyu (Islands)',  'Z238',  3, '1972-05-22', 1],
                [ '2000015',  'Sikkim',  'Sikkim',  'Z239',  3, '1974-06-28', 1],
                [ '2000016',  'Timor (Isola)',  'Timor (Island)',  'Z242',  3, '1976-06-01', 1],
                [ '2000017',  'Vietnam del Sud',  'South Vietnam',  'Z244',  3, '1976-04-25', 1],
                [ '2000040',  'Vietnam del Nord',  'North Vietnam',  'Z245',  3, '1976-04-25', 1],
                [ '2000018',  'Yemen, Repubblica Democratica Popolare',  'People\'s Democratic Republic of Yemen',  'Z250',  3, '1990-05-22', 1],
                [ '2000019',  'Africa del Sud-Ovest',  'South West Africa',  'Z300',  2, '1990-03-21', 1],
                [ '2000020',  'Basutoland',  'Basutoland',  'Z303',  2, '1966-10-04', 1],
                [ '2000021',  'Beciuania (Botswana)',  'Botswana',  'Z304',  2, '1966-09-30', 1],
                [ '2000022',  'IFNI',  'IFNI',  'Z323',  2, '1984-12-31', 1],
                [ '2000023',  'Sahara Spagnolo',  'Spanish Sahara',  'Z339',  2, '1976-01-01', 1],
                [ '2000024',  'Territorio francese degli Afar e degli Issa',  'French Territory of the Afars and the Issas',  'Z346',  2, '1977-06-27', 1],
                [ '2000025',  'Somalia Francese',  'French Somaliland',  'Z346',  2, '1975-12-31', 1],
                [ '2000026',  'Tanganica',  'Tanganyika',  'Z350',  2, '1964-04-25', 1],
                [ '2000027',  'Zanzibar',  'Zanzibar',  'Z356',  2, '1964-04-25', 1],
                [ '2000028',  'Sahara Meridionale',  'South Sahara',  'Z362',  2, '1976-01-01', 1],
                [ '2000029',  'Sahara Settentrionale',  'North Sahara',  'Z363',  2, '1976-01-01', 1],
                [ '2000030',  'Bophuthatswana',  'Bophuthatswana',  'Z364',  2, '1994-01-01', 1],
                [ '2000031',  'Transkei',  'Transkei',  'Z365',  2, '1994-01-01', 1],
                [ '2000032',  'Venda',  'Venda',  'Z366',  2, '1994-01-01', 1],
                [ '2000033',  'Ciskei',  'Ciskei',  'Z367',  2, '1994-01-01', 1],
                [ '2000034',  'Nyasaland',  'Nyasaland',  'Z369',  2, '1964-07-05', 1],
                [ '2000035',  'Congo Belga',  'Belgian Congo',  'Z370',  2, '1960-01-01', 1],
                [ '2000036',  'Antille Britanniche (Prima del 30-11-1966)',  'British Antilles (Before 1966-11-30)',  'Z500',  4, '1975-12-31', 1],
                [ '2000037',  'Antille Olandesi',  'Dutch Antilles',  'Z501',  4, '1973-07-10', 1],
                [ '2000038',  'Panama Zona Del Canale',  'Panama Canal Zone',  'Z517',  4, '1977-09-07', 1],
                [ '2000039',  'Antille Britanniche (Tra 30-11-1966 e 07-02-1974)',  'British Antilles (Between 30-11-1966 and 07-02-1974)',  'Z521',  4, '1974-02-07', 1],
                [ '2000041',  'Antille Britanniche',  'British Antilles',  'Z523',  4, '1983-09-19', 1],
                [ '2000042',  'Guyana Olandese',  'Dutch Guyana',  'Z608',  4, '1975-12-31', 1],
                [ '2000043',  'Guyana Britannica',  'British Guyana',  'Z606',  4, '1975-12-31', 1],
                [ '2000044',  'Caroline (Isole)',  'Caroline (Islands)',  'Z701',  4, '1984-12-31', 1],
                [ '2000045',  'Gilbert e Ellice (Isole)',  'Gilbert and Ellice Islands',  'Z705',  5, '1980-12-31', 1],
                [ '2000046',  'Marcus (Isole)',  'Marcus (Islands)',  'Z705',  5, '1975-12-31', 1],
                [ '2000047',  'Nuove Ebridi (Isole Condominio Franco-Inglese)',  'New Hebrides',  'Z717',  5, '1980-12-31', 1],
                [ '2000048',  'Nuova Guinea (Prima del 31-12-1975)',  'New Guinea (Before 1975-12-31)',  'Z718',  5, '1975-12-31', 1],
                [ '2000049',  'Papuasia',  'Papuasia', 'Z720',  5, '1975-12-31', 1],
                [ '2000050',  'Dipendenze Canadesi',  'Canada Dependent', 'Z800',  7, null, 0],
                [ '2000051',  'Dipendenze Norvegesi Artiche',  'Norwegian Artic Dependent', 'Z801',  7, null, 0],
                [ '2000052',  'Dipendenze Sovietiche',  'Soviet Union Dependent',  'Z802', 7, '1992-03-31', 1],
                [ '2000053',  'Dipendenze Russe',  'Russia Dependent',  'Z802', 7, null, 0],
                [ '2000054',  'Dipendenze Australiane',  'Australia Dependent',  'Z900', 7, null, 0],
                [ '2000055',  'Dipendenze Britanniche',  'Britain Dependent',  'Z901', 7, null, 0],
                [ '2000056',  'Dipendenze Francesi',  'France Dependent',  'Z902', 7, null, 0],
                [ '2000057',  'Dipendenze Neozelandesi',  'New Zealand Dependent',  'Z903', 7, null, 0],
                [ '2000058',  'Dipendenze Norvegesi Antartiche',  'Norwegian Antartica Dependent',  'Z904', 7, null, 0],
                [ '2000059',  'Dipendenze Statunitensi',  'US Dependent',  'Z905', 7, null, 0],
                [ '2000060',  'Dipendenze Sudafricane',  'South Africa Dependent',  'Z906', 7, null, 0],
            ]
        );

        return true;

    }

    public function safeDown() {

        /**
         * This will remove all data and columns inserted above
         */

        for ($i=2000000;$i<=2000060;$i++) {
            $this->delete(
                "istat_nazioni",
                [ "id" => $i ]
            );
        }

        $this->delete('istat_continenti', ['id' => 7]);

        $this->dropColumn('istat_nazioni', 'soppressa');
        $this->dropColumn('istat_nazioni', 'data_soppressione');

        return true;
    }

}