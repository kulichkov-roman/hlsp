<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") die();?>
<?IncludeTemplateLangFile(__FILE__);?>
<?if(CSite::InDir(SITE_DIR.'help/') || CSite::InDir(SITE_DIR.'company/') || CSite::InDir(SITE_DIR.'info/')):?>
	</div>
<?endif;?>
<?if(!$isFrontPage):?>
				</div>
			</div>
		</section>
	</div>
<?else:?>
	</div><?// forgoted tag in index.php <div class="wrapper_inner">?>
<?endif;?>
</div><?// <div class="wrapper">?>
		<footer id="footer" <?=($isFrontPage ? 'class="main"' : '')?>>
			<div class="footer_inner">
				<div class="line">
					<div class="wrapper_inner">
						<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_main", array(
							"ROOT_MENU_TYPE" => "bottom_main",
							"MENU_CACHE_TYPE" => "Y",
							"MENU_CACHE_TIME" => "3600",
							"MENU_CACHE_USE_GROUPS" => "Y",
							"MENU_CACHE_GET_VARS" => array(),
							"MAX_LEVEL" => "1",
							"USE_EXT" => "N",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N"
							),false
						);?>
						<span class="phone">
							<span class="phone_wrapper">
								<span class="icon"><i></i></span>
								<span  class="phone_text">
									<?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE"),));?>
								</span>
							</span>
						</span>
					</div>
				</div>
				<div class="wrapper_inner footer_bottom">
					<ul class="bottom_submenu">
						<li class="copy">
							<div class="copyright">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/copyright.php", Array(), Array("MODE" => "html", "NAME"  => GetMessage("COPYRIGHT"),));?>
							</div>
							<div class="social">
								<?$APPLICATION->IncludeComponent(
	"aspro:social.info", 
	"template1", 
	array(
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"VK" => "https://vk.com/hollyshopru",
		"FACE" => "https://www.facebook.com/hollyshopru",
		"TWIT" => "https://twitter.com/Hollyshopru",
		 "GOOGLE" => "https://plus.google.com/+Hollyshopru",
                 "INST" => "https://instagram.com/hollyshopru",
		"FLAMP" => "http://flamp.ru/hollyshop"
               
                	),
	false
);?> 
							</div>
						</li>
						<li>
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
								"ROOT_MENU_TYPE" => "bottom_company",
								"MENU_CACHE_TYPE" => "Y",
								"MENU_CACHE_TIME" => "3600",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"MENU_CACHE_GET_VARS" => array(),
								"MAX_LEVEL" => "1",
								"USE_EXT" => "N",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "N"
								),false
							);?>
						</li>
						<li>
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
								"ROOT_MENU_TYPE" => "bottom_info",
								"MENU_CACHE_TYPE" => "Y",
								"MENU_CACHE_TIME" => "3600",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"MENU_CACHE_GET_VARS" => array(),
								"MAX_LEVEL" => "1",
								"USE_EXT" => "N",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "N"
								),false
							);?>
						</li>
						<li>
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
								"ROOT_MENU_TYPE" => "bottom_help",
								"MENU_CACHE_TYPE" => "Y",
								"MENU_CACHE_TIME" => "3600",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"MENU_CACHE_GET_VARS" => array(),
								"MAX_LEVEL" => "1",
								"USE_EXT" => "N",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "N"
								),false
							);?>
						</li>
						<li class="stretch"></li>
					</ul>		
					<div class="bottom_left_icons">
						<span class="pay_system_icons">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/pay_system_icons.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE"),));?>
						</span>
						<div id="bx-composite-banner"></div>
					</div>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include1.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_1"),)); ?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include2.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_2"),)); ?>
				</div>					
			</div>	
		</footer>		
		<?
		if(!CSite::inDir(SITE_DIR."index.php")){
			if(strlen($APPLICATION->GetPageProperty('title')) > 1){
				$title = $APPLICATION->GetPageProperty('title');
			} 
			else{
				$title = $APPLICATION->GetTitle();
			}
			$APPLICATION->SetPageProperty("title", $title.' - '.$fields['SITE_NAME']);
		}
		else{
			if(strlen($APPLICATION->GetPageProperty('title')) > 1){
				$title =  $APPLICATION->GetPageProperty('title');
			}
			else{
				$title =  $APPLICATION->GetTitle();
			}
			if(!empty($title)){
				$APPLICATION->SetPageProperty("title", $title);
			}
			else{
				$APPLICATION->SetPageProperty("title", $fields['SITE_NAME']);
			}
		}
		?>
		<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("basketitems-block");?>
		<?
		if(CModule::IncludeModule("sale")){
			$dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array("ID", "PRODUCT_ID", "DELAY", "SUBSCRIBE", "CAN_BUY"));
			$basket_items = array();
			$delay_items = array();
			$subscribe_items = array();
			$compare_items = array();
			while($arBasketItems = $dbBasketItems->GetNext()){			
				if($arBasketItems["DELAY"]=="N" && $arBasketItems["CAN_BUY"] == "Y" && $arBasketItems["SUBSCRIBE"] == "N"){
					$basket_items[] = $arBasketItems["PRODUCT_ID"];
				} 
				elseif($arBasketItems["DELAY"]=="Y" && $arBasketItems["CAN_BUY"] == "Y" && $arBasketItems["SUBSCRIBE"] == "N"){
					$delay_items[] = $arBasketItems["PRODUCT_ID"];
				}
				elseif($arBasketItems["SUBSCRIBE"]=="Y"){
					$subscribe_items[] = $arBasketItems["PRODUCT_ID"];
				}
			}	
		}
		if(CModule::IncludeModule("currency")){
			$arCurPriceFormat = CCurrencyLang::GetCurrencyFormat('RUB');
			if(!isset($arCurPriceFormat["DECIMALS"])) $arCurPriceFormat["DECIMALS"] = 2;
			$arCurPriceFormat["DECIMALS"] = IntVal($arCurPriceFormat["DECIMALS"]);
			if(!isset($arCurPriceFormat["DEC_POINT"])) $arCurPriceFormat["DEC_POINT"] = ".";
			if(!empty($arCurPriceFormat["THOUSANDS_VARIANT"])){
				if($arCurPriceFormat["THOUSANDS_VARIANT"] == "N") $arCurPriceFormat["THOUSANDS_SEP"] = "";
				elseif($arCurPriceFormat["THOUSANDS_VARIANT"] == "D") $arCurPriceFormat["THOUSANDS_SEP"] = ".";
				elseif($arCurPriceFormat["THOUSANDS_VARIANT"] == "C") $arCurPriceFormat["THOUSANDS_SEP"] = ",";
				elseif($arCurPriceFormat["THOUSANDS_VARIANT"] == "S") $arCurPriceFormat["THOUSANDS_SEP"] = chr(32);
				elseif($arCurPriceFormat["THOUSANDS_VARIANT"] == "B") $arCurPriceFormat["THOUSANDS_SEP"] = '&nbsp;'; 
			}
			if(!isset($arCurPriceFormat["FORMAT_STRING"])) $arCurPriceFormat["FORMAT_STRING"] = "#";
		}
		?>
		<!-- new jsPriceFormat function -->
		<?
			if(CModule::IncludeModule("currency")){
				CJSCore::Init(array("currency")); 
				$currencyFormat = CCurrencyLang::GetFormatDescription(CSaleLang::GetLangCurrency(SITE_ID)); 
			}
			?>
			<?if(is_array($currencyFormat)):?>
				<script type="text/javascript">
				function jsPriceFormat(_number){
					BX.Currency.setCurrencyFormat("<?=CSaleLang::GetLangCurrency(SITE_ID);?>", <? echo CUtil::PhpToJSObject($currencyFormat, false, true); ?>);  
					return BX.Currency.currencyFormat(_number, "<?=CSaleLang::GetLangCurrency(SITE_ID);?>", true); 
				}
				</script>
			<?endif;?>
			<?if(is_array($arCurPriceFormat) && !empty($arCurPriceFormat) && false):?>
			<!-- old price format function -->
			<script type="text/javascript">
			function jsPriceFormat(_number){
				var decimal=<?=$arCurPriceFormat['DECIMALS']?>;
				var separator='<?=$arCurPriceFormat['THOUSANDS_SEP']?>';
				var decpoint = '<?=$arCurPriceFormat['DEC_POINT']?>';
				var format_string = '<?=$arCurPriceFormat['FORMAT_STRING']?>';
				var r=parseFloat(_number)
				var exp10=Math.pow(10,decimal);
				r=Math.round(r*exp10)/exp10;
				rr=Number(r).toFixed(decimal).toString().split('.');
				b=rr[0].replace(/(\d{1,3}(?=(\d{3})+(?:\.\d|\b)))/g,"\$1"+separator);
				r=(rr[1]?b+ decpoint +rr[1]:b);
				return format_string.replace('#', r);
			}
			</script>
		<?endif;?>
		<script type="text/javascript">
		$(document).ready(function(){
			<?if(is_array($basket_items) && !empty($basket_items)):?>
				<?foreach( $basket_items as $item ){?>
					$('.basket_button.to-cart[data-item=<?=$item?>]').hide();
					$('.basket_button.in-cart[data-item=<?=$item?>]').show();
				<?}?>
			<?endif;?>
			<?if(is_array($delay_items) && !empty($delay_items)):?>
				<?foreach( $delay_items as $item ){?>
					$('.wish_item[data-item=<?=$item?>]').addClass("added");
					if ($('.wish_item[data-item=<?=$item?>]').find(".value.added").length) { $('.wish_item[data-item=<?=$item?>]').find(".value").hide(); $('.wish_item[data-item=<?=$item?>]').find(".value.added").show(); }
				<?}?>
			<?endif;?>
			<?if(is_array($subscribe_items) && !empty($subscribe_items)):?>
				<?foreach( $subscribe_items as $item ){?>
					$('.basket_button.to-subscribe[data-item=<?=$item?>]').hide();
					$('.basket_button.in-subscribe[data-item=<?=$item?>]').show();
				<?}?>
			<?endif;?>
			<?if(is_array($compare_items) && !empty($compare_items)):?>
				<?foreach( $compare_items as $item ){?>
					$('.compare_item[data-item=<?=$item?>]').addClass("added");
					if ($('.compare_item[data-item=<?=$item?>]').find(".value.added").length) { $('.compare_item[data-item=<?=$item?>]').find(".value").hide(); $('.compare_item[data-item=<?=$item?>]').find(".value.added").show(); }
				<?}?>
			<?endif;?>
		});
		</script>
		<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("basketitems-block", "");?>
		<div id="content_new"></div>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter26159397 = new Ya.Metrika({id:26159397,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/26159397" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script async="async" src="https://w.uptolike.com/widgets/v1/zp.js?pid=1346049" type="text/javascript"></script>
	<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'cTrTnBphfH';
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
<!-- {/literal} END JIVOSITE CODE -->
</body>
</html>