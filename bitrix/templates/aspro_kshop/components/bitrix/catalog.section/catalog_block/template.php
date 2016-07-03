<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if( count( $arResult["ITEMS"] ) >= 1 ){?>
	<?
	/*$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
	$arNotify = unserialize($notifyOption);*/
	?>
	<div class="catalog_block">	
		<?foreach($arResult["ITEMS"] as $arItem){?>
			<div class="basket_props_block" id="bx_basket_div_<?=$arItem["ID"];?>" style="display: none;">
				<?
						if (!empty($arItem['PRODUCT_PROPERTIES_FILL']))
						{
							foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
							{
				?>
					<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
				<?
								if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
									unset($arItem['PRODUCT_PROPERTIES'][$propID]);
							}
						}
						$arItem["EMPTY_PROPS_JS"]="Y";
						$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
						if (!$emptyProductProperties)
						{
							$arItem["EMPTY_PROPS_JS"]="N";
				?>
					<table>
				<?
							foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo)
							{
				?>
					<tr><td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
					<td>
				<?
								if(
									'L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']
									&& 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']
								)
								{
									foreach($propInfo['VALUES'] as $valueID => $value)
									{
										?><label><input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?></label><?
									}
								}
								else
								{
									?><select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
									foreach($propInfo['VALUES'] as $valueID => $value)
									{
										?><option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option><?
									}
									?></select><?
								}
				?>
					</td></tr>
				<?
							}
				?>
					</table>
				<?
						}
				?>
			</div>
			<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
			$totalCount = CKShop::GetTotalCount($arItem);
			$arQuantityData = CKShop::GetQuantityArray($totalCount);
			$arAddToBasketData = CKShop::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arParams);
			$pictureTitle=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"]);
			$pictureAlt=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"]);
			?>
			<?			
			if (($arParams["SHOW_MEASURE"]=="Y")&&($arItem["CATALOG_MEASURE"]))
			{ $arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arItem["CATALOG_MEASURE"]), false, false, array())->GetNext(); }
			?>
			<div class="catalog_item_wrapp">
				<div class="catalog_item <?=(($_GET['q'])) ? 's' : ''?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">				
					<div class="ribbons">
						<?if (is_array($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
							<?if( in_array("HIT", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"]) ):?><span class="ribon_hit"></span><?endif;?>
							<?if( in_array("RECOMMEND", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_recomend"></span><?endif;?>
							<?if( in_array("NEW", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_new"></span><?endif;?>
							<?if( in_array("STOCK", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_action"></span><?endif;?>
						<?endif;?>
					</div>
					<div class="image">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb">
							<?if( !empty($arItem["PREVIEW_PICTURE"]) ):?>
								<img border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$pictureAlt;?>" title="<?=$pictureTitle;?>" />
							<?elseif( !empty($arItem["DETAIL_PICTURE"])):?>
								<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => 165, "height" => 165 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
								<img border="0" src="<?=$img["src"]?>" alt="<?=$pictureAlt;?>" title="<?=$pictureTitle;?>" />		
							<?else:?>
								<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$pictureAlt;?>" title="<?=$pictureTitle;?>" />
							<?endif;?>
						</a>
					</div>
					
					<div class="item_info">
						<div class="item-title">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
						</div>
						<?if(strlen($arQuantityData["TEXT"])):?>
							<div class="availability-row"><?=$arQuantityData["HTML"]?></div>
						<?endif;?>
						<div class="cost clearfix">
							<?if( $arItem["OFFERS"]){?> 
								<div class="price_block">
									<?if($arItem["MIN_PRICE_OFFER"]["DISCOUNT_VALUE"]!=$arItem["MIN_PRICE_OFFER"]["VALUE"]){?>
										<div class="price"><?=GetMessage("CATALOG_FROM");?> <?=$arItem["MIN_PRICE_OFFER"]["PRINT_DISCOUNT_VALUE"]?></div>
										<div class="price discount">
											<?=GetMessage("CATALOG_FROM");?> <strike><?=$arItem["MIN_PRICE_OFFER"]["PRINT_VALUE"];?></strike>
										</div>
									<?}else{?>
										<div class="price"><?=GetMessage("CATALOG_FROM");?> <?=$arItem["MIN_PRICE_OFFER"]["PRINT_VALUE"]?></div>
									<?}?>
								</div>
							<?} elseif ( $arItem["PRICES"] ){?>
								<? $arCountPricesCanAccess = 0; foreach( $arItem["PRICES"] as $key => $arPrice ) { if($arPrice["CAN_ACCESS"]){$arCountPricesCanAccess++;} } ?>
								<?foreach( $arItem["PRICES"] as $key => $arPrice ){?>
									<?if( $arPrice["CAN_ACCESS"] ){?>
										<?$price = CPrice::GetByID($arPrice["ID"]); ?>
										<?if($arCountPricesCanAccess>1):?><div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div><?endif;?>
										<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]){?>
											<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"];?></div>
											<div class="price discount"><strike><?=$arPrice["PRINT_VALUE"]?></strike></div>
										<?}else{?><div class="price"><?=$arPrice["PRINT_VALUE"];?></div><?}?>
									<?}?>
								<?}?>				
							<?}?>
						</div>

						<div class="counter_wrapp">
							<div class="counter_block" data-item="<?=$arItem["ID"];?>">
								<?if($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && !count($arItem["OFFERS"]) && $arAddToBasketData["ACTION"] == "ADD"):?>
									<span class="minus">-</span>
									<input type="text" class="text" name="count_items" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
									<span class="plus" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
								<?endif;?>
							</div>
							<div class="buttons_block clearfix">
								<!--noindex-->
									<?=$arAddToBasketData["HTML"]?>
									<?if((!$arItem["OFFERS"] && $arParams["DISPLAY_WISH_BUTTONS"] != "N" && $arItem["CAN_BUY"]) || (!$arItem["OFFERS"] && $arParams["DISPLAY_COMPARE"] == "Y")):?>
										<div class="like_icons">								
											<?if(!$arItem["OFFERS"] && $arParams["DISPLAY_WISH_BUTTONS"] != "N" && $arItem["CAN_BUY"]):?>
												<a title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item" rel="nofollow" data-item="<?=$arItem["ID"]?>"><i></i></a>
											<?endif;?>
											<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
												<a title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item" rel="nofollow" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" href="<?=$arItem["COMPARE_URL"]?>"><i></i></a>
											<?endif;?>
										</div>
									<?endif;?>
								<!--/noindex-->
							</div>
						</div>						
					</div>

				</div>
			</div>
		<?}?>
	</div>
	<div class="bottom_nav">
		<?if( $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" ){?><?=$arResult["NAV_STRING"]?><?}?>
		<?
			$show=$arParams["PAGE_ELEMENT_COUNT"];
			if (array_key_exists("show", $_REQUEST))
			{
				if ( intVal($_REQUEST["show"]) && in_array(intVal($_REQUEST["show"]), array(20, 40, 60, 80, 100)) ) {$show=intVal($_REQUEST["show"]); $_SESSION["show"] = $show;}
				elseif ($_SESSION["show"]) {$show=intVal($_SESSION["show"]);}
			}
		?>
		<?if ($arParams["DISPLAY_SHOW_NUMBER"]!="N"){?>
			<div class="show_number">
				<span class="show_title"><?=GetMessage("CATALOG_DROP_TO")?></span>
				<span class="number_list">
					<?for( $i = 20; $i <= 100; $i+=20 ){?>
						<a rel="nofollow" <?if ($i == $show):?>class="current"<?endif;?> href="<?=$APPLICATION->GetCurPageParam('show='.$i, array('show', 'mode'))?>"><span><?=$i?></span></a>
					<?}?>
				</span>
			</div>
		<?}else{?>
			<br/><br/>
		<?}?>
	</div>
<?}elseif($arParams["FROM_SEARCH"]=="Y"){?>
	<?=GetMessage('EMPTY_CATALOG_DESCR_SEARCH');?>
	<script type="text/javascript">
		$('.sort_header').remove();
	</script>
<?}else{?>
	<p class="no_products"><?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?></p>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section.list",
		"sections_list",
		Array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
			"TOP_DEPTH" => 2,
			"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
			"SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
			"SECTIONS_LIST_PREVIEW_PROPERTY" => $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"],
			"SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_LIST_PICTURES"],
		), $component
	);?>
<?}?>
<?if ($arResult["SEO_DESCRIPTION"]){?>
	<div class="group_description">
		<img class="shadow" src="<?=SITE_TEMPLATE_PATH?>/images/shadow_bottom.png" />
		<div><?=$arResult["SEO_DESCRIPTION"]?></div>
	</div>
<?} else {?>
	<div class="group_description">
		<img class="shadow" src="<?=SITE_TEMPLATE_PATH?>/images/shadow_bottom.png" />
		<div><?=$arResult["DESCRIPTION"]?></div>
	</div>
<?}?>

<div class="clear"></div>
<script>
	var fRand = function() {return Math.floor(arguments.length > 1 ? (999999 - 0 + 1) * Math.random() + 0 : (0 + 1) * Math.random());};
	var waitForFinalEvent = (function () 
	{
	  var timers = {};
	  return function (callback, ms, uniqueId) 
	  {
		if (!uniqueId) {
		  uniqueId = fRand();
		}
		if (timers[uniqueId]) {
		  clearTimeout (timers[uniqueId]);
		}
		timers[uniqueId] = setTimeout(callback, ms);
	  };
	})();
	
	
	
	$('.catalog_block').ready(function()
	{
		$('.catalog_block').equalize({children: '.catalog_item .cost', reset: true}); 
		$('.catalog_block').equalize({children: '.catalog_item .item-title', reset: true}); 
		$('.catalog_block').equalize({children: '.catalog_item .counter_block', reset: true}); 
		$('.catalog_block').equalize({children: '.catalog_item', reset: true});
	})

</script>
