<?php
// This file is called on plugin activation

	// get current options
	$localcurrency_options = get_option('localcurrency_options');
	
	// set the settings as an option
	if (!isset($localcurrency_options['site_currency'])){ $localcurrency_options['site_currency'] = 'USD';}
	if (!isset($localcurrency_options['link_on'])){ $localcurrency_options['link_on'] = 'true';}
	if (!isset($localcurrency_options['exchange_rates'])){ $localcurrency_options['exchange_rates'] = null;}
	if (!isset($localcurrency_options['last_updated'])){ $localcurrency_options['last_updated'] = (time() - 86400);}
	if (!isset($localcurrency_options['hide_base_price'])){ $localcurrency_options['hide_base_price'] = 'false';}
	if (!isset($localcurrency_options['freeze_prices'])){ $localcurrency_options['freeze_prices'] = 'false';}
	if (!isset($localcurrency_options['debug'])){ $localcurrency_options['debug'] = 'false';}
	if (!isset($localcurrency_options['priority'])){ $localcurrency_options['priority'] = 10;}
	
	// create the currency list array and set that as an option
	$lc_currencylist = array();
	$lc_currencylist['USD'] = array('name'=>'U.S. Dollar (USD)', 'symbol'=>'$');
	$lc_currencylist['GBP'] = array('name'=>'British Pound (GBP)', 'symbol'=>'&#163;');
	$lc_currencylist['EUR'] = array('name'=>'Euro (EUR)', 'symbol'=>'&#8364;');
	$lc_currencylist['AUD'] = array('name'=>'Australian Dollar (AUD)', 'symbol'=>'$');
	$lc_currencylist['CAD'] = array('name'=>'Canadian Dollar (CAD)', 'symbol'=>'$');
	$lc_currencylist['NZD'] = array('name'=>'New Zealand Dollar (NZD)', 'symbol'=>'$');
	$lc_currencylist['CNY'] = array('name'=>'Chinese Yuan (CNY)', 'symbol'=>'&#20803;');
	$lc_currencylist['JPY'] = array('name'=>'Japanese Yen (JPY)', 'symbol'=>'&#165;');
	$lc_currencylist['RUB'] = array('name'=>'Russian Rouble (RUB)', 'symbol'=>'&#1088;&#1091;&#1073;');
	$lc_currencylist['---'] = array('name'=>'-------------------', 'symbol'=>'---');
	$lc_currencylist['AED'] = array('name'=>'UAE Dirham (AED)', 'symbol'=>'&#1583;.&#1573;');
	$lc_currencylist['ANG'] = array('name'=>'Neth Antilles Guilder (ANG)', 'symbol'=>'&#402;');
	$lc_currencylist['ARS'] = array('name'=>'Argentine Peso (ARS)', 'symbol'=>'$');
	$lc_currencylist['AWG'] = array('name'=>'Aruba Florin (AWG)', 'symbol'=>'&#402;');
	$lc_currencylist['BBD'] = array('name'=>'Barbados Dollar (BBD)', 'symbol'=>'$');
	$lc_currencylist['BDT'] = array('name'=>'Bangladesh Taka (BDT)', 'symbol'=>'');
	$lc_currencylist['BGN'] = array('name'=>'Bulgarian Lev (BGN)', 'symbol'=>'&#1083;&#1074;');
	$lc_currencylist['BHD'] = array('name'=>'Bahraini Dinar (BHD)', 'symbol'=>'&#1576;.&#1583;');
	$lc_currencylist['BIF'] = array('name'=>'Burundi Franc (BIF)', 'symbol'=>'Fr');
	$lc_currencylist['BMD'] = array('name'=>'Bermuda Dollar (BMD)', 'symbol'=>'$');
	$lc_currencylist['BND'] = array('name'=>'Brunei Dollar (BND)', 'symbol'=>'$');
	$lc_currencylist['BOB'] = array('name'=>'Bolivian Boliviano (BOB)', 'symbol'=>'$b');
	$lc_currencylist['BRL'] = array('name'=>'Brazilian Real (BRL)', 'symbol'=>'R$');
	$lc_currencylist['BSD'] = array('name'=>'Bahamian Dollar (BSD)', 'symbol'=>'$');
	$lc_currencylist['BTN'] = array('name'=>'Bhutan Ngultrum (BTN)', 'symbol'=>'');
	$lc_currencylist['BYR'] = array('name'=>'Belarus Ruble (BYR)', 'symbol'=>'p.');
	$lc_currencylist['BZD'] = array('name'=>'Belize Dollar (BZD)', 'symbol'=>'$');
	$lc_currencylist['CHF'] = array('name'=>'Swiss Franc (CHF)', 'symbol'=>'CHF');
	$lc_currencylist['CLP'] = array('name'=>'Chilean Peso (CLP)', 'symbol'=>'$');
	$lc_currencylist['COP'] = array('name'=>'Colombian Peso (COP)', 'symbol'=>'$');
	$lc_currencylist['CRC'] = array('name'=>'Costa Rica Colon (CRC)', 'symbol'=>'&#8353;');
	$lc_currencylist['CUP'] = array('name'=>'Cuban Peso (CUP)', 'symbol'=>'&#8369;');
	$lc_currencylist['CYP'] = array('name'=>'Cyprus Pound (CYP)', 'symbol'=>'&#163;');
	$lc_currencylist['CZK'] = array('name'=>'Czech Koruna (CZK)', 'symbol'=>'K&#269;');
	$lc_currencylist['DJF'] = array('name'=>'Dijibouti Franc (DJF)', 'symbol'=>'Fr');
	$lc_currencylist['DKK'] = array('name'=>'Danish Krone (DKK)', 'symbol'=>'kr');
	$lc_currencylist['DOP'] = array('name'=>'Dominican Peso (DOP)', 'symbol'=>'RD$');
	$lc_currencylist['DZD'] = array('name'=>'Algerian Dinar (DZD)', 'symbol'=>'&#1583;.&#1580;');
	$lc_currencylist['ECS'] = array('name'=>'Ecuador Sucre (ECS)', 'symbol'=>'');
	$lc_currencylist['EEK'] = array('name'=>'Estonian Kroon (EEK)', 'symbol'=>'kr');
	$lc_currencylist['EGP'] = array('name'=>'Egyptian Pound (EGP)', 'symbol'=>'&#163;');
	$lc_currencylist['ERN'] = array('name'=>'Eritrea Nakfa (ERN)', 'symbol'=>'Nfk');
	$lc_currencylist['ETB'] = array('name'=>'Ethiopian Birr (ETB)', 'symbol'=>'');
	$lc_currencylist['FKP'] = array('name'=>'Falkland Islands Pound (FKP)', 'symbol'=>'&#163;');
	$lc_currencylist['GHC'] = array('name'=>'Ghanian Cedi (GHC)', 'symbol'=>'&#162;');
	$lc_currencylist['GIP'] = array('name'=>'Gibraltar Pound (GIP)', 'symbol'=>'&#163;');
	$lc_currencylist['GMD'] = array('name'=>'Gambian Dalasi (GMD)', 'symbol'=>'D');
	$lc_currencylist['GNF'] = array('name'=>'Guinea Franc (GNF)', 'symbol'=>'Fr');
	$lc_currencylist['GTQ'] = array('name'=>'Guatemala Quetzal (GTQ)', 'symbol'=>'Q');
	$lc_currencylist['HKD'] = array('name'=>'Hong Kong Dollar (HKD)', 'symbol'=>'HK$');
	$lc_currencylist['HNL'] = array('name'=>'Honduras Lempira (HNL)', 'symbol'=>'L');
	$lc_currencylist['HRK'] = array('name'=>'Croatian Kuna (HRK)', 'symbol'=>'kn');
	$lc_currencylist['HTG'] = array('name'=>'Haiti Gourde (HTG)', 'symbol'=>'G');
	$lc_currencylist['HUF'] = array('name'=>'Hungarian Forint (HUF)', 'symbol'=>'Ft');
	$lc_currencylist['IDR'] = array('name'=>'Indonesian Rupiah (IDR)', 'symbol'=>'Rp');
	$lc_currencylist['ILS'] = array('name'=>'Israeli Shekel (ILS)', 'symbol'=>'&#8362;');
	$lc_currencylist['INR'] = array('name'=>'Indian Rupee (INR)', 'symbol'=>'&#8360;');
	$lc_currencylist['IRR'] = array('name'=>'Iran Rial (IRR)', 'symbol'=>'&#65020;');
	$lc_currencylist['ISK'] = array('name'=>'Iceland Krona (ISK)', 'symbol'=>'kr');
	$lc_currencylist['JMD'] = array('name'=>'Jamaican Dollar (JMD)', 'symbol'=>'J$');
	$lc_currencylist['JOD'] = array('name'=>'Jordanian Dinar (JOD)', 'symbol'=>'&#1583;.&#1575;');
	$lc_currencylist['KES'] = array('name'=>'Kenyan Shilling (KES)', 'symbol'=>'Sh');
	$lc_currencylist['KHR'] = array('name'=>'Cambodia Riel (KHR)', 'symbol'=>'&#6107;');
	$lc_currencylist['KMF'] = array('name'=>'Comoros Franc (KMF)', 'symbol'=>'Fr');
	$lc_currencylist['KRW'] = array('name'=>'Korean Won (KRW)', 'symbol'=>'&#8361;');
	$lc_currencylist['KWD'] = array('name'=>'Kuwaiti Dinar (KWD)', 'symbol'=>'&#1583;.&#1603;');
	$lc_currencylist['KYD'] = array('name'=>'Cayman Islands Dollar (KYD)', 'symbol'=>'$');
	$lc_currencylist['KZT'] = array('name'=>'Kazakhstan Tenge (KZT)', 'symbol'=>'&#1083;&#1074;');
	$lc_currencylist['LAK'] = array('name'=>'Lao Kip (LAK)', 'symbol'=>'&#8365;');
	$lc_currencylist['LBP'] = array('name'=>'Lebanese Pound (LBP)', 'symbol'=>'&#163;');
	$lc_currencylist['LKR'] = array('name'=>'Sri Lanka Rupee (LKR)', 'symbol'=>'&#8360;');
	$lc_currencylist['LSL'] = array('name'=>'Lesotho Loti (LSL)', 'symbol'=>'L');
	$lc_currencylist['LTL'] = array('name'=>'Lithuanian Lita (LTL)', 'symbol'=>'Lt');
	$lc_currencylist['LVL'] = array('name'=>'Latvian Lat (LVL)', 'symbol'=>'Ls');
	$lc_currencylist['LYD'] = array('name'=>'Libyan Dinar (LYD)', 'symbol'=>'&#1604;.&#1583;');
	$lc_currencylist['MAD'] = array('name'=>'Moroccan Dirham (MAD)', 'symbol'=>'&#1583;.&#1605;.');
	$lc_currencylist['MDL'] = array('name'=>'Moldovan Leu (MDL)', 'symbol'=>'L');
	$lc_currencylist['MGF'] = array('name'=>'Malagasy Franc (MGF)', 'symbol'=>'Fr');
	$lc_currencylist['MKD'] = array('name'=>'Macedonian Denar (MKD)', 'symbol'=>'&#1076;&#1077;&#1085;');
	$lc_currencylist['MNT'] = array('name'=>'Mongolian Tugrik (MNT)', 'symbol'=>'&#8366;');
	$lc_currencylist['MOP'] = array('name'=>'Macau Pataca (MOP)', 'symbol'=>'P');
	$lc_currencylist['MRO'] = array('name'=>'Mauritania Ougulya (MRO)', 'symbol'=>'UM');
	$lc_currencylist['MTL'] = array('name'=>'Maltese Lira (MTL)', 'symbol'=>'Lm');
	$lc_currencylist['MUR'] = array('name'=>'Mauritius Rupee (MUR)', 'symbol'=>'&#8360;');
	$lc_currencylist['MVR'] = array('name'=>'Maldives Rufiyaa (MVR)', 'symbol'=>'&#1923;.');
	$lc_currencylist['MWK'] = array('name'=>'Malawi Kwacha (MWK)', 'symbol'=>'MK');
	$lc_currencylist['MXN'] = array('name'=>'Mexican Peso (MXN)', 'symbol'=>'$');
	$lc_currencylist['MYR'] = array('name'=>'Malaysian Ringgit (MYR)', 'symbol'=>'RM');
	$lc_currencylist['MZN'] = array('name'=>'Mozambique Metical (MZM)', 'symbol'=>'MTn');
	$lc_currencylist['NAD'] = array('name'=>'Namibian Dollar (NAD)', 'symbol'=>'$');
	$lc_currencylist['NGN'] = array('name'=>'Nigerian Naira (NGN)', 'symbol'=>'&#8358;');
	$lc_currencylist['NIO'] = array('name'=>'Nicaragua Cordoba (NIO)', 'symbol'=>'C$');
	$lc_currencylist['NOK'] = array('name'=>'Norwegian Krone (NOK)', 'symbol'=>'kr');
	$lc_currencylist['NPR'] = array('name'=>'Nepalese Rupee (NPR)', 'symbol'=>'&#8360;');
	$lc_currencylist['OMR'] = array('name'=>'Omani Rial (OMR)', 'symbol'=>'&#1585;.&#1593;.');
	$lc_currencylist['PAB'] = array('name'=>'Panama Balboa (PAB)', 'symbol'=>'B/.');
	$lc_currencylist['PEN'] = array('name'=>'Peruvian Nuevo Sol (PEN)', 'symbol'=>'S/.');
	$lc_currencylist['PGK'] = array('name'=>'Papua New Guinea Kina (PGK)', 'symbol'=>'K');
	$lc_currencylist['PHP'] = array('name'=>'Philippine Peso (PHP)', 'symbol'=>'Php');
	$lc_currencylist['PKR'] = array('name'=>'Pakistani Rupee (PKR)', 'symbol'=>'&#8360;');
	$lc_currencylist['PLN'] = array('name'=>'Polish Zloty (PLN)', 'symbol'=>'z&#322;');
	$lc_currencylist['PYG'] = array('name'=>'Paraguayan Guarani (PYG)', 'symbol'=>'Gs');
	$lc_currencylist['QAR'] = array('name'=>'Qatar Rial (QAR)', 'symbol'=>'&#1585;.&#1602;');
	$lc_currencylist['RON'] = array('name'=>'Romanian New Leu (RON)', 'symbol'=>'lei');
	$lc_currencylist['RWF'] = array('name'=>'Rwanda Franc (RWF)', 'symbol'=>'Fr');
	$lc_currencylist['SAR'] = array('name'=>'Saudi Arabian Riyal (SAR)', 'symbol'=>'&#1585;.&#1587;');
	$lc_currencylist['SCR'] = array('name'=>'Seychelles Rupee (SCR)', 'symbol'=>'&#8360;');
	$lc_currencylist['SDG'] = array('name'=>'Sudanese Dinar (SDD)', 'symbol'=>'&#163;');
	$lc_currencylist['SEK'] = array('name'=>'Swedish Krona (SEK)', 'symbol'=>'kr');
	$lc_currencylist['SGD'] = array('name'=>'Singapore Dollar (SGD)', 'symbol'=>'$');
	$lc_currencylist['SHP'] = array('name'=>'St Helena Pound (SHP)', 'symbol'=>'&#163;');
	$lc_currencylist['SKK'] = array('name'=>'Slovak Koruna (SKK)', 'symbol'=>'SIT');
	$lc_currencylist['SLL'] = array('name'=>'Sierra Leone Leone (SLL)', 'symbol'=>'Le');
	$lc_currencylist['SOS'] = array('name'=>'Somali Shilling (SOS)', 'symbol'=>'S');
	$lc_currencylist['SRD'] = array('name'=>'Surinam Guilder (SRG)', 'symbol'=>'$');
	$lc_currencylist['STD'] = array('name'=>'Sao Tome Dobra (STD)', 'symbol'=>'Db');
	$lc_currencylist['SVC'] = array('name'=>'El Salvador Colon (SVC)', 'symbol'=>'$');
	$lc_currencylist['SYP'] = array('name'=>'Syrian Pound (SYP)', 'symbol'=>'&#163;');
	$lc_currencylist['SZL'] = array('name'=>'Swaziland Lilageni (SZL)', 'symbol'=>'L');
	$lc_currencylist['THB'] = array('name'=>'Thai Baht (THB)', 'symbol'=>'&#3647;');
	$lc_currencylist['TND'] = array('name'=>'Tunisian Dinar (TND)', 'symbol'=>'&#1583;.&#1578;');
	$lc_currencylist['TOP'] = array('name'=>'Tonga Paanga (TOP)', 'symbol'=>'T$');
	$lc_currencylist['TRY'] = array('name'=>'New Turkish Lira (TRY)', 'symbol'=>'YTL');
	$lc_currencylist['TTD'] = array('name'=>'Trinidad&Tobago Dollar (TTD)', 'symbol'=>'TT$');
	$lc_currencylist['TWD'] = array('name'=>'Taiwan Dollar (TWD)', 'symbol'=>'$');
	$lc_currencylist['TZS'] = array('name'=>'Tanzanian Shilling (TZS)', 'symbol'=>'Sh');
	$lc_currencylist['UAH'] = array('name'=>'Ukraine Hryvnia (UAH)', 'symbol'=>'&#8372;');
	$lc_currencylist['UGX'] = array('name'=>'Ugandan Shilling (UGX)', 'symbol'=>'Sh');
	$lc_currencylist['UYU'] = array('name'=>'Uruguayan New Peso (UYU)', 'symbol'=>'$U');
	$lc_currencylist['VEB'] = array('name'=>'Venezuelan Bolivar (VEB)', 'symbol'=>'Bs');
	$lc_currencylist['VND'] = array('name'=>'Vietnam Dong (VND)', 'symbol'=>'&#8363;');
	$lc_currencylist['VUV'] = array('name'=>'Vanuatu Vatu (VUV)', 'symbol'=>'Vt');
	$lc_currencylist['WST'] = array('name'=>'Samoa Tala (WST)', 'symbol'=>'T');
	$lc_currencylist['XAF'] = array('name'=>'CFA Franc (BEAC) (XAF)', 'symbol'=>'Fr');
	$lc_currencylist['XCD'] = array('name'=>'East Caribbean Dollar (XCD)', 'symbol'=>'$');
	$lc_currencylist['XOF'] = array('name'=>'CFA Franc (BCEAO) (XOF)', 'symbol'=>'Fr');
	$lc_currencylist['XPF'] = array('name'=>'Pacific Franc (XPF)', 'symbol'=>'Fr');
	$lc_currencylist['YER'] = array('name'=>'Yemen Riyal (YER)', 'symbol'=>'&#65020;');
	$lc_currencylist['ZAR'] = array('name'=>'South African Rand (ZAR)', 'symbol'=>'R');
	$lc_currencylist['ZMK'] = array('name'=>'Zambian Kwacha (ZMK)', 'symbol'=>'Zk');
	$lc_currencylist['ZWD'] = array('name'=>'Zimbabwe Dollar (ZWD)', 'symbol'=>'Z$');
	$localcurrency_options['currency_list'] = $lc_currencylist;
	
	// create the currency location list array and set that as an option
	$lc_locationlist = array(
		'AF' => 'AFA',
		'AL' => 'ALL',
		'DZ' => 'DZD',
		'AS' => 'USD',
		'AD' => 'EUR',
		'AO' => 'AON',
		'AI' => 'XCD',
		'AQ' => 'NOK',
		'AG' => 'XCD',
		'AR' => 'ARA',
		'AM' => 'AMD',
		'AW' => 'AWG',
		'AU' => 'AUD',
		'AT' => 'EUR',
		'AZ' => 'AZM',
		'BS' => 'BSD',
		'BH' => 'BHD',
		'BD' => 'BDT',
		'BB' => 'BBD',
		'BY' => 'BYR',
		'BE' => 'EUR',
		'BZ' => 'BZD',
		'BJ' => 'XAF',
		'BM' => 'BMD',
		'BT' => 'BTN',
		'BO' => 'BOB',
		'BA' => 'BAM',
		'BW' => 'BWP',
		'BV' => 'NOK',
		'BR' => 'BRR',
		'IO' => 'GBP',
		'BN' => 'BND',
		'BG' => 'BGL',
		'BF' => 'XAF',
		'BI' => 'BIF',
		'KH' => 'KHR',
		'CM' => 'XAF',
		'CA' => 'CAD',
		'CV' => 'CVE',
		'KY' => 'KYD',
		'CF' => 'XAF',
		'TD' => 'XAF',
		'CL' => 'CLF',
		'CN' => 'CNY',
		'CX' => 'AUD',
		'CC' => 'AUD',
		'CO' => 'COP',
		'KM' => 'KMF',
		'CG' => 'XAF',
		'CK' => 'NZD',
		'CR' => 'CRC',
		'CI' => 'XAF',
		'HR' => 'HRK',
		'CU' => 'CUP',
		'CY' => 'CYP',
		'CZ' => 'CZK',
		'DK' => 'DKK',
		'DJ' => 'DJF',
		'DM' => 'XCD',
		'DO' => 'DOP',
		'EC' => 'USD',
		'EG' => 'EGP',
		'SV' => 'USD',
		'GQ' => 'XAF',
		'ER' => 'ERN',
		'EE' => 'EEK',
		'ET' => 'ETB',
		'FK' => 'FKP',
		'FO' => 'DKK',
		'FJ' => 'FJD',
		'FI' => 'EUR',
		'FR' => 'EUR',
		'GF' => 'EUR',
		'PF' => 'XPF',
		'TF' => 'EUR',
		'GA' => 'XAF',
		'GM' => 'GMD',
		'GE' => 'GEL',
		'DE' => 'EUR',
		'GH' => 'GHC',
		'GI' => 'GIP',
		'GR' => 'EUR',
		'GL' => 'DKK',
		'GD' => 'XCD',
		'GP' => 'EUR',
		'GU' => 'USD',
		'GT' => 'GTQ',
		'GG' => 'GBP',
		'GN' => 'GNS',
		'GW' => 'GWP',
		'GY' => 'GYD',
		'HT' => 'HTG',
		'HM' => 'AUD',
		'VA' => 'EUR',
		'HN' => 'HNL',
		'HK' => 'HKD',
		'HU' => 'HUF',
		'IS' => 'ISK',
		'IN' => 'INR',
		'ID' => 'IDR',
		'IR' => 'IRR',
		'IQ' => 'IQD',
		'IE' => 'EUR',
		'IM' => 'GBP',
		'IL' => 'ILS',
		'IT' => 'EUR',
		'JM' => 'JMD',
		'JP' => 'JPY',
		'JE' => 'GBP',
		'JO' => 'JOD',
		'KZ' => 'KZT',
		'KE' => 'KES',
		'KI' => 'AUD',
		'KR' => 'KRW',
		'KW' => 'KWD',
		'KG' => 'KGS',
		'LA' => 'LAK',
		'LV' => 'LVL',
		'LB' => 'LBP',
		'LS' => 'LSL',
		'LR' => 'LRD',
		'LY' => 'LYD',
		'LI' => 'CHF',
		'LT' => 'LTL',
		'LU' => 'EUR',
		'MO' => 'MOP',
		'MK' => 'MKD',
		'MG' => 'MGF',
		'MW' => 'MWK',
		'MY' => 'MYR',
		'MV' => 'MVR',
		'ML' => 'XAF',
		'MT' => 'MTL',
		'MH' => 'USD',
		'MQ' => 'EUR',
		'MR' => 'MRO',
		'MU' => 'MUR',
		'YT' => 'EUR',
		'MX' => 'MXN',
		'MC' => 'EUR',
		'MN' => 'MNT',
		'ME' => 'EUR',
		'MS' => 'XCD',
		'MA' => 'MAD',
		'MZ' => 'MZM',
		'MM' => 'MMK',
		'NA' => 'NAD',
		'NR' => 'AUD',
		'NP' => 'NPR',
		'NL' => 'EUR',
		'AN' => 'ANG',
		'NC' => 'XPF',
		'NZ' => 'NZD',
		'NI' => 'NIC',
		'NE' => 'XOF',
		'NG' => 'NGN',
		'NU' => 'NZD',
		'NF' => 'AUD',
		'MP' => 'USD',
		'NO' => 'NOK',
		'OM' => 'OMR',
		'PK' => 'PKR',
		'PW' => 'USD',
		'PA' => 'PAB',
		'PG' => 'PGK',
		'PY' => 'PYG',
		'PE' => 'PEI',
		'PH' => 'PHP',
		'PN' => 'NZD',
		'PL' => 'PLN',
		'PT' => 'EUR',
		'PR' => 'USD',
		'QA' => 'QAR',
		'RE' => 'EUR',
		'RO' => 'ROL',
		'RU' => 'RUB',
		'RW' => 'RWF',
		'SH' => 'SHP',
		'KN' => 'XCD',
		'LC' => 'XCD',
		'PM' => 'EUR',
		'VC' => 'XCD',
		'WS' => 'WST',
		'SM' => 'EUR',
		'ST' => 'STD',
		'SA' => 'SAR',
		'SN' => 'XOF',
		'SC' => 'SCR',
		'SL' => 'SLL',
		'SG' => 'SGD',
		'SK' => 'SKK',
		'SI' => 'SIT',
		'SB' => 'SBD',
		'SO' => 'SOS',
		'ZA' => 'ZAR',
		'GS' => 'GBP',
		'ES' => 'EUR',
		'LK' => 'LKR',
		'SD' => 'SDG',
		'SR' => 'SRG',
		'SJ' => 'NOK',
		'SZ' => 'SZL',
		'SE' => 'SEK',
		'CH' => 'CHF',
		'SY' => 'SYP',
		'TW' => 'TWD',
		'TJ' => 'TJR',
		'TZ' => 'TZS',
		'TH' => 'THB',
		'TL' => 'TPE',
		'TG' => 'XAF',
		'TK' => 'NZD',
		'TO' => 'TOP',
		'TT' => 'TTD',
		'TN' => 'TND',
		'TR' => 'TRL',
		'TM' => 'TMM',
		'TC' => 'USD',
		'TV' => 'AUD',
		'UG' => 'UGS',
		'UA' => 'UAH',
		'AE' => 'AED',
		'GB' => 'GBP',
		'US' => 'USD',
		'UM' => 'USD',
		'UY' => 'UYU',
		'UZ' => 'UZS',
		'VU' => 'VUV',
		'VE' => 'VEB',
		'VN' => 'VND',
		'VG' => 'USD',
		'VI' => 'USD',
		'WF' => 'XPF',
		'EH' => 'MAD',
		'YE' => 'YER',
		'ZM' => 'ZMK',
		'ZW' => 'ZWD',
	);
	$localcurrency_options['currency_locationlist'] = $lc_locationlist;
	
	// Save the options
	update_option('localcurrency_options', $localcurrency_options);

?>