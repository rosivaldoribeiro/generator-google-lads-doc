<?php

$Fichier = "";

$idlist = $_POST['total'];

foreach (explode(',', $idlist) as $id) {
  $idsremove = explode('=', $id);
  $ids[] = $idsremove[0];
}

foreach ($ids as $index => $title)
{
    $foo[$title] = $title;
}

if (!$fp = fopen("https://www.autoradio-fr.com/gmerchantcenter.xml.php?id_shop=1&id_lang=2&country=FR&currency_iso=EUR&token=4bcaba91337cedf0549c957120554840","r")) {
	echo "Echec de l'ouverture du fichier";
	exit;
	}
	else {
	 while(!feof($fp)) {
	 // On récupère une ligne
	  $Ligne = fgets($fp,255);
	
	 // On affiche la ligne
	  
	
	 // On stocke l'ensemble des lignes dans une variable
	  $Fichier .= $Ligne;

	 }
	 fclose($fp); // On ferme le fichier
}

class XML2Array {

    private static $xml = null;
    private static $encoding = 'UTF-8';

    /**
     * Initialize the root XML node [optional]
     * @param $version
     * @param $encoding
     * @param $format_output
     */
    public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = true) {
        self::$xml = new DOMDocument($version, $encoding);
        self::$xml->formatOutput = $format_output;
        self::$encoding = $encoding;
    }

    /**
     * Convert an XML to Array
     * @param string $node_name - name of the root node to be converted
     * @param array $arr - aray to be converterd
     * @return DOMDocument
     */
    public static function &createArray($input_xml) {
        $xml = self::getXMLRoot();
        if(is_string($input_xml)) {
            $parsed = $xml->loadXML($input_xml);
            if(!$parsed) {
                throw new Exception('[XML2Array] Error parsing the XML string.');
            }
        } else {
            if(get_class($input_xml) != 'DOMDocument') {
                throw new Exception('[XML2Array] The input XML object should be of type: DOMDocument.');
            }
            $xml = self::$xml = $input_xml;
        }
        $array[$xml->documentElement->tagName] = self::convert($xml->documentElement);
        self::$xml = null;    // clear the xml node in the class for 2nd time use.
        return $array;
    }

    /**
     * Convert an Array to XML
     * @param mixed $node - XML as a string or as an object of DOMDocument
     * @return mixed
     */
    private static function &convert($node) {
        $output = array();

        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
                $output['@cdata'] = trim($node->textContent);
                break;

            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;

            case XML_ELEMENT_NODE:

                // for each child node, call the covert function recursively
                for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = self::convert($child);
                    if(isset($child->tagName)) {
                        $t = $child->tagName;

                        // assume more nodes of same kind are coming
                        if(!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    } else {
                        //check if it is not an empty text node
                        if($v !== '') {
                            $output = $v;
                        }
                    }
                }

                if(is_array($output)) {
                    // if only one node of its kind, assign it directly instead if array($value);
                    foreach ($output as $t => $v) {
                        if(is_array($v) && count($v)==1) {
                            $output[$t] = $v[0];
                        }
                    }
                    if(empty($output)) {
                        //for empty nodes
                        $output = '';
                    }
                }

                // loop through the attributes and collect them
                if($node->attributes->length) {
                    $a = array();
                    foreach($node->attributes as $attrName => $attrNode) {
                        $a[$attrName] = (string) $attrNode->value;
                    }
                    // if its an leaf node, store the value in @value instead of directly storing it.
                    if(!is_array($output)) {
                        $output = array('@value' => $output);
                    }
                    $output['@attributes'] = $a;
                }
                break;
        }
        return $output;
    }

    /*
     * Get the root XML node, if there isn't one, create it.
     */
    private static function getXMLRoot(){
        if(empty(self::$xml)) {
            self::init();
        }
        return self::$xml;
    }
}

$array = XML2Array::createArray($Fichier);


// Scale array to item
$array1 = $array['rss']['channel']['item'];

//echo $foo['FR3375'];
//print_r($foo['FR3375']);
//echo "</pre>";

$labels = array_values($ids);
//$values = array_values($array1['g:id']);
//header("Pragma: public");
//header("Expires: 0");
//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//header("Cache-Control: private",false);
//header("Content-Type: application/octet-stream");
//header("Content-Disposition: attachment; filename=\"filename.csv\";" );
//header("Content-Transfer-Encoding: binary");

$fp = fopen('google_ads_filter.csv', 'w');

$header = array('Campaign','Daily Budget','Campaign Type','Networks','Languages','DSA Website','DSA Language','DSA targeting source','Targeting expansion','ID','Location','Ad rotation','Targeting method',
'Exclusion method','Bid Strategy Type','Enhanced CPC','Max CPC','Ad Group Type','Ad Group','Ad type','Description line 1','Description line 2','Dynamic Ad Target Condition 1',
'Dynamic Ad Target Value 1','Dynamic Ad Target Condition 2','Dynamic Ad Target Value 2','Dynamic Ad Target Condition 3','Campaign Status','Ad Group Status','Status');

$topheader = array('ANA DSA TEST [DO NOT USE]','10','Search','"Google search;Search Partners"','fr','autoradio-fr.com','fr','Google','','','','Optimize for clicks','Location of presence',
'Location of presence','Manual CPC','Enabled','','','','','','','','','','','','','Paused','');

$subheader = array('ANA DSA TEST [DO NOT USE]','','','','','','','','','2250','France','','','','','','','','','','','','','','','','','','','');

fputcsv($fp, $header, ';');
fputcsv($fp, $topheader, ';');
fputcsv($fp, $subheader, ';');

foreach($array1 as $index => $value) {
    $optionValue = $index+1;
    $id = $value['g:id']; 
    $title = $value['title']['@cdata'];
    $link = $value['link']['@cdata'];
    
    // Remove url of google
	$url = $link;
	$url = explode("?", $url);
	$link = $url[0];
    
    if (isset($foo[$id])) {
    
    $contentheader[$id] = array("ANA DSA TEST [DO NOT USE]","","","","","","","","Disabled","","","","","","","","0,1","Dynamic","$title _ $id","","","","","","","","","Paused","Enabled");    
    $contentheaderutf8[$id] = array_map("utf8_decode", $contentheader[$id]);
    $contenttop[$id] = array("ANA DSA TEST [DO NOT USE]","","","","","","","","","","","","","","","","","","$title _ $id","","","","URL equals","$link","None","","None","Paused","Enabled","Enabled");    
    $contenttoputf8[$id] = array_map("utf8_decode", $contenttop[$id]);
    $contentfooter[$id] = array("ANA DSA TEST [DO NOT USE]","","","","","","","","","","","","","","","","","","$title _ $id","Expanded Dynamic Search Ad","Test numero 1","Test Numero 2","","","","","","Paused","Enabled","Enabled");    
    $contentfooterutf8[$id] = array_map("utf8_decode", $contentfooter[$id]);

    fputcsv($fp, $contentheaderutf8[$id], ';');
    fputcsv($fp, $contenttoputf8[$id], ';');
    fputcsv($fp, $contentfooterutf8[$id], ';');

    }
    
}



fclose($fp);


?>

<a href="google_ads_filter.csv">Download CSV</a>
