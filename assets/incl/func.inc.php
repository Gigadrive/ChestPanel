<?php session_start();

error_reporting(E_ALL);
ini_set("display_errors",1);
ini_set("display_startup_errors",1);
date_default_timezone_set("Europe/Berlin");

$link = mysqli_connect("****************","****************","****************","****************");
mysqli_set_charset($link,"utf8");

require_once "/home/web/thechest-admin/vendor/autoload.php";
require_once "paginator.class.php";
require_once "MinecraftPlayer.class.php";

require_once "mcping/MinecraftPing.php";
require_once "mcping/MinecraftPingException.php";
require_once "mcping/MinecraftQuery.php";
require_once "mcping/MinecraftQueryException.php";

use phpFastCache\CacheManager;
use phpFastCache\Core\phpFastCache;
use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;
$InstanceCache = CacheManager::getInstance("files");

$currentPlayer = null;
if(isset($_SESSION["uuid"])){
    $currentPlayer = MinecraftPlayer::getByUUID($_SESSION["uuid"]);
}

//
// -----------------------------------------------------------------------------------------------------------------
// CACHE FUNCTIONS START
// -----------------------------------------------------------------------------------------------------------------
//

function setToCache($name,$value,$expiry){
	global $InstanceCache;
	
	if(existsInCache($name)) deleteFromCache($name);
	
	$c = $InstanceCache->getItem($name);
	if(is_null($c->get())){
		$c->set($value)->expiresAfter($expiry);
		$InstanceCache->save($c);
	}
}

function getFromCache($name){
	if(existsInCache($name)){
		global $InstanceCache;
	
		$c = $InstanceCache->getItem($name);
		
		return $c->get();
	} else {
		return null;
	}
}

function existsInCache($name){
	global $InstanceCache;
	
	return $InstanceCache->hasItem($name);
	//return getFromCache($name) != null;
}

function deleteFromCache($name){
	global $InstanceCache;
	
	$r = false;
	
	if(existsInCache($name)){
		$InstanceCache->deleteItem($name);
		$r = true;
	}
	
	return $r;
}

//
// -----------------------------------------------------------------------------------------------------------------
// CACHE FUNCTIONS END
// -----------------------------------------------------------------------------------------------------------------
//

function convertRankToID($rank){
    if($rank == "USER"){
        return 0;
    } else if($rank == "PRO"){
        return 1;
    } else if($rank == "PRO_PLUS"){
        return 2;
    } else if($rank == "TITAN"){
        return 3;
    } else if($rank == "VIP"){
        return 4;
    } else if($rank == "STAFF"){
        return 5;
    } else if($rank == "BUILD_TEAM"){
        return 6;
    } else if($rank == "MOD"){
        return 7;
    } else if($rank == "SR_MOD"){
        return 8;
    } else if($rank == "CM"){
        return 9;
    } else if($rank == "ADMIN"){
        return 10;
    }

    return 0;
}

function getRankColor($rank){
    if($rank == "USER"){
        return "#707070";
    } else if($rank == "PRO"){
        return "#E08216";
    } else if($rank == "PRO_PLUS"){
        return "#094E7D";
    } else if($rank == "TITAN"){
        return "#0DD9CB";
    } else if($rank == "VIP"){
        return "#910978";
    } else if($rank == "STAFF"){
        return "#F2D21D";
    } else if($rank == "BUILD_TEAM"){
        return "#133394";
    } else if($rank == "MOD"){
        return "#11D614";
    } else if($rank == "SR_MOD"){
        return "#0C6E0E";
    } else if($rank == "CM"){
        return "#FF0000";
    } else if($rank == "ADMIN"){
        return "#FF0000";
    }

    return "#707070";
}

function getRankName($rank){
    if($rank == "USER"){
        return "User";
    } else if($rank == "PRO"){
        return "Pro";
    } else if($rank == "PRO_PLUS"){
        return "Pro+";
    } else if($rank == "TITAN"){
        return "Titan";
    } else if($rank == "VIP"){
        return "VIP";
    } else if($rank == "STAFF"){
        return "Staff Member";
    } else if($rank == "BUILD_TEAM"){
        return "Build Team Member";
    } else if($rank == "MOD"){
        return "Moderator";
    } else if($rank == "SR_MOD"){
        return "Senior Moderator";
    } else if($rank == "CM"){
        return "Community Manager";
    } else if($rank == "ADMIN"){
        return "Administrator";
    }

    return "User";
}

function timeago($timestamp){
	echo '<time class="timeago" datetime="' . $timestamp . '">' . $timestamp . '</time>';
}

function convertTime($timeString, $time){
    $dt_ev = new DateTime($time);
    return $dt_ev->format($timeString);
}

function getVisitoryFromCountry($country){
    global $link;
    $n = "countryVisitory_" . $country;

    if(existsInCache($n)){
        return getFromCache($n);
    } else {
        $s = mysqli_query($link,"SELECT * FROM `users` WHERE `country` = '" . mysqli_real_escape_string($link,$country) . "'");
        if($s){
            $i = mysqli_num_rows($s);
            setToCache($n,$i,60*60);

            return $i;
        } else {
            return 0;
        }
    }
}

function convertMCVersion($i){
    switch($i){
        case 47:
            return "1.8.X";
            break;
        case 107:
            return "1.9";
            break;
        case 108:
            return "1.9.1";
            break;
        case 109:
            return "1.9.2";
            break;
        case 110:
            return "1.9.4";
            break;
        case 210:
            return "1.10.X";
            break;
        case 315:
            return "1.11";
            break;
        case 316:
            return "1.11.2";
            break;
        case 335:
            return "1.12";
            break;
        case 338:
            return "1.12.1";
            break;
    }

    return "???";
}

$countrynames = array(
"AF"=>"Afghanistan",
"AX"=>"\xc3\x85land Islands",
"AL"=>"Albania",
"DZ"=>"Algeria",
"AS"=>"American Samoa",
"AD"=>"Andorra",
"AO"=>"Angola",
"AI"=>"Anguilla",
"AQ"=>"Antarctica",
"AG"=>"Antigua and Barbuda",
"AR"=>"Argentina",
"AM"=>"Armenia",
"AW"=>"Aruba",
"AU"=>"Australia",
"AT"=>"Austria",
"AZ"=>"Azerbaijan",
"BS"=>"Bahamas",
"BH"=>"Bahrain",
"BD"=>"Bangladesh",
"BB"=>"Barbados",
"BY"=>"Belarus",
"BE"=>"Belgium",
"BZ"=>"Belize",
"BJ"=>"Benin",
"BM"=>"Bermuda",
"BT"=>"Bhutan",
"BO"=>"Bolivia",
"BQ"=>"Bonaire, Sint Eustatius and Saba",
"BA"=>"Bosnia and Herzegovina",
"BW"=>"Botswana",
"BV"=>"Bouvet Island",
"BR"=>"Brazil",
"IO"=>"British Indian Ocean Territory",
"BN"=>"Brunei Darussalam",
"BG"=>"Bulgaria",
"BF"=>"Burkina Faso",
"BI"=>"Burundi",
"KH"=>"Cambodia",
"CM"=>"Cameroon",
"CA"=>"Canada",
"CV"=>"Cape Verde",
"KY"=>"Cayman Islands",
"CF"=>"Central African Republic",
"TD"=>"Chad",
"CL"=>"Chile",
"CN"=>"China",
"CX"=>"Christmas Island",
"CC"=>"Cocos (Keeling) Islands",
"CO"=>"Colombia",
"KM"=>"Comoros",
"CG"=>"Congo-Brazzaville",
"CD"=>"Congo-Kinshasa",
"CK"=>"Cook Islands",
"CR"=>"Costa Rica",
"CI"=>"C\xc3\xb4te d'Ivoire",
"HR"=>"Croatia",
"CU"=>"Cuba",
"CW"=>"Cura\xc3\xa7ao",
"CY"=>"Cyprus",
"CZ"=>"Czech Republic",
"DK"=>"Denmark",
"DJ"=>"Djibouti",
"DM"=>"Dominica",
"DO"=>"Dominican Republic",
"EC"=>"Ecuador",
"EG"=>"Egypt",
"SV"=>"El Salvador",
"GQ"=>"Equatorial Guinea",
"ER"=>"Eritrea",
"EE"=>"Estonia",
"ET"=>"Ethiopia",
"FK"=>"Falkland Islands",
"FO"=>"Faroe Islands",
"FJ"=>"Fiji",
"FI"=>"Finland",
"FR"=>"France",
"GF"=>"French Guiana",
"PF"=>"French Polynesia",
"TF"=>"French Southern Territories",
"GA"=>"Gabon",
"GM"=>"Gambia",
"GE"=>"Georgia",
"DE"=>"Germany",
"GH"=>"Ghana",
"GI"=>"Gibraltar",
"GR"=>"Greece",
"GL"=>"Greenland",
"GD"=>"Grenada",
"GP"=>"Guadeloupe",
"GU"=>"Guam",
"GT"=>"Guatemala",
"GG"=>"Guernsey",
"GN"=>"Guinea",
"GW"=>"Guinea-Bissau",
"GY"=>"Guyana",
"HT"=>"Haiti",
"HM"=>"Heard Island and McDonald Islands",
"VA"=>"Holy See (Vatican City State)",
"HN"=>"Honduras",
"HK"=>"Hong Kong",
"HU"=>"Hungary",
"IS"=>"Iceland",
"IN"=>"India",
"ID"=>"Indonesia",
"IR"=>"Iran",
"IQ"=>"Iraq",
"IE"=>"Ireland",
"IM"=>"Isle of Man",
"IL"=>"Israel",
"IT"=>"Italy",
"JM"=>"Jamaica",
"JP"=>"Japan",
"JE"=>"Jersey",
"JO"=>"Jordan",
"KZ"=>"Kazakhstan",
"KE"=>"Kenya",
"KI"=>"Kiribati",
"KP"=>"North Korea",
"KR"=>"South Korea",
"KW"=>"Kuwait",
"KG"=>"Kyrgyzstan",
"LA"=>"Laos",
"LV"=>"Latvia",
"LB"=>"Lebanon",
"LS"=>"Lesotho",
"LR"=>"Liberia",
"LY"=>"Libya",
"LI"=>"Liechtenstein",
"LT"=>"Lithuania",
"LU"=>"Luxembourg",
"MO"=>"Macao",
"MK"=>"Macedonia",
"MG"=>"Madagascar",
"MW"=>"Malawi",
"MY"=>"Malaysia",
"MV"=>"Maldives",
"ML"=>"Mali",
"MT"=>"Malta",
"MH"=>"Marshall Islands",
"MQ"=>"Martinique",
"MR"=>"Mauritania",
"MU"=>"Mauritius",
"YT"=>"Mayotte",
"MX"=>"Mexico",
"FM"=>"Micronesia, Federated States of",
"MD"=>"Moldova",
"MC"=>"Monaco",
"MN"=>"Mongolia",
"ME"=>"Montenegro",
"MS"=>"Montserrat",
"MA"=>"Morocco",
"MZ"=>"Mozambique",
"MM"=>"Myanmar",
"NA"=>"Namibia",
"NR"=>"Nauru",
"NP"=>"Nepal",
"NL"=>"Netherlands",
"NC"=>"New Caledonia",
"NZ"=>"New Zealand",
"NI"=>"Nicaragua",
"NE"=>"Niger",
"NG"=>"Nigeria",
"NU"=>"Niue",
"NF"=>"Norfolk Island",
"MP"=>"Northern Mariana Islands",
"NO"=>"Norway",
"OM"=>"Oman",
"PK"=>"Pakistan",
"PW"=>"Palau",
"PS"=>"Palestine",
"PA"=>"Panama",
"PG"=>"Papua New Guinea",
"PY"=>"Paraguay",
"PE"=>"Peru",
"PH"=>"Philippines",
"PN"=>"Pitcairn",
"PL"=>"Poland",
"PT"=>"Portugal",
"PR"=>"Puerto Rico",
"QA"=>"Qatar",
"RE"=>"R\xc3\xa9union",
"RO"=>"Romania",
"RU"=>"Russia",
"RW"=>"Rwanda",
"BL"=>"Saint Barth\xc3\xa9lemy",
"SH"=>"Saint Helena, Ascension and Tristan Da Cunha",
"KN"=>"Saint Kitts and Nevis",
"LC"=>"Saint Lucia",
"MF"=>"Saint Martin (French part)",
"PM"=>"Saint Pierre and Miquelon",
"VC"=>"Saint Vincent and the Grenadines",
"WS"=>"Samoa",
"SM"=>"San Marino",
"ST"=>"Sao Tome and Principe",
"SA"=>"Saudi Arabia",
"SN"=>"Senegal",
"RS"=>"Serbia",
"SC"=>"Seychelles",
"SL"=>"Sierra Leone",
"SG"=>"Singapore",
"SX"=>"Sint Maarten (Dutch part)",
"SK"=>"Slovakia",
"SI"=>"Slovenia",
"SB"=>"Solomon Islands",
"SO"=>"Somalia",
"ZA"=>"South Africa",
"GS"=>"South Georgia and the South Sandwich Islands",
"SS"=>"South Sudan",
"ES"=>"Spain",
"LK"=>"Sri Lanka",
"SD"=>"Sudan",
"SR"=>"Suriname",
"SJ"=>"Svalbard and Jan Mayen",
"SZ"=>"Swaziland",
"SE"=>"Sweden",
"CH"=>"Switzerland",
"SY"=>"Syria",
"TW"=>"Taiwan",
"TJ"=>"Tajikistan",
"TZ"=>"Tanzania",
"TH"=>"Thailand",
"TL"=>"Timor-Leste",
"TG"=>"Togo",
"TK"=>"Tokelau",
"TO"=>"Tonga",
"TT"=>"Trinidad and Tobago",
"TN"=>"Tunisia",
"TR"=>"Turkey",
"TM"=>"Turkmenistan",
"TC"=>"Turks and Caicos Islands",
"TV"=>"Tuvalu",
"UG"=>"Uganda",
"UA"=>"Ukraine",
"AE"=>"United Arab Emirates",
"GB"=>"United Kingdom",
"US"=>"United States",
"UM"=>"United States Minor Outlying Islands",
"UY"=>"Uruguay",
"UZ"=>"Uzbekistan",
"VU"=>"Vanuatu",
"VE"=>"Venezuela",
"VN"=>"Viet Nam",
"VG"=>"Virgin Islands, British",
"VI"=>"Virgin Islands, U.S.",
"WF"=>"Wallis and Futuna",
"EH"=>"Western Sahara",
"YE"=>"Yemen",
"ZM"=>"Zambia",
"ZW"=>"Zimbabwe"
);

$countrycodes = array(
"Afghanistan"=>"AF",
"\xc3\x85land Islands"=>"AX",
"Albania"=>"AL",
"Algeria"=>"DZ",
"American Samoa"=>"AS",
"Andorra"=>"AD",
"Angola"=>"AO",
"Anguilla"=>"AI",
"Antarctica"=>"AQ",
"Antigua and Barbuda"=>"AG",
"Argentina"=>"AR",
"Armenia"=>"AM",
"Aruba"=>"AW",
"Australia"=>"AU",
"Austria"=>"AT",
"Azerbaijan"=>"AZ",
"Bahamas"=>"BS",
"Bahrain"=>"BH",
"Bangladesh"=>"BD",
"Barbados"=>"BB",
"Belarus"=>"BY",
"Belgium"=>"BE",
"Belize"=>"BZ",
"Benin"=>"BJ",
"Bermuda"=>"BM",
"Bhutan"=>"BT",
"Bolivia"=>"BO",
"Bonaire, Sint Eustatius and Saba"=>"BQ",
"Bosnia and Herzegovina"=>"BA",
"Botswana"=>"BW",
"Bouvet Island"=>"BV",
"Brazil"=>"BR",
"British Indian Ocean Territory"=>"IO",
"Brunei Darussalam"=>"BN",
"Bulgaria"=>"BG",
"Burkina Faso"=>"BF",
"Burundi"=>"BI",
"Cambodia"=>"KH",
"Cameroon"=>"CM",
"Canada"=>"CA",
"Cape Verde"=>"CV",
"Cayman Islands"=>"KY",
"Central African Republic"=>"CF",
"Chad"=>"TD",
"Chile"=>"CL",
"China"=>"CN",
"Christmas Island"=>"CX",
"Cocos (Keeling) Islands"=>"CC",
"Colombia"=>"CO",
"Comoros"=>"KM",
"Congo-Brazzaville"=>"CG",
"Congo-Kinshasa"=>"CD",
"Cook Islands"=>"CK",
"Costa Rica"=>"CR",
"C\xc3\xb4te d'Ivoire"=>"CI",
"Croatia"=>"HR",
"Cuba"=>"CU",
"Cura\xc3\xa7ao"=>"CW",
"Cyprus"=>"CY",
"Czech Republic"=>"CZ",
"Denmark"=>"DK",
"Djibouti"=>"DJ",
"Dominica"=>"DM",
"Dominican Republic"=>"DO",
"Ecuador"=>"EC",
"Egypt"=>"EG",
"El Salvador"=>"SV",
"Equatorial Guinea"=>"GQ",
"Eritrea"=>"ER",
"Estonia"=>"EE",
"Ethiopia"=>"ET",
"Falkland Islands"=>"FK",
"Faroe Islands"=>"FO",
"Fiji"=>"FJ",
"Finland"=>"FI",
"France"=>"FR",
"French Guiana"=>"GF",
"French Polynesia"=>"PF",
"French Southern Territories"=>"TF",
"Gabon"=>"GA",
"Gambia"=>"GM",
"Georgia"=>"GE",
"Germany"=>"DE",
"Ghana"=>"GH",
"Gibraltar"=>"GI",
"Greece"=>"GR",
"Greenland"=>"GL",
"Grenada"=>"GD",
"Guadeloupe"=>"GP",
"Guam"=>"GU",
"Guatemala"=>"GT",
"Guernsey"=>"GG",
"Guinea"=>"GN",
"Guinea-Bissau"=>"GW",
"Guyana"=>"GY",
"Haiti"=>"HT",
"Heard Island and McDonald Islands"=>"HM",
"Holy See (Vatican City State)"=>"VA",
"Honduras"=>"HN",
"Hong Kong"=>"HK",
"Hungary"=>"HU",
"Iceland"=>"IS",
"India"=>"IN",
"Indonesia"=>"ID",
"Iran"=>"IR",
"Iraq"=>"IQ",
"Ireland"=>"IE",
"Isle of Man"=>"IM",
"Israel"=>"IL",
"Italy"=>"IT",
"Jamaica"=>"JM",
"Japan"=>"JP",
"Jersey"=>"JE",
"Jordan"=>"JO",
"Kazakhstan"=>"KZ",
"Kenya"=>"KE",
"Kiribati"=>"KI",
"North Korea"=>"KP",
"South Korea"=>"KR",
"Kuwait"=>"KW",
"Kyrgyzstan"=>"KG",
"Laos"=>"LA",
"Latvia"=>"LV",
"Lebanon"=>"LB",
"Lesotho"=>"LS",
"Liberia"=>"LR",
"Libya"=>"LY",
"Liechtenstein"=>"LI",
"Lithuania"=>"LT",
"Luxembourg"=>"LU",
"Macao"=>"MO",
"Macedonia"=>"MK",
"Madagascar"=>"MG",
"Malawi"=>"MW",
"Malaysia"=>"MY",
"Maldives"=>"MV",
"Mali"=>"ML",
"Malta"=>"MT",
"Marshall Islands"=>"MH",
"Martinique"=>"MQ",
"Mauritania"=>"MR",
"Mauritius"=>"MU",
"Mayotte"=>"YT",
"Mexico"=>"MX",
"Micronesia, Federated States of"=>"FM",
"Moldova"=>"MD",
"Monaco"=>"MC",
"Mongolia"=>"MN",
"Montenegro"=>"ME",
"Montserrat"=>"MS",
"Morocco"=>"MA",
"Mozambique"=>"MZ",
"Myanmar"=>"MM",
"Namibia"=>"NA",
"Nauru"=>"NR",
"Nepal"=>"NP",
"Netherlands"=>"NL",
"New Caledonia"=>"NC",
"New Zealand"=>"NZ",
"Nicaragua"=>"NI",
"Niger"=>"NE",
"Nigeria"=>"NG",
"Niue"=>"NU",
"Norfolk Island"=>"NF",
"Northern Mariana Islands"=>"MP",
"Norway"=>"NO",
"Oman"=>"OM",
"Pakistan"=>"PK",
"Palau"=>"PW",
"Palestine"=>"PS",
"Panama"=>"PA",
"Papua New Guinea"=>"PG",
"Paraguay"=>"PY",
"Peru"=>"PE",
"Philippines"=>"PH",
"Pitcairn"=>"PN",
"Poland"=>"PL",
"Portugal"=>"PT",
"Puerto Rico"=>"PR",
"Qatar"=>"QA",
"R\xc3\xa9union"=>"RE",
"Romania"=>"RO",
"Russia"=>"RU",
"Rwanda"=>"RW",
"Saint Barth\xc3\xa9lemy"=>"BL",
"Saint Helena, Ascension and Tristan Da Cunha"=>"SH",
"Saint Kitts and Nevis"=>"KN",
"Saint Lucia"=>"LC",
"Saint Martin (French part)"=>"MF",
"Saint Pierre and Miquelon"=>"PM",
"Saint Vincent and the Grenadines"=>"VC",
"Samoa"=>"WS",
"San Marino"=>"SM",
"Sao Tome and Principe"=>"ST",
"Saudi Arabia"=>"SA",
"Senegal"=>"SN",
"Serbia"=>"RS",
"Seychelles"=>"SC",
"Sierra Leone"=>"SL",
"Singapore"=>"SG",
"Sint Maarten (Dutch part)"=>"SX",
"Slovakia"=>"SK",
"Slovenia"=>"SI",
"Solomon Islands"=>"SB",
"Somalia"=>"SO",
"South Africa"=>"ZA",
"South Georgia and the South Sandwich Islands"=>"GS",
"South Sudan"=>"SS",
"Spain"=>"ES",
"Sri Lanka"=>"LK",
"Sudan"=>"SD",
"Suriname"=>"SR",
"Svalbard and Jan Mayen"=>"SJ",
"Swaziland"=>"SZ",
"Sweden"=>"SE",
"Switzerland"=>"CH",
"Syria"=>"SY",
"Taiwan"=>"TW",
"Tajikistan"=>"TJ",
"Tanzania"=>"TZ",
"Thailand"=>"TH",
"Timor-Leste"=>"TL",
"Togo"=>"TG",
"Tokelau"=>"TK",
"Tonga"=>"TO",
"Trinidad and Tobago"=>"TT",
"Tunisia"=>"TN",
"Turkey"=>"TR",
"Turkmenistan"=>"TM",
"Turks and Caicos Islands"=>"TC",
"Tuvalu"=>"TV",
"Uganda"=>"UG",
"Ukraine"=>"UA",
"United Arab Emirates"=>"AE",
"United Kingdom"=>"GB",
"United States"=>"US",
"United States Minor Outlying Islands"=>"UM",
"Uruguay"=>"UY",
"Uzbekistan"=>"UZ",
"Vanuatu"=>"VU",
"Venezuela"=>"VE",
"Viet Nam"=>"VN",
"Virgin Islands, British"=>"VG",
"Virgin Islands, U.S."=>"VI",
"Wallis and Futuna"=>"WF",
"Western Sahara"=>"EH",
"Yemen"=>"YE",
"Zambia"=>"ZM",
"Zimbabwe"=>"ZW"
);

function countryCodeToName($code){
    global $countrynames;

    if(array_key_exists($code,$countrynames)){
        return $countrynames[$code];
    } else {
        return $code;
    }
}

function countryNameToCode($name){
    global $countrycodes;

    if(array_key_exists($name,$countrycodes)){
        return $countrycodes[$name];
    } else {
        return $name;
    }
}

?>