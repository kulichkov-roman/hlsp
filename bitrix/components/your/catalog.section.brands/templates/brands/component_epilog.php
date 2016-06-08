<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(sizeof($arResult["ID"]))
{
	/**
	 * Получить список всех разделов
	 */
	$arSort = array(
		"ID" => "DESC"
	);
	$arFilter = array(
		"IBLOCK_ID" => 11,
		"ID" => $arResult["ID"]
	);
	$arSelect = array(
		"ID",
		"NAME",
		"UF_SEC_SEO_TEXT"
	);

	$rsSection = CIBlockSection::GetList(
		$arSort,
		$arFilter,
		false,
		$arSelect,
		false
	);

	$arSection = array();
	if($arItem = $rsSection->GetNext())
	{
		$arSection['UF_SEC_SEO_TEXT'] = $arItem['UF_SEC_SEO_TEXT'];

		itc\CUncachedArea::startCapture();
		?>
		<div class="content-header__context">
			<?=$arSection["UF_SEC_SEO_TEXT"];?>
		</div>
		<?
		$showSectionDescription = itc\CUncachedArea::endCapture();
		itc\CUncachedArea::setContent("showSectionDescription", $showSectionDescription);
	}
}
?>