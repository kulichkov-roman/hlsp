<?
$resProperty = CIBlockPropertyEnum::GetList(array("SORT" => "ASC"), Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => "HIT"));
while($item = $resProperty->Fetch()){
	$arHitPropertyValues[$item["ID"]] = $item;
}

$arResult["SPECIALS_BLOCK"] = array();
$arResult["SPECIALS_BLOCK"]["HTML"] = "";
$arResult["SPECIALS_BLOCK"]["OPENED"] = null;
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
	} elseif($arItem["CODE"]=="IN_STOCK"){
		sort($arResult["ITEMS"][$key]["VALUES"]);
		if($arResult["ITEMS"][$key]["VALUES"])
			$arResult["ITEMS"][$key]["VALUES"][0]["VALUE"]=$arItem["NAME"];
	} elseif($arItem["CODE"]=="CML2_MANUFACTURER"){

		global $APPLICATION;

		$environment = \Your\Environment\EnvironmentManager::getInstance();

		$curDir = $APPLICATION->GetCurDir();

		$arUrl = array_unique(explode('/', $curDir));
		array_splice($arUrl, sizeof($arUrl)-1);

		$arBrandNames = array();
		foreach($arItem['VALUES'] as &$arValue)
		{
			$arBrandNames[] = $arValue['VALUE'];
		}

		$arBrandSort = array();
		$arBrandSelect = array(
			'ID',
			'NAME',
			'CODE'
		);

		$arBrandFilter = array(
			'IBLOCK_ID' => $environment->get('brandIBlock'),
			'NAME' => $arBrandNames
		);

		$rsBrandElements = CIBlockElement::GetList(
			$arBrandSort,
			$arBrandFilter,
			false,
			false,
			$arSelect
		);

		$arBrandCodes = array();
		while($arBrandItem = $rsBrandElements->GetNext())
		{
			$arBrandCodes[$arBrandItem['NAME']] = $arBrandItem['CODE'];
		}

		foreach($arItem['VALUES'] as &$arValue)
		{
			$arBrandUrl   = $arUrl;
			$arBrandUrl[] = $arBrandCodes[$arValue['VALUE']];
			$arBrandUrl[] = '';
			
			$arValue['BRAND_URL'] = implode('/', $arBrandUrl);
		}
		unset($arValue);
	}
}
?>