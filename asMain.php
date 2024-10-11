<?php
/*
**************************************************************************************
*                         OpenOffice.org API-Scraper 2014                            *
**************************************************************************************
*      Autor: Thomas Gsell                                                           *
*      Datum: 08.09.2014                                                             *
*      Funktion: Eintrittspunkt fuer die Ausfuehrung des Scrapers.                   *
**************************************************************************************
*/


	ob_start();


	include 'asInput.tpl.php';
	include 'LIB_http.php';
	include 'LIB_resolve_addresses.php';
	include 'LIB_parse.php';

	
	set_time_limit(30);                           // Don't let PHP timeout
	
	//$SEED_URL        = "http://www.schrenk.com";    // First URL spider downloads
	//$MAX_PENETRATION = 1;                           // Set spider penetration depth
	//$FETCH_DELAY     = 1;                           // Wait one second between page fetches
	$ALLOW_OFFISTE   = false;                        // Don't allow spider to roam from the SEED_URL's domain
	//$spider_array = array();

	//prueft ob die gegebene Adresse ausgeschlossen werden muss.
	function exclude($address){
		if(stristr($address, "module-ix")){ return true; }
		if(stristr($address, "#")){ return true; }
		if(stristr($address, "e=")){ return true; }
		if(stristr($address, "index-1")){ return true; }
		if(stristr($address, "-xref")){ return true; }
		return false;
	}

	//sammelt Links von einer Seite und zwar zwischen einem bestimmten Bereich
	//der durch einen start- und einen end-String begrenzt wird.
	//Gibt ein Listen=Array mit allen Links der gegeben Webseite zurueck. 
	function harvest_links_between($url, $start, $end)
	{
		
		# Initialize
		global $DELAY;
		$link_array = array();
		    
		# Get page base for $url
		$page_base = get_base_page_address($url);
		    
		# Download webpage TODO: sollte nicht bei jedem Aufruf geladen werden.
		sleep($DELAY);          
		$downloaded_page = http_get($url, "");
		
		$range = "";
		if(strstr($downloaded_page['FILE'], $start))
		{
			$range = return_between($downloaded_page['FILE'], $start, $end, EXCL);
		}
		
		$anchor_tags = parse_array($range , "<a", "</a>", EXCL);
		# Put http attributes for each tag into an array
		for($xx=0; $xx<count($anchor_tags); $xx++)
		{
			$href = get_attribute($anchor_tags[$xx], "href");
			$resolved_addres = resolve_address($href, $page_base);
			
			if(!exclude($resolved_addres))
			{
				$link_array[] = $resolved_addres;
				//echo "Harvested: ".$resolved_addres." <br>";				
			}			
			
		}
    		return $link_array;
	}
	
	//Erntet alle Service und Interface-Links und gibt sie in einem Array zurueck.
	function doHarvesting($arr_urls)
	{
		
		$arr_result = array();
		for($x1=0; $x1<count($arr_urls); $x1++)
		{
			$arr_servicelinks = harvest_links_between($arr_urls[$x1], '<a name="ServicesSummary"', '</table>');
			$arr_result = array_merge($arr_result, doHarvesting($arr_servicelinks));

			$arr_result = array_merge($arr_result, $arr_servicelinks);
			$arr_interfacelinks = harvest_links_between($arr_urls[$x1], '<a name="InterfacesSummary"', '</table>');
			
			$arr_result = array_merge($arr_result, $arr_interfacelinks);
			
			for($y1=0; $y1<count($arr_interfacelinks); $y1++)
			{
				$arr_baseinterfacelinks = harvest_links_between($arr_interfacelinks[$y1], '<dt><b>Base Interfaces</b></dt>', '</pre></dd>');
				$arr_result = array_merge($arr_result, $arr_baseinterfacelinks);	
			}
			
		}
		return $arr_result;
	}
	
	
	function sumMethods($url)
	{
		//echo "<br>"."sumMethods: Funktion wurde aufgerufen.";
		global $DELAY;
		$arr_result = array();
		
		sleep($DELAY);          
		$downloaded_page = http_get($url, "");
		
		if(stristr($downloaded_page['FILE'], '<a name="MethodsDetails"/>'))
		{

			$rest = split_string($downloaded_page['FILE'], '<a name="MethodsDetails"/>', AFTER, INCL);
			$pattern = '/<td class="imdetail">/';

			$result_array = preg_split($pattern, $rest);

			$str_result = "";
			for($x1=0; $x1<count($result_array); $x1++){
				$str_result = $str_result.$x1.", ".$result_array[$x1]."<br>";
			}

			$pattern = '/(?sm)<table class="table-in-method" border="0">.*;<\/td>/';

			for($x1=0; $x1<count($result_array); $x1++)
			{
				$result_True = preg_match_all($pattern, $result_array[$x1], $arr_needles);
				if($result_True == true)
				{
					$arr_result[] = strip_tags($arr_needles[0][0]);
				}
			}
		}
		
		return $arr_result;
	}
	
	function sumProperties($url)
	{
		global $DELAY;
		$arr_result = array();
		
		sleep($DELAY);          
		$downloaded_page = http_get($url, "");
		
		if(stristr($downloaded_page['FILE'], '<a name="PropertiesDetails"/>'))
		{

			$rest = split_string($downloaded_page['FILE'], '<a name="PropertiesDetails"/>', AFTER, INCL);
			$pattern = '/<td class="imdetail">/';
			$result_array = preg_split($pattern, $rest);

			$str_result = "";
			for($x1=0; $x1<count($result_array); $x1++){
				$str_result = $str_result.$x1.", ".$result_array[$x1]."<br>";
			}

			$pattern = '/<td>.*<\/b>;<hr>/';

			for($x1=0; $x1<count($result_array); $x1++)
			{
				$result_True = preg_match_all($pattern, $result_array[$x1], $arr_needles);
				if($result_True == true)
				{
					$arr_result[] = strip_tags($arr_needles[0][0]);
				}
			}
		}
		
		return $arr_result;	
	}
	
	
	//holt aus jeder url die Methoden und die Properties heraus.
	//Es muessen Arrays angegeben werden.
	function doSummarizing($arr_urls, &$arr_methods, &$arr_properties)
	{
		if(is_array($arr_methods) && is_array($arr_properties)){		
			for($x1=0; $x1<count($arr_urls); $x1++)
			{
				$arr_methods = array_merge($arr_methods, sumMethods($arr_urls[$x1]));
				$arr_properties = array_merge($arr_properties, sumProperties($arr_urls[$x1]));
			}
		}
		//echo var_dump($arr_methods);
	}
	
	//Vereinheitlicht das gegebene Array.
	//Erst werden die doppelten Eintraege geloescht.
	//Anschliessend wird es sortiert. 
	//Die Aufgaben werden per Referenz erledigt.
	function unifyArray(&$arr)
	{
		$arr = array_keys(array_unique(array_flip($arr)));
		$arr_lowercase = array_map('strtolower', $arr);
		array_multisort($arr_lowercase, SORT_ASC, SORT_STRING, $arr);
	}
	

	
	//APIScraper-Main
	if(isset($_POST['command']))
	{
		if($_POST['command'] == 'Execute')
		{
			
			//INPUT
			//echo "asMain: Das Commando execute wurde gegeben.";
			//var_dump($_POST);
			//unset($_POST['commmand']);
			$rooturl = $_POST['rooturl'];
			$implname = $_POST['implementationname'];
			$servicenames[] = $_POST['servicename1'];
			$servicenames[] = $_POST['servicename2'];
			$servicenames[] = $_POST['servicename3'];
			$servicenames[] = $_POST['servicename4'];
			$servicenames[] = $_POST['servicename5'];
			$servicenames[] = $_POST['servicename6'];
			$servicenames[] = $_POST['servicename7'];
			$servicenames[] = $_POST['servicename8'];
			$servicenames[] = $_POST['servicename9'];
			$servicenames[] = $_POST['servicename10'];
			$servicenames[] = $_POST['servicename11'];
			$servicenames[] = $_POST['servicename12'];
			$servicenames[] = $_POST['servicename13'];
			$servicenames[] = $_POST['servicename14'];
			$servicenames[] = $_POST['servicename15'];
			$servicenames[] = $_POST['servicename16'];
			$servicenames[] = $_POST['servicename17'];
			$servicenames[] = $_POST['servicename18'];
			$servicenames[] = $_POST['servicename19'];
			$servicenames[] = $_POST['servicename20'];
			$servicenames[] = $_POST['servicename21'];
			$servicenames[] = $_POST['servicename22'];
			$servicenames[] = $_POST['servicename23'];
			$servicenames[] = $_POST['servicename24'];
			$servicenames[] = $_POST['servicename25'];
			
			echo '<i><b>API-URL: </b></i>'.$rooturl.'<br><br>';
			echo '<i><b>Implementation-Name: </b></i>'.$implname.'<br><br>';
			//var_dump($servicenames);
			echo "<i><b>Exported Services of ".$implname.": </b></i><br>";
			
			
			//GET SEED URLs
			$seedURLs = array();
			for($x1=0; $x1<count($servicenames); $x1++)
			{
				if($servicenames[$x1]!="")
				{
					echo $servicenames[$x1].'<br>';
					$service_path = preg_replace('/\./', '/', $servicenames[$x1]);	
					$seedURLs[] = $rooturl.$service_path.".html";
				}
			}
			echo "<br>";
						
			//HARVEST LINKS ON SERVICES AND INTERFACES
			
			
			$harvestedInterfaceURLs = doHarvesting($seedURLs);
			unifyArray($harvestedInterfaceURLs);
			$harvestedURLs = array_merge($seedURLs, $harvestedInterfaceURLs);						
			
			
			//EARNING AND SUMMARIZE ELEMENTS
			
			//jede Page muss durchgegangen werden.
			//die Tabelle mit den Elemente muss mit Parsen rausgenommen werden.
			//die Elemente muessen in verschiedene Arrays gelesen werden.
			
			$arr_methods = array();
			$arr_properties = array();			
			doSummarizing($harvestedURLs, $arr_methods, $arr_properties);
			
			unifyArray($arr_methods);
			unifyArray($arr_properties);
			
			//OUTPUT
			echo "<i><b>Implemented Interfaces of ".$implname.": </b></i><br>";
			for($x1=0; $x1<count($harvestedInterfaceURLs); $x1++)
			{
				//der Interface-Name muss zuerst herausgeparsed werden.
				$urlLen = strlen($rooturl);
				$interfaceName = substr( $harvestedInterfaceURLs[$x1], $urlLen);
				$interfaceName = str_replace('/', '.', $interfaceName);
				$interfaceName = str_replace('.html', '', $interfaceName);
				echo $interfaceName."<br>";
			}
			echo "<br><br>";			
			
			echo "<i><b>Methods of ".$implname.": </b></i><br>";
			for($x1=0; $x1<count($arr_methods); $x1++)
			{
				echo $arr_methods[$x1]."<br>";
			}
			echo "<br><br>";
			
			echo "<i><b>Properties of ".$implname.": </b></i><br>";
			for($x1=0; $x1<count($arr_properties); $x1++)
			{
				echo $arr_properties[$x1]."<br>";
			}
			
			
		}
	}
	else
	{
		echo $input_form;
	}
	
	
	ob_end_flush();   


?>