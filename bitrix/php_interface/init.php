<?
/**
 * Подключение YourTools
 *
 * @author https://github.com/kulichkov-roman
 */
require_once 'vendor/YourTools/bootstrap.php';
require_once 'classes/AutoLoader.php';

\spl_autoload_register('\HLSP\AutoLoader::autoLoad');

$environment = \Your\Environment\EnvironmentManager::getInstance();

foreach ($environment->getConfigFileNames() as $fileName)
{
	$fileName = sprintf('%s/config/%s.php', __DIR__, $fileName);

	if (file_exists($fileName))
	{
		include_once $fileName;
	}
}
//include($_SERVER["DOCUMENT_ROOT"]."/seo.php");

// подключение переризатора
CModule::IncludeModule('itconstruct.uncachedarea');

// подпишем пользователя при его согласии
AddEventHandler('sale', 'OnSaleComponentOrderOneStepComplete', 'Subscrible');
function Subscrible($ID, $arFields)
{
    // если галка на подписку стоит
    if($_REQUEST['NEWSCHECKED'] == 'Y') { 
        $EMAIL =  $_REQUEST['ORDER_PROP_2'];
        $USER = $arFields['USER_ID'];
        // получим все активные рубрики 
        CModule::IncludeModule("subscribe");
        $RUB_ID = array();  
        $rsRubric = CRubric::GetList(array(), array("ACTIVE" => "Y"));
        while($arRubric = $rsRubric->GetNext()) {
            $RUB_ID[] = $arRubric['ID'];                    
        }   
        /* создадим массив на подписку */
        $subscr = new CSubscription;
        $arFields = Array(
            "USER_ID" => $USER,
            "FORMAT" => "html/text",
            "EMAIL" => $EMAIL,
            "ACTIVE" => "Y",
            "RUB_ID" => $RUB_ID,
            "SEND_CONFIRM" => "N",
            "CONFIRMED" => "Y"
        );
        $idsubrscr = $subscr->Add($arFields, SITE_ID);                              
    }
}

//AddEventHandler("main", "OnBeforeUserRegister", Array("Handlers", "OnBeforeUserRegisterHandler"));
AddEventHandler("main", "OnBeforeUserAdd", Array("Handlers", "OnBeforeUserAddHandler"));
class Handlers {
// function OnBeforeUserRegisterHandler(&$arFields) {
//  	$arFields['LOGIN'] = $arFields['EMAIL'];
// }
 
  function OnBeforeUserAddHandler(&$arFields) {
  	$arFields['LOGIN'] = $arFields['EMAIL'];

  }
}

function getSeoDataCatalog()
{
	$code = current(explode("?", $_SERVER["REQUEST_URI"]));
	$codeCache = 'seodata_' . substr(
			str_replace(array("/", "\\", ' ', "_"), '-', $code), 1
		);

	$cache = new iCache($codeCache, 9999999);

	if (!($SEO_DATA = $cache->getCache())) {
		CModule::IncludeModule("iblock");

		$arSelect = Array();
		$arFilter = Array(
			'IBLOCK_ID' => 14,
			'CODE'      => $code,
			"ACTIVE"    => "Y"
		);
		$res = CIBlockElement::GetList(
			Array(), $arFilter, false, array("nTopCount" => 1), array('ID')
		);
		if ($ob = $res->GetNext()) {
			$arFilter = Array('ID' => $ob['ID'], 'IBLOCK_ID' => 14);
			$res = CIBlockElement::GetList(
				Array('left_margin' => 'asc'), $arFilter, false,
				array("nTopCount" => 1)
			);
			if ($ob = $res->GetNextElement()) {
				$tmpArr = $ob->GetFields();
				$data = $tmpArr + array("PROP" => $ob->GetProperties());
				//$cache->SetCache($SEO_DATA);
				$FILTERS = array();

				if (!empty($data['PROP']['ELEMENTS']['VALUE'])) {
					$FILTERS['ID'] = $data['PROP']['ELEMENTS']['VALUE'];
				}

				if (!empty($data['PROP']['FILTERS']['VALUE'])) {
					foreach (
						$data['PROP']['FILTERS']['VALUE'] as $key => $value
					) {
						$filed = $data['PROP']['FILTERS']['DESCRIPTION'][$key];

						if (!empty($filed)) {
							$tmp = explode(",", trim($value));
							if (count($tmp)) {
								foreach ($tmp as $v) {
									$v = trim($v);
									if (strlen($v) > 0) {
										$FILTERS[$filed][] = $v;
									}
								}
							}
						}
					}
				}

				if (!empty($FILTERS)) {
					$SEO_DATA['filters'] = $FILTERS;
				}

				$BREADCRUMBS = array();
				if (!empty($data['PROP']['BREADCRUMBS']['VALUE'])) {
					foreach (
						$data['PROP']['BREADCRUMBS']['VALUE'] as $key => $value
					) {
						$url
							= $data['PROP']['BREADCRUMBS']['DESCRIPTION'][$key];
						$text = trim($value);
						if (!empty($value)) {
							$BREADCRUMBS[] = array($text, $url);
						}
					}
				}

				if (!empty($BREADCRUMBS)) {
					$SEO_DATA['breadcrumbs'] = $BREADCRUMBS;
				}

				$seoMetaKey = array('H1', 'TITLE', 'KEYWORDS', 'DESCRIPTION');
				foreach ($seoMetaKey as $code) {
					if (!empty($data['PROP'][$code]['VALUE'])) {
						$SEO_DATA['seo_meta'][$code] = trim(
							$data['PROP'][$code]['VALUE']
						);
					}
				}

				if ($tmpArr['PREVIEW_TEXT']) {
					$SEO_DATA['SEO_TEXT'] = $tmpArr['PREVIEW_TEXT'];
				}
			}

			$cache->SetCache($SEO_DATA);
		}
		//echo '<pre>'.print_r($FILTERS,1).'</pre>'.__FILE__.' # '.__LINE__;
	}

	return $SEO_DATA;
}



class iCache
{
   public $code;
   public $cache_time=3600000;
   public $cache_id;
   public $cache_path;


public function __construct($code, $cache_time)
   {
       if(!empty($cache_time))
		$this->cache_time = $cache_time;

	$this->cache_id = 'autocache_'.$code;
       $this->code = $code;

    $folder = str_replace('_','/',$code);
       if(substr($folder,0,1)!='/')
		$folder= '/'.$folder;
       if(substr($folder,strlen($folder)-1,1)=='/')
	$folder= substr($folder,0,strlen($folder)-1);

     $this->cache_path=$folder;
   }



 	public function clearCache($folderNum)// очистить кешь начиная с папки под номером  0 - удалить кешь из первой папки кеша
{

        $arrFoder 			= explode('/',$this->cache_path);
        $folderClearCache 	= implode('/',array_slice($arrFoder,1,$folderNum+1));
        if(!empty($folderClearCache))
	 {
        		$folderClearCache = '/'.$folderClearCache.'/';

			$cache = new CPHPCache();
			var_dump($cache->CleanDir($folderClearCache));
	 }
	 return  $folderClearCache;

}


 	public function getCache()
{

    global $USER;
  	if(is_object($USER))
  		if($USER->IsAdmin() && $_GET['clear_cache']=='Y')
               return  false;

   	$cache = new CPHPCache();
   	if($cache->InitCache($this->cache_time, $this->cache_id, $this->cache_path))
   	{
      // $ee = microtime(true);
   	   $var =  $cache->GetVars();
	   //echo '<pre>'.print_r($var,1).'</pre>'.__FILE__.' # '.__LINE__;
	  // echo (microtime(true) - $ee);

   	   return $var;
   	}
   return  false;
}

public function setCache($var)
{
    $cache = new CPHPCache();
	$cache->CleanDir($this->cache_path);
       $cache->StartDataCache($this->cache_time, $this->cache_id, $this->cache_path);
    $cache->EndDataCache($var);
	//echo '<pre>'.print_r($var,1).'</pre>'.__FILE__.' # '.__LINE__;
    return true;
}


}
    


//AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserUpdateHandler");
//AddEventHandler("main", "OnBeforeUserUpdate", "OnBeforeUserUpdateHandler");
//AddEventHandler("main", "OnBeforeUserAdd", "OnBeforeUserUpdateHandler");
//function OnBeforeUserUpdateHandler($arFields)
//{
//        $arFields["LOGIN"] = $arFields["EMAIL"];
//        return $arFields;
//}

AddEventHandler('main', 'OnEpilog', '_Check404Error', 1);
function _Check404Error()
{
   if (defined('ERROR_404') && ERROR_404=='Y' && !defined('ADMIN_SECTION') && !defined('NO_ERROR_404'))
   {
      GLOBAL $APPLICATION;
      $APPLICATION->RestartBuffer();
      require $_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/'.SITE_TEMPLATE_ID.'/header.php';
      require $_SERVER['DOCUMENT_ROOT'].'/404.php';
      require $_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/'.SITE_TEMPLATE_ID.'/footer.php';
   }
}
AddEventHandler("main", "OnEndBufferContent", "SeoFeatures"); 
function SeoFeatures(&$sContent) {
	if (file_exists($_SERVER['DOCUMENT_ROOT']."/p-seo.php")) {
		$aSEOData = include $_SERVER['DOCUMENT_ROOT']."/p-seo.php";
		if (isset($aSEOData['title']) && !empty($aSEOData['title'])) {
        	$aSEOData['title'] = htmlspecialchars($aSEOData['title']);
        	$sContent = preg_replace( '#<title>.*?</title>#siU', '<title>' . $aSEOData['title'] . '</title>', $sContent);
      	}
      	if (isset($aSEOData['descr']) && !empty($aSEOData['descr'])) {
        	$aSEOData['descr'] = htmlspecialchars($aSEOData['descr']);
        	$sContent = preg_replace( '#<meta[^>]+name[^>]{1,7}description[^>]*>#siU', '<meta name="description" content="' . $aSEOData['descr'] . '" />', $sContent);
      	}
      	if (isset($aSEOData['keywr']) && !empty($aSEOData['keywr'])) {
        	$aSEOData['keywr'] = htmlspecialchars($aSEOData['keywr']);
        	$sContent = preg_replace( '#<meta[^>]+name[^>]{1,7}keywords[^>]*>#siU', '<meta name="keywords" content="' . $aSEOData['keywr'] . '" />', $sContent);
      	}
      	if (isset($aSEOData['h1']) && !empty($aSEOData['h1'])) {
        	$sContent = preg_replace( '#(<h1[^>]*>).*(</h1>)#siU', '$1'.$aSEOData['h1'].'$2', $sContent);
      	}
      	if (isset($aSEOData['text_bottom']) && !empty($aSEOData['text_bottom'])) {
        	$sContent = preg_replace('#<!--text_bottom-->.*<!--/text_bottom-->#siU', '<div>'.$aSEOData['text_bottom'].'</div>', $sContent);
      	}
	}
}
?>