<?/* include($_SERVER["DOCUMENT_ROOT"]."/seo.php");   */?>
<?/* include_once($_SERVER["DOCUMENT_ROOT"]."/p-seo.php");   */?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?> 
<?IncludeTemplateLangFile(__FILE__); global $APPLICATION, $TEMPLATE_OPTIONS; $fields = CSite::GetByID(SITE_ID)->Fetch();?>
<?if($GET["debug"]=="y"){error_reporting(E_ERROR | E_PARSE);}?>
<!DOCTYPE html>
<head>
	<title><?$APPLICATION->ShowTitle()?></title>
        <meta name="cmsmagazine" content="38d2170328e981e4d60ee986faaa509f" />
        <meta name="ktoprodvinul" content="b239417ebfdc8992" />
	<?$APPLICATION->ShowMeta("viewport");?>
	<?$APPLICATION->ShowMeta("HandheldFriendly");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
	<?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
	<?$APPLICATION->ShowHead();?>
	<?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject( $MESS, false ).')</script>', true);?>
	<?if(CModule::IncludeModule("aspro.kshop")) {CKShop::Start(SITE_ID);}?>
	<!--[if gte IE 9]><style type="text/css">.basket_button, .button30, .icon {filter: none;}</style><![endif]-->

	<?
	/**
	 * По требованию SEO.
	 * @todo перенестив в functions.php
	 */
	function inCatalog()
	{
		if(strpos($_SERVER["REQUEST_URI"], '/catalog/') === 0)
		{
			return true;
		}
		return false;
	}
	function inCatalogDetail()
	{
		global $APPLICATION;
		$curDir = $APPLICATION -> GetCurPage();

		$catalogDetailPattern = '~^/catalog/product/[^/]+/~';

		return preg_match($catalogDetailPattern, $curDir);
	}
	?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-54770835-1', 'auto');
  ga('send', 'pageview');
</script>
</head>	
	<body id="main">
		<?if(!CModule::IncludeModule("aspro.kshop")){?><center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center></body></html><?die();?><?}?>
		<!--noindex-->
			<div id="preload_wrapp" style="display:none;"> 
				<?$arImages = array("button_icons.png", "slider_pagination.png", "arrows_big.png", "like_icons.png", "arrows_small.png", "sort_icons.png");?>
				<?foreach($arImages as $image):?><img src="<?=SITE_TEMPLATE_PATH?>/images/<?=$image;?>" /><?endforeach;?>
			</div><? //it's for fast load some sprites ?>
		<!--/noindex-->
		<?$APPLICATION->IncludeComponent("aspro:theme.kshop", "", array("DEMO" => "N", "MODULE_ID" => "aspro.kshop"), false);?>
		<?CKShop::SetJSOptions();?>
		<?$isFrontPage = CSite::InDir(SITE_DIR.'index.php');?>
		<div class="wrapper <?=($isFrontPage ? "front_page" : "")?> basket_<?=strToLower($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"])?> head_<?=strToLower($TEMPLATE_OPTIONS["HEAD"]["CURRENT_VALUE"])?> banner_<?=strToLower($TEMPLATE_OPTIONS["BANNER_WIDTH"]["CURRENT_VALUE"])?>">
			<div id="panel"><?$APPLICATION->ShowPanel();?></div>	
			<div class="top-h-row">
				<div class="wrapper_inner">
					<div class="h-user-block" id="personal_block">
						<?$APPLICATION->IncludeComponent(
							"bitrix:system.auth.form", "top",
							Array(
								"REGISTER_URL" => SITE_DIR."auth/",
								"FORGOT_PASSWORD_URL" => SITE_DIR."auth/forgot-password",
								"PROFILE_URL" => SITE_DIR."personal/",
								"SHOW_ERRORS" => "Y"
							)
						);?>
					</div>
					<div class="search">
						<?$APPLICATION->IncludeComponent("bitrix:search.form", "top", array(
							"PAGE" => SITE_DIR."catalog/",
							"USE_SUGGEST" => "N",
							"USE_SEARCH_TITLE" => "Y",
							"INPUT_ID" => "title-search-input-1",
							"CONTAINER_ID" => "title-search-1",
							), false
						);?>
					</div>
					<div class="content_menu">
							<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top_content_multilevel", 
	array(
		"ROOT_MENU_TYPE" => "top_content",
		"MENU_CACHE_TYPE" => "Y",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "2",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N"
	),
	false
);?>
						</div>
					<div class="phone">
						<span class="phone_wrapper">
							<span class="icon"><i></i></span>
							<span class="phone_text">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE"),));?>
							</span>
						</span>
					</div>
				</div>
			</div>
			
			<header id="header">	
				<div class="wrapper_inner">	
					<table class="middle-h-row" cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
						<td class="logo_wrapp">
							<div class="logo">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/logo.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("LOGO"),));?>
							</div>
						</td>
						<td  class="center_block">
							<div class="main-nav">
									<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top_general_multilevel", 
	array(
		"ROOT_MENU_TYPE" => "top_general",
		"MENU_CACHE_TYPE" => "Y",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "2",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"IBLOCK_CATALOG_TYPE" => "aspro_kshop_catalog",
		"IBLOCK_CATALOG_ID" => "11",
		"IBLOCK_CATALOG_DIR" => "/catalog/"
	),
	false
);?>
								</div>
							<div class="search">
									<?$APPLICATION->IncludeComponent(
	"bitrix:search.form", 
	"top", 
	array(
		"PAGE" => SITE_DIR."catalog/",
		"USE_SUGGEST" => "N",
		"USE_SEARCH_TITLE" => "Y",
		"CONTAINER_ID" => "title-search-2",
		"INPUT_ID" => "title-search-input-2"
	),
	false
);?>
								</div>
						</td>
						<td class="basket_wrapp">
							<div class="header-cart-block" id="basket_line">
								<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("small-basket-block");?>
								<?if($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"] == "FLY" && !CSite::InDir(SITE_DIR.'basket/') && !CSite::InDir(SITE_DIR.'order/')):?>
									<script type="text/javascript">
									$(document).ready(function() {
										$.ajax({
											url: arKShopOptions['SITE_DIR'] + 'ajax/basket_fly.php',
											type: 'post',
											success: function(html){
												$('#basket_line').append(html);
											}
										});
									});
									</script>
								<?endif;?>
								<?$APPLICATION->IncludeFile(SITE_DIR."include/basket_top.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("BASKET_TOP")));?>
								<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("small-basket-block", "");?>
							</div>	
						</td>		
					</tr></table>
					<div class="catalog_menu">
							<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top_catalog_multilevel_new", 
	array(
		"ROOT_MENU_TYPE" => "top_catalog",
		"MENU_CACHE_TYPE" => "Y",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "3",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"IBLOCK_CATALOG_TYPE" => "aspro_kshop_catalog",
		"IBLOCK_CATALOG_ID" => "11"
	),
	false
);?>
						</div>
				</div>
			</header>
			
			<div class="wrapper_inner">				
				<section class="middle <?=($isFrontPage ? 'main' : '')?>">
					<div class="container">
						<?if(!$isFrontPage):?>
							<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "kshop", array(
								"START_FROM" => "0",
								"PATH" => "",
								"SITE_ID" => "-",
								"SHOW_SUBSECTIONS" => "N"
								),
								false
							);?>
							<?
							/**
							 * По требованияю SEO
							 */
							?>
							<?if(!inCatalog()){?>
								<h1 id="pagetitle"><?=$APPLICATION->ShowTitle(false);?></h1>
							<?} elseif(inCatalogDetail()) {
								?>
								<h1 id="pagetitle"><?=$APPLICATION->ShowTitle(false);?></h1>
								<?
							}?>
						<?endif;?>
						<div id="content" <?=($isFrontPage ? 'class="main"' : '')?>>
							<?if(CSite::InDir(SITE_DIR.'help/') || CSite::InDir(SITE_DIR.'company/') || CSite::InDir(SITE_DIR.'info/')):?>
								<div class="left_block">
								<?$APPLICATION->IncludeComponent("bitrix:menu", "left_menu", array(
									"ROOT_MENU_TYPE" => "left",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "3600000",
									"MENU_CACHE_USE_GROUPS" => "N",
									"MENU_CACHE_GET_VARS" => "",
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "left",
									"USE_EXT" => "N",
									"DELAY" => "N",
									"ALLOW_MULTI_SELECT" => "N" ),
									false, array( "ACTIVE_COMPONENT" => "Y" )
								);?>
							</div>
								<div class="right_block">
							<?endif;?>
							<?if($isFrontPage):?>
						</div>
					</div>
				</section>
			</div>
				<?endif;?>
						<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") $APPLICATION->RestartBuffer();?>