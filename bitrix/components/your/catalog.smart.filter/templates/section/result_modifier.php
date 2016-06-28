<?
$resProperty = CIBlockPropertyEnum::GetList(array("SORT" => "ASC"), Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => "HIT"));
while($item = $resProperty->Fetch()){
	$arHitPropertyValues[$item["ID"]] = $item;
}



function getBrandsInDir()
{
	global $APPLICATION;

	$environment = \Your\Environment\EnvironmentManager::getInstance();

	$curDir = $APPLICATION->GetCurDir();

	$arRatio = array(
		6 => 1,
		7 => 2,
		8 => 3,
	);

	$arBrandCodes = array();

	$arUrl = array_unique(explode('/', $curDir));

	$index = 0;
	if(sizeof($arUrl) >= 3)
	{
		$index = 3;
	}
	elseif (sizeof($arUrl) < 3)
	{
		$index = 2;
	}

	if($index)
	{
		array_splice($arUrl, sizeof($arUrl)-$arRatio[sizeof($arUrl)]);

		$arSecSort = array();
		$arSecFilter = array(
			"IBLOCK_ID" => $environment->get('catalogIBlock'),
			"CODE" => $arUrl[$index]
		);
		$arSecSelect = array(
			"ID",
			"NAME",
		);

		$rsSection =  \CIBlockSection::GetList(
			$arSecSort,
			$arSecFilter,
			false,
			$arSecSelect,
			false
		);

		$arSec = array();
		if ($arSecItem = $rsSection->Fetch()){}

		$arElemSort = array();
		$arElemSelect = array(
			'ID',
			'NAME',
			'PROPERTY_BRAND'
		);

		$arElemFilter = array(
			'IBLOCK_ID' => $environment->get('catalogIBlock'),
			'SECTION_ID' => $arSecItem['ID']
		);

		$rsElements = \CIBlockElement::GetList(
			$arElemSort,
			$arElemFilter,
			false,
			false,
			$arElemSelect
		);

		$arBrandIds = array();
		while($arElemItem = $rsElements->Fetch())
		{
			if($arElemItem['PROPERTY_BRAND_VALUE'])
			{
				$arBrandIds[] = $arElemItem['PROPERTY_BRAND_VALUE'];
			}
		}
		$arBrandIds = array_unique($arBrandIds);

		$arBrandSort = array();
		$arBrandSelect = array(
			'ID',
			'NAME',
			'CODE'
		);

		$arBrandFilter = array(
			'IBLOCK_ID' => $environment->get('brandIBlock'),
			'ID'        => $arBrandIds
		);

		$rsBrands = CIBlockElement::GetList(
			$arBrandSort,
			$arBrandFilter,
			false,
			false,
			$arBrandSelect
		);

		$arBrandCodes = array();
		while($arBrandItem = $rsBrands->Fetch())
		{
			$arBrandCodes[$arBrandItem['NAME']] = $arBrandItem['CODE'];
		}
	}
	return $arBrandCodes;
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
	}
	elseif($arItem["CODE"] == "CML2_MANUFACTURER")
	{
		if(sizeof($arItem["VALUES"]))
		{
			$arBrandCodes = HLSP\Helper\UrlHelper::getBrandsInDir();

			if(sizeof($arBrandCodes))
			{
				$arResult = getResultExtended($arResult, $key, $arBrandCodes, $APPLICATION);
			}
		}
	}
}
?>