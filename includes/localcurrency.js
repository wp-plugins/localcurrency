//<![CDATA[

// function to change local currency at user request
function localCurrencyChange(lcfrom,lcvalues,postid) {
	var lcselectname = document.getElementById("lc_currency"+postid);
	var lctoindex = lcselectname.selectedIndex;
	var lcto = lcselectname.options[lctoindex].value;
	localCurrency(lcfrom,lcto,lcvalues,postid);
}

// create object instance
function localCurrencyObject(id,name,symbol) {
	this.id = id;
	this.name = name;
	this.symbol = symbol;
}

// set up array of local currencies which we will use for symbols / select etc.
var localcurrency_array = new Array();
localcurrency_array['0'] = new localCurrencyObject("USD","U.S. Dollar (USD)","$"); 
localcurrency_array['1'] = new localCurrencyObject("GBP","British Pound (GBP)","&#163;"); 
localcurrency_array['2'] = new localCurrencyObject("EUR","Euro (EUR)","&#8364;"); 
localcurrency_array['3'] = new localCurrencyObject("AUD","Australian Dollar (AUD)","$"); 
localcurrency_array['4'] = new localCurrencyObject("CAD","Canadian Dollar (CAD)","$"); 
localcurrency_array['5'] = new localCurrencyObject("NZD","New Zealand Dollar (NZD)","$"); 
localcurrency_array['6'] = new localCurrencyObject("CNY","Chinese Yuan (CNY)","&#20803;"); 
localcurrency_array['7'] = new localCurrencyObject("JPY","Japanese Yen (JPY)","&#165;"); 
localcurrency_array['8'] = new localCurrencyObject("RUB","Russian Rouble (RUB)","&#1088;&#1091;&#1073;"); 
localcurrency_array['9'] = new localCurrencyObject("---","-------------------","---"); 
localcurrency_array['10'] = new localCurrencyObject("AED","UAE Dirham (AED)","&#1583;.&#1573;"); 
localcurrency_array['11'] = new localCurrencyObject("ANG","Neth Antilles Guilder (ANG)","&#402;"); 
localcurrency_array['12'] = new localCurrencyObject("ARS","Argentine Peso (ARS)","$"); 
localcurrency_array['13'] = new localCurrencyObject("AWG","Aruba Florin (AWG)","&#402;"); 
localcurrency_array['14'] = new localCurrencyObject("BBD","Barbados Dollar (BBD)","$"); 
localcurrency_array['15'] = new localCurrencyObject("BDT","Bangladesh Taka (BDT)",""); 
localcurrency_array['16'] = new localCurrencyObject("BGN","Bulgarian Lev (BGN)","&#1083;&#1074;"); 
localcurrency_array['17'] = new localCurrencyObject("BHD","Bahraini Dinar (BHD)","&#1576;.&#1583;"); 
localcurrency_array['18'] = new localCurrencyObject("BIF","Burundi Franc (BIF)","Fr"); 
localcurrency_array['19'] = new localCurrencyObject("BMD","Bermuda Dollar (BMD)","$"); 
localcurrency_array['20'] = new localCurrencyObject("BND","Brunei Dollar (BND)","$"); 
localcurrency_array['21'] = new localCurrencyObject("BOB","Bolivian Boliviano (BOB)","$b"); 
localcurrency_array['22'] = new localCurrencyObject("BRL","Brazilian Real (BRL)","R$"); 
localcurrency_array['23'] = new localCurrencyObject("BSD","Bahamian Dollar (BSD)","$"); 
localcurrency_array['24'] = new localCurrencyObject("BTN","Bhutan Ngultrum (BTN)",""); 
localcurrency_array['25'] = new localCurrencyObject("BYR","Belarus Ruble (BYR)","p."); 
localcurrency_array['26'] = new localCurrencyObject("BZD","Belize Dollar (BZD)","$"); 
localcurrency_array['27'] = new localCurrencyObject("CHF","Swiss Franc (CHF)","CHF"); 
localcurrency_array['28'] = new localCurrencyObject("CLP","Chilean Peso (CLP)","$"); 
localcurrency_array['29'] = new localCurrencyObject("COP","Colombian Peso (COP)","$"); 
localcurrency_array['30'] = new localCurrencyObject("CRC","Costa Rica Colon (CRC)","&#8353;"); 
localcurrency_array['31'] = new localCurrencyObject("CUP","Cuban Peso (CUP)","&#8369;"); 
localcurrency_array['32'] = new localCurrencyObject("CYP","Cyprus Pound (CYP)","&#163;"); 
localcurrency_array['33'] = new localCurrencyObject("CZK","Czech Koruna (CZK)","K&#269;"); 
localcurrency_array['34'] = new localCurrencyObject("DJF","Dijibouti Franc (DJF)","Fr"); 
localcurrency_array['35'] = new localCurrencyObject("DKK","Danish Krone (DKK)","kr"); 
localcurrency_array['36'] = new localCurrencyObject("DOP","Dominican Peso (DOP)","RD$"); 
localcurrency_array['37'] = new localCurrencyObject("DZD","Algerian Dinar (DZD)","&#1583;.&#1580;"); 
localcurrency_array['38'] = new localCurrencyObject("ECS","Ecuador Sucre (ECS)",""); 
localcurrency_array['39'] = new localCurrencyObject("EEK","Estonian Kroon (EEK)","kr"); 
localcurrency_array['40'] = new localCurrencyObject("EGP","Egyptian Pound (EGP)","&#163;"); 
localcurrency_array['41'] = new localCurrencyObject("ERN","Eritrea Nakfa (ERN)","Nfk"); 
localcurrency_array['42'] = new localCurrencyObject("ETB","Ethiopian Birr (ETB)",""); 
localcurrency_array['43'] = new localCurrencyObject("FKP","Falkland Islands Pound (FKP)","&#163;"); 
localcurrency_array['44'] = new localCurrencyObject("GHC","Ghanian Cedi (GHC)","&#162;"); 
localcurrency_array['45'] = new localCurrencyObject("GIP","Gibraltar Pound (GIP)","&#163;"); 
localcurrency_array['46'] = new localCurrencyObject("GMD","Gambian Dalasi (GMD)","D"); 
localcurrency_array['47'] = new localCurrencyObject("GNF","Guinea Franc (GNF)","Fr"); 
localcurrency_array['48'] = new localCurrencyObject("GTQ","Guatemala Quetzal (GTQ)","Q"); 
localcurrency_array['49'] = new localCurrencyObject("HKD","Hong Kong Dollar (HKD)","HK$"); 
localcurrency_array['50'] = new localCurrencyObject("HNL","Honduras Lempira (HNL)","L"); 
localcurrency_array['51'] = new localCurrencyObject("HRK","Croatian Kuna (HRK)","kn"); 
localcurrency_array['52'] = new localCurrencyObject("HTG","Haiti Gourde (HTG)","G"); 
localcurrency_array['53'] = new localCurrencyObject("HUF","Hungarian Forint (HUF)","Ft"); 
localcurrency_array['54'] = new localCurrencyObject("IDR","Indonesian Rupiah (IDR)","Rp"); 
localcurrency_array['55'] = new localCurrencyObject("ILS","Israeli Shekel (ILS)","&#8362;"); 
localcurrency_array['56'] = new localCurrencyObject("INR","Indian Rupee (INR)","&#8360;"); 
localcurrency_array['57'] = new localCurrencyObject("IRR","Iran Rial (IRR)","&#65020;"); 
localcurrency_array['58'] = new localCurrencyObject("ISK","Iceland Krona (ISK)","kr"); 
localcurrency_array['59'] = new localCurrencyObject("JMD","Jamaican Dollar (JMD)","J$"); 
localcurrency_array['60'] = new localCurrencyObject("JOD","Jordanian Dinar (JOD)","&#1583;.&#1575;"); 
localcurrency_array['61'] = new localCurrencyObject("KES","Kenyan Shilling (KES)","Sh"); 
localcurrency_array['62'] = new localCurrencyObject("KHR","Cambodia Riel (KHR)","&#6107;"); 
localcurrency_array['63'] = new localCurrencyObject("KMF","Comoros Franc (KMF)","Fr"); 
localcurrency_array['64'] = new localCurrencyObject("KRW","Korean Won (KRW)","&#8361;"); 
localcurrency_array['65'] = new localCurrencyObject("KWD","Kuwaiti Dinar (KWD)","&#1583;.&#1603;"); 
localcurrency_array['66'] = new localCurrencyObject("KYD","Cayman Islands Dollar (KYD)","$"); 
localcurrency_array['67'] = new localCurrencyObject("KZT","Kazakhstan Tenge (KZT)","&#1083;&#1074;"); 
localcurrency_array['68'] = new localCurrencyObject("LAK","Lao Kip (LAK)","&#8365;"); 
localcurrency_array['69'] = new localCurrencyObject("LBP","Lebanese Pound (LBP)","&#163;"); 
localcurrency_array['70'] = new localCurrencyObject("LKR","Sri Lanka Rupee (LKR)","&#8360;"); 
localcurrency_array['71'] = new localCurrencyObject("LSL","Lesotho Loti (LSL)","L"); 
localcurrency_array['72'] = new localCurrencyObject("LTL","Lithuanian Lita (LTL)","Lt"); 
localcurrency_array['73'] = new localCurrencyObject("LVL","Latvian Lat (LVL)","Ls"); 
localcurrency_array['74'] = new localCurrencyObject("LYD","Libyan Dinar (LYD)","&#1604;.&#1583;"); 
localcurrency_array['75'] = new localCurrencyObject("MAD","Moroccan Dirham (MAD)","&#1583;.&#1605;."); 
localcurrency_array['76'] = new localCurrencyObject("MDL","Moldovan Leu (MDL)","L"); 
localcurrency_array['77'] = new localCurrencyObject("MGF","Malagasy Franc (MGF)","Fr"); 
localcurrency_array['78'] = new localCurrencyObject("MKD","Macedonian Denar (MKD)","&#1076;&#1077;&#1085;"); 
localcurrency_array['79'] = new localCurrencyObject("MNT","Mongolian Tugrik (MNT)","&#8366;"); 
localcurrency_array['80'] = new localCurrencyObject("MOP","Macau Pataca (MOP)","P"); 
localcurrency_array['81'] = new localCurrencyObject("MRO","Mauritania Ougulya (MRO)","UM"); 
localcurrency_array['82'] = new localCurrencyObject("MTL","Maltese Lira (MTL)","Lm"); 
localcurrency_array['83'] = new localCurrencyObject("MUR","Mauritius Rupee (MUR)","&#8360;"); 
localcurrency_array['84'] = new localCurrencyObject("MVR","Maldives Rufiyaa (MVR)","&#1923;."); 
localcurrency_array['85'] = new localCurrencyObject("MWK","Malawi Kwacha (MWK)","MK"); 
localcurrency_array['86'] = new localCurrencyObject("MXN","Mexican Peso (MXN)","$"); 
localcurrency_array['87'] = new localCurrencyObject("MYR","Malaysian Ringgit (MYR)","RM"); 
localcurrency_array['88'] = new localCurrencyObject("MZN","Mozambique Metical (MZM)","MTn"); 
localcurrency_array['89'] = new localCurrencyObject("NAD","Namibian Dollar (NAD)","$"); 
localcurrency_array['90'] = new localCurrencyObject("NGN","Nigerian Naira (NGN)","&#8358;"); 
localcurrency_array['91'] = new localCurrencyObject("NIO","Nicaragua Cordoba (NIO)","C$"); 
localcurrency_array['92'] = new localCurrencyObject("NOK","Norwegian Krone (NOK)","kr"); 
localcurrency_array['93'] = new localCurrencyObject("NPR","Nepalese Rupee (NPR)","&#8360;"); 
localcurrency_array['94'] = new localCurrencyObject("OMR","Omani Rial (OMR)","&#1585;.&#1593;."); 
localcurrency_array['95'] = new localCurrencyObject("PAB","Panama Balboa (PAB)","B/."); 
localcurrency_array['96'] = new localCurrencyObject("PEN","Peruvian Nuevo Sol (PEN)","S/."); 
localcurrency_array['97'] = new localCurrencyObject("PGK","Papua New Guinea Kina (PGK)","K"); 
localcurrency_array['98'] = new localCurrencyObject("PHP","Philippine Peso (PHP)","Php"); 
localcurrency_array['99'] = new localCurrencyObject("PKR","Pakistani Rupee (PKR)","&#8360;"); 
localcurrency_array['100'] = new localCurrencyObject("PLN","Polish Zloty (PLN)","z&#322;"); 
localcurrency_array['101'] = new localCurrencyObject("PYG","Paraguayan Guarani (PYG)","Gs"); 
localcurrency_array['102'] = new localCurrencyObject("QAR","Qatar Rial (QAR)","&#1585;.&#1602;"); 
localcurrency_array['103'] = new localCurrencyObject("RON","Romanian New Leu (RON)","lei"); 
localcurrency_array['104'] = new localCurrencyObject("RWF","Rwanda Franc (RWF)","Fr"); 
localcurrency_array['105'] = new localCurrencyObject("SAR","Saudi Arabian Riyal (SAR)","&#1585;.&#1587;"); 
localcurrency_array['106'] = new localCurrencyObject("SCR","Seychelles Rupee (SCR)","&#8360;"); 
localcurrency_array['107'] = new localCurrencyObject("SDG","Sudanese Dinar (SDD)","&#163;"); 
localcurrency_array['108'] = new localCurrencyObject("SEK","Swedish Krona (SEK)","kr"); 
localcurrency_array['109'] = new localCurrencyObject("SGD","Singapore Dollar (SGD)","$"); 
localcurrency_array['110'] = new localCurrencyObject("SHP","St Helena Pound (SHP)","&#163;"); 
localcurrency_array['111'] = new localCurrencyObject("SKK","Slovak Koruna (SKK)","SIT"); 
localcurrency_array['112'] = new localCurrencyObject("SLL","Sierra Leone Leone (SLL)","Le"); 
localcurrency_array['113'] = new localCurrencyObject("SOS","Somali Shilling (SOS)","S"); 
localcurrency_array['114'] = new localCurrencyObject("SRD","Surinam Guilder (SRG)","$"); 
localcurrency_array['115'] = new localCurrencyObject("STD","Sao Tome Dobra (STD)","Db"); 
localcurrency_array['116'] = new localCurrencyObject("SVC","El Salvador Colon (SVC)","$"); 
localcurrency_array['117'] = new localCurrencyObject("SYP","Syrian Pound (SYP)","&#163;"); 
localcurrency_array['118'] = new localCurrencyObject("SZL","Swaziland Lilageni (SZL)","L"); 
localcurrency_array['119'] = new localCurrencyObject("THB","Thai Baht (THB)","&#3647;"); 
localcurrency_array['120'] = new localCurrencyObject("TND","Tunisian Dinar (TND)","&#1583;.&#1578;"); 
localcurrency_array['121'] = new localCurrencyObject("TOP","Tonga Paanga (TOP)","T$"); 
localcurrency_array['122'] = new localCurrencyObject("TRY","New Turkish Lira (TRY)","YTL"); 
localcurrency_array['123'] = new localCurrencyObject("TTD","Trinidad&Tobago Dollar (TTD)","TT$"); 
localcurrency_array['124'] = new localCurrencyObject("TWD","Taiwan Dollar (TWD)","$"); 
localcurrency_array['125'] = new localCurrencyObject("TZS","Tanzanian Shilling (TZS)","Sh"); 
localcurrency_array['126'] = new localCurrencyObject("UAH","Ukraine Hryvnia (UAH)","&#8372;"); 
localcurrency_array['127'] = new localCurrencyObject("UGX","Ugandan Shilling (UGX)","Sh"); 
localcurrency_array['128'] = new localCurrencyObject("UYU","Uruguayan New Peso (UYU)","$U"); 
localcurrency_array['129'] = new localCurrencyObject("VEB","Venezuelan Bolivar (VEB)","Bs"); 
localcurrency_array['130'] = new localCurrencyObject("VND","Vietnam Dong (VND)","&#8363;"); 
localcurrency_array['131'] = new localCurrencyObject("VUV","Vanuatu Vatu (VUV)","Vt"); 
localcurrency_array['132'] = new localCurrencyObject("WST","Samoa Tala (WST)","T"); 
localcurrency_array['133'] = new localCurrencyObject("XAF","CFA Franc (BEAC) (XAF)","Fr"); 
localcurrency_array['134'] = new localCurrencyObject("XCD","East Caribbean Dollar (XCD)","$"); 
localcurrency_array['135'] = new localCurrencyObject("XOF","CFA Franc (BCEAO) (XOF)","Fr"); 
localcurrency_array['136'] = new localCurrencyObject("XPF","Pacific Franc (XPF)","Fr"); 
localcurrency_array['137'] = new localCurrencyObject("YER","Yemen Riyal (YER)","&#65020;"); 
localcurrency_array['138'] = new localCurrencyObject("ZAR","South African Rand (ZAR)","R"); 
localcurrency_array['139'] = new localCurrencyObject("ZMK","Zambian Kwacha (ZMK)","Zk"); 
localcurrency_array['140'] = new localCurrencyObject("ZWD","Zimbabwe Dollar (ZWD)","Z$"); 

// add form for reader to change currency. Created by script to save file size.
function localCurrencyUserSelection(sitecurrency,usercurrency,postid) {
	document.write('<select style="width:200px" name="lc_currency'+postid+'" id="lc_currency'+postid+'" onchange="localCurrencyChange(\''+sitecurrency+'\',lcValues'+postid+','+postid+')">');
	for (var i = 0; i <= localcurrency_array.length-1; i++) {
		document.write('<option value="'+localcurrency_array[i]['id']+'" ');
		if (usercurrency == localcurrency_array[i]['id']) {document.write('selected="selected"');}
		document.write('>'+localcurrency_array[i]['name']+'</option>');
	}
	document.write('</select>');
}
//]]>