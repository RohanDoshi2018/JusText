<?php

	header("Access-Control-Allow-Origin: *");

	require 'vendor/autoload.php';
	use Parse\ParseClient;
	use Parse\ParseObject;
	use Parse\ParseQuery;
	use Parse\ParseACL;
	use Parse\ParsePush;
	use Parse\ParseUser;
	use Parse\ParseInstallation;
	use Parse\ParseException;
	use Parse\ParseAnalytics;
	use Parse\ParseFile;
	use Parse\ParseCloud;
	ParseClient::initialize('oeAOgVV52ypbXGCttQD0EjkT3aYkEyEu6FlE0E6O', 'TEK3rC7AB7eXpaC8UilOs9WPNZYNldrt5enrYBjB', 'yV3aiE8IzVavtmqmR73UqEnRJYwiOUyne6TH023m');

	require_once("TwitterAPIExchange.php");
	$settings = array(
		'oauth_access_token' => "2878067884-bnEjHxm6snfuXlHSJnk182rbOrBr3jkn4NUi1ro",
		'oauth_access_token_secret' => "5wDLzsDTAm8oWSZVlRoodnaOJQms94dCJvolZdxMyM3Dp",
		'consumer_key' => "Ajw2MKj1se4wF0K5COJ0xX1Nz",
		'consumer_secret' => "BMYXK04QywravXg2XSc6wFqQgENJa0UaDIiHzLmTSIGnF5ubXa"
	);

	class BingTranslation
	{
    	public $clientID;
    	public $clientSecret;

    	public function __construct($cid, $secret)
    	{
        	$this->clientID = $cid;
        	$this->clientSecret = $secret;
    	}

    	public function get_access_token()
    	{   
        	//if access token is not expired and is stored in COOKIE
        	if(isset($_COOKIE['bing_access_token']))
            	return $_COOKIE['bing_access_token'];

        	// Get a 10-minute access token for Microsoft Translator API.
        	$url = 'https://datamarket.accesscontrol.windows.net/v2/OAuth2-13';
        	$postParams = 'grant_type=client_credentials&client_id='.urlencode($this->clientID).
        	'&client_secret='.urlencode($this->clientSecret).'&scope=http://api.microsofttranslator.com';

        	$ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL, $url); 
        	curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);  
        	$rsp = curl_exec($ch); 
        	$rsp = json_decode($rsp);
        	$access_token = $rsp->access_token;

        	setcookie('bing_access_token', $access_token, $rsp->expires_in);

        	return $access_token;
    	}

    	public function translate($word, $from, $to)
    	{
        	$access_token = $this->get_access_token();
        	$url = 'http://api.microsofttranslator.com/V2/Http.svc/Translate?text='.$word.'&from='.$from.'&to='.$to;

        	$ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL, $url); 
        	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:bearer '.$access_token));
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);  
        	$rsp = curl_exec($ch); 

        	preg_match_all('/<string (.*?)>(.*?)<\/string>/s', $rsp, $matches);

        	return $matches[2][0];
    	}

    	public function translate2($word, $from, $tos)
    	{
        	//translates 1 word to several languages
        	//$tos is array of languages to translate to
        	//returns array of translations as $result['en']=>'Hello'

        	$access_token = $this->get_access_token();

        	$result[$from] = $word;

        	foreach($tos as $to)
        	{
            	$url = 'http://api.microsofttranslator.com/V2/Http.svc/Translate?text='.$word.'&from='.$from.'&to='.$to;

            	$ch = curl_init();
            	curl_setopt($ch, CURLOPT_URL, $url); 
            	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:bearer '.$access_token));
            	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);  
            	$rsp = curl_exec($ch); 

            	preg_match_all('/<string (.*?)>(.*?)<\/string>/s', $rsp, $matches);

            	$result[$to] = $matches[2][0];
        	}

        	return $result;
    	}
	}

	function getLocaleCodeForDisplayLanguage($name){
    	$languageCodes = array(
    	"aa" => "afar",
    	"ab" => "abkhazian",
    	"ae" => "avestan",
    	"af" => "afrikaans",
    	"ak" => "akan",
    	"am" => "amharic",
    	"an" => "aragonese",
    	"ar" => "arabic",
    	"as" => "assamese",
    	"av" => "avaric",
    	"ay" => "aymara",
    	"az" => "azerbaijani",
    	"ba" => "bashkir",
    	"be" => "belarusian",
    	"bg" => "bulgarian",
    	"bh" => "bihari",
    	"bi" => "bislama",
    	"bm" => "bambara",
    	"bn" => "bengali",
    	"bo" => "bibetan",
    	"br" => "breton",
    	"bs" => "bosnian",
    	"ca" => "catalan",
    	"ce" => "chechen",
    	"ch" => "camorro",
    	"co" => "corsican",
    	"cr" => "cree",
    	"cs" => "czech",
    	"cu" => "church slavic",
    	"cv" => "chuvash",
    	"cy" => "welsh",
    	"da" => "danish",
    	"de" => "german",
    	"dv" => "divehi",
    	"dz" => "dzongkha",
    	"ee" => "ewe",
    	"el" => "greek",
    	"en" => "english",
    	"eo" => "esperanto",
    	"es" => "spanish",
    	"et" => "estonian",
    	"eu" => "basque",
    	"fa" => "persian",
    	"ff" => "fulah",
    	"fi" => "finnish",
    	"fj" => "fijian",
    	"fo" => "faroese",
    	"fr" => "french",
    	"fy" => "western frisian",
    	"ga" => "irish",
    	"gd" => "scottish gaelic",
    	"gl" => "galician",
    	"gn" => "guarani",
    	"gu" => "gujarati",
    	"gv" => "manx",
    	"ha" => "hausa",
    	"he" => "hebrew",
    	"hi" => "hindi",
    	"ho" => "hiri motu",
    	"hr" => "croatian",
    	"ht" => "haitian",
    	"hu" => "hungarian",
    	"hy" => "armenian",
    	"hz" => "herero",
    	"ia" => "interlingua (international auxiliary language association)",
    	"id" => "indonesian",
    	"ie" => "interlingue",
    	"ig" => "igbo",
    	"ii" => "sichuan yi",
    	"ik" => "inupiaq",
    	"io" => "ido",
    	"is" => "icelandic",
    	"it" => "italian",
    	"iu" => "inuktitut",
    	"ja" => "japanese",
    	"jv" => "javanese",
    	"ka" => "georgian",
    	"kg" => "kongo",
    	"ki" => "kikuyu",
    	"kj" => "kwanyama",
    	"kk" => "kazakh",
    	"kl" => "kalaallisut",
    	"km" => "khmer",
    	"kn" => "kannada",
    	"ko" => "korean",
    	"kr" => "kanuri",
    	"ks" => "kashmiri",
    	"ku" => "kurdish",
    	"kv" => "komi",
    	"kw" => "cornish",
    	"ky" => "kirghiz",
    	"la" => "latin",
    	"lb" => "luxembourgish",
    	"lg" => "ganda",
    	"li" => "limburgish",
    	"ln" => "lingala",
    	"lo" => "lao",
    	"lt" => "lithuanian",
    	"lu" => "luba-katanga",
    	"lv" => "latvian",
    	"mg" => "malagasy",
    	"mh" => "marshallese",
    	"mi" => "maori",
    	"mk" => "macedonian",
    	"ml" => "malayalam",
    	"mn" => "mongolian",
    	"mr" => "marathi",
    	"ms" => "malay",
    	"mt" => "maltese",
    	"my" => "burmese",
    	"na" => "nauru",
    	"nb" => "norwegian bokmal",
    	"nd" => "north bdebele",
    	"ne" => "nepali",
    	"ng" => "ndonga",
    	"nl" => "dutch",
    	"nn" => "norwegian nynorsk",
    	"no" => "norwegian",
    	"nr" => "south ndebele",
    	"nv" => "navajo",
    	"ny" => "chichewa",
    	"oc" => "occitan",
    	"oj" => "ojibwa",
    	"om" => "oromo",
    	"or" => "oriya",
    	"os" => "ossetian",
    	"pa" => "panjabi",
    	"pi" => "pali",
    	"pl" => "polish",
    	"ps" => "pashto",
    	"pt" => "portuguese",
    	"qu" => "quechua",
    	"rm" => "raeto-romance",
    	"rn" => "kirundi",
    	"ro" => "romanian",
    	"ru" => "russian",
    	"rw" => "kinyarwanda",
    	"sa" => "sanskrit",
    	"sc" => "sardinian",
    	"sd" => "sindhi",
    	"se" => "northern sami",
    	"sg" => "sango",
    	"si" => "sinhala",
    	"sk" => "slovak",
    	"sl" => "slovenian",
    	"sm" => "samoan",
    	"sn" => "shona",
    	"so" => "somali",
    	"sq" => "albanian",
    	"sr" => "serbian",
    	"ss" => "swati",
    	"st" => "southern sotho",
    	"su" => "sundanese",
    	"sv" => "swedish",
    	"sw" => "swahili",
    	"ta" => "tamil",
    	"te" => "telugu",
    	"tg" => "tajik",
    	"th" => "thai",
    	"ti" => "tigrinya",
    	"tk" => "turkmen",
    	"tl" => "tagalog",
    	"tn" => "tswana",
    	"to" => "tonga",
    	"tr" => "turkish",
    	"ts" => "tsonga",
    	"tt" => "tatar",
    	"tw" => "twi",
    	"ty" => "tahitian",
    	"ug" => "uighur",
    	"uk" => "ukrainian",
    	"ur" => "urdu",
    	"uz" => "uzbek",
    	"ve" => "venda",
    	"vi" => "vietnamese",
    	"vo" => "volapuk",
    	"wa" => "walloon",
    	"wo" => "wolof",
    	"xh" => "xhosa",
    	"yi" => "yiddish",
    	"yo" => "yoruba",
    	"za" => "zhuang",
    	"zh" => "chinese",
    	"zu" => "zulu"
    	);
    	return array_search($name, $languageCodes);
	}

	$mode = $_GET;
	if(isset($_POST['From'])) $mode = $_POST;

	$from = $mode['From'];
	$message = $mode['Body'];

	$logEntry = ParseObject::create("Log");
	$logEntry->set("action","receive");
	$logEntry->set("number",$from);
	$logEntry->set("message",$message);
	$logEntry->save();

	$words = explode(" ",$message);

	if(check($words,array("navigate"))) {
		$origin = urlencode(trim(between("navigate from "," to ",strtolower($message))));
		$destination = urlencode(trim(after(" to ",strtolower($message))));
		$url = "http://dev.virtualearth.net/REST/v1/Routes?wp.0=".$origin."&wp.1=".$destination."&key=Al2LngxgqSPMLcfEWFntPD_aWP8tzVNaEoN0ky6XGJwXUrXJuSSCkKHeNJcIj2N_";
		$request = file_get_contents($url);
		$data = json_decode($request,true);
		if(count($data['resourceSets']) == 0) {
			reply("There doesn't appear to be a route from ".$origin." to ".$destination.".");
			exit();
		}
		$leg = $data['resourceSets'][0]['resources'][0]['routeLegs'][0];
		$steps = $leg['itineraryItems'];
		$totalDistance = 0;
		$totalDuration = 0;
		for($i = 0; $i < count($steps); $i ++) {
			$step = $steps[$i];
			$totalDistance += $step['travelDistance'];
			$totalDuration += $step['travelDuration'];
		}
		$totalDuration /= 60;
		$message =
			"Distance: ".intval($totalDistance)." miles\n".
			"Duration: ".intval($totalDuration)." minutes\n".
			"Steps: ".count($steps);
		for($i = 0; $i < count($steps); $i ++) {
			$step = $steps[$i];
			$instruction = $step['instruction']['text'];
			$distance = intval($step['travelDistance'])." miles";
			$duration = intval($step['travelDuration']/60)." minutes";
			$message = $message."\n".strval($i+1).". ".$instruction." (".$distance."/".$duration.").";
		}
		reply($message);
	}else if(check($words,array("remind"))) {
		$i = 0;
		$time = "asdf";
		while(strtotime($time) === false && strlen($time) > 0) {
			$time = trim(between(strtolower($words[$i])." "," to ",strtolower($message)));
			$i ++;
		}
		if(strlen($time) == 0) {
			reply("I couldn't figure out what time the reminder is for.");
			exit();
		}
		$time = strtotime($time);
		$reminder = trim(after("to",$message));
		$reminderEntry = ParseObject::create("Reminder");
		$reminderEntry->set("number",$from);
		$reminderEntry->set("time",$time);
		$reminderEntry->set("reminder",$reminder);
		$reminderEntry->save();
		shell_exec('python reminder.py '.strval($time-time()).' '.$from.' '.escapeshellarg($reminder).' > /dev/null 2>/dev/null &');
		reply("Reminder set for ".date("g:iA T",$time)." on ".date("j F",$time)." for \"".$reminder."\"");
	}else if(check($words,array("call"))) {
		$i = 0;
		$time = "asdf";
		while(strtotime($time) === false && strlen($time) > 0) {
			$time = trim(after(strtolower($words[$i]),strtolower($message)));
			$i ++;
		}
		if(strlen($time) == 0) {
			reply("I couldn't figure out what the time you want is.");
			exit();
		}
		$time = strtotime($time);
		$callEntry = ParseObject::create("Call");
		$callEntry->set("number",$from);
		$callEntry->set("time",$time);
		$callEntry->save();
		shell_exec('python call.py '.strval($time-time()).' '.$from.' > /dev/null 2>/dev/null &');
		reply("Okay, I will call you at ".date("g:iA T",$time)." on ".date("j F",$time).".");
	}else if(check($words,array("get")) && check_contains($words,array("stock","stocks"))) {
		$symbol = $words[count($words)-1];
		$request = file_get_contents("http://finance.yahoo.com/d/quotes.csv?s=".$symbol."&f=b3");
		reply(strtoupper($symbol)." is currently at $".$request);
	}else if(check($words,array("google"))) {
		$query = substr($message,7,strlen($message)-7);
		$freebase_api_key="AIzaSyDvBs_HWcZhR7HGv9vq-VXTCqmdJg8o8Qk";
		$service_url = 'https://www.googleapis.com/freebase/v1/search';
		$params = array(
			'query' => $query,
			'key' => $freebase_api_key
		);
		$url = $service_url . '?' . http_build_query($params);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = json_decode(curl_exec($ch), true);
		curl_close($ch);
		$compare = array();
		for($i = 0; $i < 5; $i ++) {
			if(isset($response['result'][$i])) array_push($compare,"\"".$response['result'][$i]['name']." - ".$response['result'][$i]['notable']['name']."\"");
		}
		if(count($compare) == 0) {
			reply("I couldn't find anything on \"".$query."\".");
			exit();
		}
		reply("Here's what I found:\n".implode("\n",$compare).".");
	}else if(check($words,array("get","latest","tweet")) || check($words,array("get","the","latest","tweet"))) {
		$name = $words[count($words)-1];
		$name = str_replace("@","",$name);
		$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
		$getField = "?screen_name=".$name."&count=1";
		$requestMethod = "GET";
		$twitter = new TwitterAPIExchange($settings);
		$result = json_decode($twitter->setGetField($getField)->buildOauth($url,$requestMethod)->performRequest(),true);
		if(!$result[0]['text']) {
			reply("I couldn't find any tweets from ".$name."!");
			exit();
		}
		reply($name." said \"".$result[0]['text']."\".");
	}else if((check($words,array("get")) || check($words,array("what")) || check($words,array("what's"))) && check_contains($words,array("weather","temperature"))) {
		$location = trim(preg_replace("/[?]/"," ",after_last(" in ",$message)));
		if(!$location) $location = trim(preg_replace("/[?]/"," ",after_last(" of ",$message)));
		$result = json_decode(file_get_contents("http://api.openweathermap.org/data/2.5/find?q=$location&type=like&mode=json&units=imperial"),true)['list'];
		if(count($result) == 0) {
			reply("I couldn't find that location.");
			exit();
		}
		$record = $result[0];
		$temperature = $record['main']['temp'];
		$weather = strtolower($record['weather'][0]['description']);
		$location = ucfirst($location);
		reply("It is $temperature degrees Fahrenheit and $weather in $location.");
	}else if((check($words,array("get")) || check($words,array("what")) || check($words,array("what's")))
				&& check_contains($words,array("trending","news"))
				&& check_contains($words,array("on","about"))) {
		$topic = trim(preg_replace("/[?]/"," ",after_last(" on ",$message)));
		if(!$topic) $topic = trim(preg_replace("/[?]/"," ",after_last(" about ",$message)));
		$acctKey = '1hwk+/F29FQV2FN87DBxgsNkq9V40Pl8/7mHcenMwBc=';
		$url = 'https://api.datamarket.azure.com/Bing/Search/News?$format=json&Query='.urlencode("'$topic'");
		$auth = base64_encode("$acctKey:$acctKey");
		$data = array(
		    'http' => array(
			    'request_fulluri' => true,
				'ignore_errors' => true,
				'header' => "Authorization: Basic $auth")
			);
		$context = stream_context_create($data);
		$response = file_get_contents($url,0,$context);
		$result = json_decode($response,true)['d']['results'];
		if(count($result) == 0) {
			reply("I couldn't find anything on $topic.");
		}
		$finalmessage = "Here are the latest headlines on $topic: \n";
		for($i = 0; $i < 5; $i ++) {
			if(strlen($finalmessage) > 320) break;
			if($result[$i]) {
				$entry = $result[$i];
				$title = $entry['Title'];
				$description = before(".",$entry['Description']);
				$finalmessage = $finalmessage."\"$title - $description\", \n";
			}
		}
		$finalmessage = trim($finalmessage);
		$finalmessage = substr($finalmessage,0,strlen($finalmessage)-1);
		reply($finalmessage);
	}else if(check($words,array("yo"))) {
		$name = $words[count($words)-1];
		$url = "https://api.justyo.co/yo/";
		$params = array(
			'username'=>$name,
			'api_token'=>"75ad8a77-99dd-4b49-9ab2-62649d65f49e"
		);
		$options = array(
		    'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($params),
			),
		);
		$context  = stream_context_create($options);
		file_get_contents($url, false, $context);
		reply("Yo'd ".strtoupper($name)." from JUSTEXT!");
	}else if(check($words,array("translate"))) {
		$words = urlencode(trim(between_last("translate","from",strtolower($message))));
		$fromlanguage = getLocaleCodeForDisplayLanguage(trim(between_last("from","to",strtolower($message))));
		$tolanguage = getLocaleCodeForDisplayLanguage(trim(after_last("to",strtolower($message))));
		$translator = new BingTranslation("JusText","uIPSg1j+YY92TzW8dPHfrPgryy0xq6bg0CnKXJVfYxw=");
		$result = $translator->translate($words,$fromlanguage,$tolanguage);
		if(!$result) {
			reply("I couldn't translate \"".$words."\".");
			exit();
		}
		reply("Here's what I got: \"".$result."\".");
	}else if(check($words,array("?"))) {
		reply("Available commands: \n\n".
			"navigate from [ORIGIN] to [DESTINATION], \n\n".
			"remind me at/in [TIME] to [REMINDER], \n\n".
			"call me at/in [TIME], \n\n".
			"google [QUERY], \n\n".
			"translate [WORDS] from [LANGUAGE] to [LANGUAGE], \n\n".
			"get stock price of [SYMBOL], \n\n".
			"what's the weather like in / get the temperature in [CITY], \n\n".
			"get the latest news on [TOPIC], \n\n".
			"get latest tweet from/by [NAME], \n\n".
			"yo [NAME]");
	}else if(check($words,array("hi")) || check($words,array("hello")) || check($words,array("hey"))) {
		reply("Greetings!");
	}else if(check($words,array("what's","up?"))) {
		reply("The ceiling. Hahaha. Ha. Get it? No? :(");
	}else if(check($words,array("you're","not","funny"))) {
		reply("You're mean.");
	}else if(check($words,array("what","is")) && check_contains($words,array("answer","life","universe","meaning"))) {
		reply("42");
	}else
	{
		reply("I'm not sure what you meant by that. Text me \"?\" for a list of all commands!");
	}

	function check($haystack,$needle) {
		if(count($needle) > count($haystack)) {
			return false;
		}
		for($i = 0; $i < count($needle); $i ++) {
			if(strtolower($haystack[$i]) != strtolower($needle[$i])) return false;
		}
		return true;
	}

	function check_contains($haystack,$needle) {
		$newhaystack = array();
		for($i = 0; $i < count($haystack); $i ++) {
			array_push($newhaystack,strtolower($haystack[$i]));
		}
		for($i = 0; $i < count($needle); $i ++) {
			if(in_array($needle[$i],$haystack)) return true;
		}
		return false;
	}

	function reply($message) {
		global $from;
		$logEntry = ParseObject::create("Log");
		$logEntry->set("action","send");
		$logEntry->set("number",$from);
		$logEntry->set("message",$message);
		$logEntry->save();
		print("<Response><Message>".$message."</Message></Response>");
	}

	function after ($this, $inthat) {
		if (!is_bool(strpos($inthat, $this))) return substr($inthat, strpos($inthat,$this)+strlen($this));
	}

	function after_last ($this, $inthat) {
		if (!is_bool(strrevpos($inthat, $this))) return substr($inthat, strrevpos($inthat, $this)+strlen($this));
	}

	function before ($this, $inthat) {
		return substr($inthat, 0, strpos($inthat, $this));
	}

	function before_last ($this, $inthat) {
		return substr($inthat, 0, strrevpos($inthat, $this));
	}

	function between ($this, $that, $inthat) {
		return before ($that, after($this, $inthat));
	}

	function between_last ($this, $that, $inthat) {
	 return after_last($this, before_last($that, $inthat));
	}

	function strrevpos($instr, $needle) {
		$rev_pos = strpos (strrev($instr), strrev($needle));
		if ($rev_pos===false) return false;
		else return strlen($instr) - $rev_pos - strlen($needle);
	}

?>
