<?
$resProperty = CIBlockPropertyEnum::GetList(array("SORT" => "ASC"), Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => "HIT"));
while($item = $resProperty->Fetch()){
	$arHitPropertyValues[$item["ID"]] = $item;
}

$arResult["SPECIALS_BLOCK"] = array();
$arResult["SPECIALS_BLOCK"]["HTML"] = "";
$arResult["SPECIALS_BLOCK"]["OPENED"] = null;

/**
 * @param $arResult
 * @param $key
 * @param $arBrandCodes
 * @param $APPLICATION
 *
 * @return mixed
 */
function getResultExtended($arResult, $key, $arBrandCodes, $APPLICATION)
{
	$arResult['ITEMS'][$key]['VALUES'] = array();

	foreach ($arBrandCodes as $strBrandName => $arBrandCode) {
		$curDir = $APPLICATION->GetCurDir();

		$arUrl = array_unique(explode('/', $curDir));

		$arRatio = array(
			4 => 0,
			5 => 1,
			6 => 1,
			7 => 2,
			8 => 3,
		);

		if (!$arRatio[sizeof($arUrl)]) {
			$arUrl[] = 'brand';
		} else {
			array_splice($arUrl, sizeof($arUrl) - $arRatio[sizeof($arUrl)]);
		}

		$arBrandUrl = $arUrl;
		$arBrandUrl[] = $arBrandCode;
		$arBrandUrl[] = '';

		$arResult['ITEMS'][$key]['VALUES'][] = array(
			'BRAND_URL' => implode('/', $arBrandUrl),
			'VALUE'     => $strBrandName
		);
	}
	return $arResult;
}

foreach($arResult["ITEMS"] as $key => $arItem)
{
	if( $arItem["CODE"] == "HIT")
	{
		foreach($arItem["VALUES"] as $k => $ar){
			$arResult["SPECIALS_BLOCK"]["HTML"] .=
				'<div class="specials_'.strtolower($arHitPropertyValues[$k]["XML_ID"]).($ar["DISABLED"] ? ' disabled': '').'">
					<input type="checkbox" value="'.$ar["HTML_VALUE"].'" name="'.$ar["CONTROL_NAME"].'"
				id="'.$ar["CONTROL_ID"].'"'.($ar["CHECKED"]?'checked="checked"':'').' onclick="smartFilter.click(this)"'.($ar["DISABLED"]?' disabled':'').' />
					<label for="'.$ar["CONTROL_ID"].'"><i class="icon"></i><span>'.$ar["VALUE"].'</span></label>
				</div>';
		}
	} elseif($arItem["CODE"] == "IN_STOCK"){
		sort($arResult["ITEMS"][$key]["VALUES"]);
		if($arResult["ITEMS"][$key]["VALUES"])
			$arResult["ITEMS"][$key]["VALUES"][0]["VALUE"]=$arItem["NAME"];
	} elseif($arItem["CODE"] == "CML2_MANUFACTURER"){

		$arBrandCodes = HLSP\Helper\UrlHelper::getBrandsInDir();

		if(sizeof($arBrandCodes))
		{
			$arResult = getResultExtended($arResult, $key, $arBrandCodes, $APPLICATION);
		}
	}
}
?>
