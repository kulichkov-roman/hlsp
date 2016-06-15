<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
$arParams["ADD_SECTIONS_CHAIN"] = (isset($arParams["ADD_SECTIONS_CHAIN"])
    ? $arParams["ADD_SECTIONS_CHAIN"] : "Y");

CModule::IncludeModule("iblock");
$arPageParams = $arSection = $section = array();
if ($arResult["VARIABLES"]["SECTION_ID"] > 0) {
    $db_list = CIBlockSection::GetList(
        array(), array('GLOBAL_ACTIVE' => 'Y',
                       "ID"            => $arResult["VARIABLES"]["SECTION_ID"]),
        true, array("ID", "NAME", $arParams["SECTION_DISPLAY_PROPERTY"],
                    $arParams["LIST_BROWSER_TITLE"],
                    $arParams["LIST_META_KEYWORDS"],
                    $arParams["LIST_META_DESCRIPTION"],
                    $arParams["SECTION_PREVIEW_PROPERTY"], "IBLOCK_SECTION_ID")
    );
    $section = $db_list->GetNext();
} elseif (strlen(trim($arResult["VARIABLES"]["SECTION_CODE"])) > 0) {
    $db_list = CIBlockSection::GetList(
        array(), array('GLOBAL_ACTIVE' => 'Y',
                       "=CODE"         => $arResult["VARIABLES"]["SECTION_CODE"]),
        true, array("ID", "NAME", $arParams["SECTION_DISPLAY_PROPERTY"],
                    $arParams["LIST_BROWSER_TITLE"],
                    $arParams["LIST_META_KEYWORDS"],
                    $arParams["LIST_META_DESCRIPTION"],
                    $arParams["SECTION_PREVIEW_PROPERTY"], "IBLOCK_SECTION_ID")
    );
    $section = $db_list->GetNext();
}

if ($section) {
    $arSection["ID"] = $section["ID"];
    $arSection["NAME"] = $section["NAME"];
    $arSection["IBLOCK_SECTION_ID"] = $section["IBLOCK_SECTION_ID"];
    if ($section[$arParams["SECTION_DISPLAY_PROPERTY"]]) {
        $arDisplayRes = CUserFieldEnum::GetList(
            array(),
            array("ID" => $section[$arParams["SECTION_DISPLAY_PROPERTY"]])
        );
        if ($arDisplay = $arDisplayRes->GetNext()) {
            $arSection["DISPLAY"] = $arDisplay["XML_ID"];
        }
    }
    $arSection["SEO_DESCRIPTION"]
        = $section[$arParams["SECTION_PREVIEW_PROPERTY"]];
    $arPageParams["title"] = $section[$arParams["LIST_BROWSER_TITLE"]];
    $arPageParams["keywords"] = $section[$arParams["LIST_META_KEYWORDS"]];
    $arPageParams["description"] = $section[$arParams["LIST_META_DESCRIPTION"]];
}

if ($arPageParams) {
    foreach ($arPageParams as $code => $value) {
        if ($value) {
            $APPLICATION->SetPageProperty($code, $value);
        }
    }
}

global $KShopSectionID;
$KShopSectionID = $arSection["ID"];

$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(
    $arParams["IBLOCK_ID"], IntVal($arResult["VARIABLES"]["SECTION_ID"])
);
$values = $ipropValues->getValues();
$ishop_page_title = $values['SECTION_META_TITLE']
    ? $values['SECTION_META_TITLE'] : $arSection["NAME"];
$ishop_page_h1 = $values['SECTION_PAGE_TITLE'] ? $values['SECTION_PAGE_TITLE']
    : $arSection["NAME"];
if ($ishop_page_h1) {
    $APPLICATION->SetTitle($ishop_page_h1);
} else {
    $APPLICATION->SetTitle($arSection["NAME"]);
}
if ($ishop_page_title) {
    $APPLICATION->SetPageProperty("title", $ishop_page_title);
}
if ($values['SECTION_META_DESCRIPTION']) {
    $APPLICATION->SetPageProperty(
        "description", $values['SECTION_META_DESCRIPTION']
    );
}
if ($values['SECTION_META_KEYWORDS']) {
    $APPLICATION->SetPageProperty("keywords", $values['SECTION_META_KEYWORDS']);
}
$iSectionsCount = CIBlockSection::GetCount(
    array("SECTION_ID" => $arSection["ID"], "ACTIVE" => "Y",
          "GLOBAL_ACTIVE" => "Y")
);

$environment = \Your\Environment\EnvironmentManager::getInstance();

$arBrand = array();

if($arResult['VARIABLES']['BRAND'] <> '')
{
    $arSortBrand = array();
    $arSelectBrand = array(
        'ID',
        'NAME'
    );
    $arFilterBrand = array(
        'IBLOCK_ID' => $environment->get('brandIBlock'),
        'CODE'      => $arResult['VARIABLES']['BRAND']
    );

    $rsBrand = \CIBlockElement::GetList(
        $arSortBrand,
        $arFilterBrand,
        false,
        false,
        $arSelectBrand
    );

    if($arBrandItem = $rsBrand->Fetch())
    {
        $arBrand = $arBrandItem;
    }
}

$arSubSec = array();
$arSubSecItem = array();
if($arResult['VARIABLES']['SECTION_DL2'] <> '')
{
    $arSortSubSec = array(
        'SORT' => 'ASC'
    );
    $arSelectSubSec = array(
        'ID',
        'NAME',
        'PROPERTY_LINK_SECTION_CAT',
        'PROPERTY_LINK_ELEMENTS_CAT',
        'PROPERTY_LEVEL',
        'PROPERTY_GEN_URL'
    );
    $arFilterSubSec = array(
        'IBLOCK_ID' => $environment->get('seoSubsectionsIBlock'),
        'CODE' => $arResult['VARIABLES']['SECTION_DL2'],
        'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'],
        'PROPERTY_LEVEL_VALUE' => 2
    );

    $rsSubSec = \CIBlockElement::GetList(
        $arSortSubSec,
        $arFilterSubSec,
        false,
        false,
        $arSelectSubSec
    );

    $arElemIDs = array();
    if($arSubSecItem = $rsSubSec->Fetch())
    {
        $arSortElem = array(
            'SORT' => 'ASC'
        );
        $arSelectElem = array(
            'ID',
            'NAME',
            'PROPERTY_LEVEL'
        );
        $arFilterElem = array(
            'IBLOCK_ID'      => $environment->get('catalogIBlock'),
            'ID'             => $arSubSecItem['PROPERTY_LINK_ELEMENTS_CAT_VALUE'],
            'SECTION_ID'     => $arSubSecItem['PROPERTY_LINK_SECTION_CAT_VALUE'],
            'ACTIVE'         => 'Y'
        );

        $rsElem = \CIBlockElement::GetList(
            $arSortElem,
            $arFilterElem,
            false,
            false,
            $arSelectElem
        );

        while($arElemItem = $rsElem->Fetch())
        {
            $arElemIDs[] = $arElemItem['ID'];
        }

        if($arSubSecItem['PROPERTY_GEN_URL_VALUE'])
        {
            $APPLICATION->AddHeadString('<link href="'.$arSubSecItem['PROPERTY_GEN_URL_VALUE'].'" rel="canonical" />',true);
        }
    }
}
?>
<?if($iSectionsCount > 0):?>
    <script type="text/javascript" src="/js/jquery.main.js"></script>
    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/jquery.accordion.js"></script>
    <script src="/js/jquery01.js" type="text/javascript"></script>
    <style>
        ul li::before {
            content: ""!important;
        }
        .accordion li.active a.opener
        {
            background: linear-gradient(to bottom, #c32560 0%, #9c0f3a 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
            color: #fff;
        }
    </style>
    <div class="container">
        <div class="sidebar">
            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.section.list",
                "subsections_list_ito_15",
                Array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                    "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                    "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                    "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                    "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
                    "SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
                    "TOP_DEPTH" => "1",
                ),
                $component
            );?>
        </div>
        <div class="content">
            <?
            global $APPLICATION, $USER, $arrSeoFilter;
            $curDir = $APPLICATION->GetCurDir();

            $arrSeoFilter = array();

            switch($curDir)
            {
                case '/catalog/make_up/bb_ss_kremy_tonalnye_sredstva/missha_bb_cream/':
                    $arrSeoFilter = array(
                        "PROPERTY_BRAND" => 1202
                    );

                    $arResult["VARIABLES"]["SECTION_ID"] = 24;

                    break;
                case '/catalog/make_up/dlya_gub/tony_moly_tint/':
                    $arrSeoFilter = array(
                        "PROPERTY_BRAND" => 232
                    );

                    $arResult["VARIABLES"]["SECTION_ID"] = 69;

                    break;
                case '/catalog/make_up/bb_ss_kremy_tonalnye_sredstva/bb_cream_tony_moly/':
                    $arrSeoFilter = array(
                        "PROPERTY_BRAND" => 232
                    );

                    $arResult["VARIABLES"]["SECTION_ID"] = 52;

                    break;
                case '/catalog/for_hair/shampuni_i_konditsionery/kerasys/':
                    $arrSeoFilter = array(
                        "PROPERTY_BRAND" => 1110
                    );

                    $arResult["VARIABLES"]["SECTION_ID"] = 70;

                    break;
                case '/catalog/for_hair/lechenie/kerasys_maska/':
                    $arrSeoFilter = array(
                        "PROPERTY_BRAND" => 1110
                    );

                    $arResult["VARIABLES"]["SECTION_ID"] = 71;

                    break;
                case '/catalog/make_up/bb_ss_kremy_tonalnye_sredstva/mizon/':
                    $arrSeoFilter = array(
                        "PROPERTY_BRAND" => 235
                    );

                    $arResult["VARIABLES"]["SECTION_ID"] = 24;

                    break;
                case '/catalog/basecare/uvlazhnyayushchie_kremy/saem/':
                    $arrSeoFilter = array(
                        "PROPERTY_BRAND" => 854
                    );

                    $arResult["VARIABLES"]["SECTION_ID"] = 20;

                    break;
                case '/catalog/make_up/bb_ss_kremy_tonalnye_sredstva/skin79/':
                    $arrSeoFilter = array(
                        "PROPERTY_BRAND" => 233
                    );

                    $arResult["VARIABLES"]["SECTION_ID"] = 20;

                    break;
            }

            if(sizeof($arrSeoFilter) > 0)
            {
                $arParams["FILTER_NAME"] = 'arrSeoFilter';

            }

            $arSort = array();
            $arFilter = array(
                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                "ID" => $arResult["VARIABLES"]["SECTION_ID"],
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

            $name = "";
            $description = "";
            if($arItems = $rsSection->GetNext())
            {
                $name = $arItems['NAME'];
                $description = $arItems['UF_SEC_SEO_TEXT'];
            }

            $SEO_DATA = getSeoDataCatalog();

            if($SEO_DATA)
            {
                CHTTP::SetStatus("200 OK");
                define("NO_ERROR_404",'Y');
            }
            if(count($SEO_DATA['filters']) > 0)
            {
                $arrSeoFilter = $SEO_DATA['filters'];
                $arParams["FILTER_NAME"] = 'arrSeoFilter';
            }
            if(count($SEO_DATA['breadcrumbs']) > 0)
            {
                foreach($SEO_DATA['breadcrumbs'] as $val)
                    $APPLICATION->AddChainItem($val[0],$val[1]);
            }

            if(count($SEO_DATA['seo_meta']) > 0)
            {
                $tmp = trim($SEO_DATA['seo_meta']['H1']);
                if(strlen($tmp))
                {
                    $APPLICATION->SetTitle($tmp);
                    $name = $tmp;
                }
            }
            if(strlen($SEO_DATA['SEO_TEXT']))
            {
                if(intval($_GET['PAGEN_1'])<=0)
                    $description = $SEO_DATA['SEO_TEXT'];
            }
            ?>
            <div class="content-header">
                <h1><?=$name?></h1>
                <div class="content-header__context">
                    <?=$description?>
                </div>
            </div>
            <?$APPLICATION->IncludeComponent(
                'your:catalog.section.subsections',
                'subsections',
                Array(
                    'SEF_URL_TEMPLATES' => $arParams['SEF_URL_TEMPLATES'],
                    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                    'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'],
                    'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
                    'ELEMENT_SORT_FIELD' => $sort,
                    'ELEMENT_SORT_ORDER' => $sort_order,
                    'FILTER_NAME' => $arParams['FILTER_NAME'],
                    'INCLUDE_SUBSECTIONS' => $arParams['INCLUDE_SUBSECTIONS'],
                    'PAGE_ELEMENT_COUNT' => $show,
                    'LINE_ELEMENT_COUNT' => $arParams['LINE_ELEMENT_COUNT'],
                    'PROPERTY_CODE' => $arParams['LIST_PROPERTY_CODE'],
                    'OFFERS_FIELD_CODE' => $arParams['LIST_OFFERS_FIELD_CODE'],
                    'OFFERS_PROPERTY_CODE' => $arParams['LIST_OFFERS_PROPERTY_CODE'],
                    'OFFERS_SORT_FIELD' => $arParams['OFFERS_SORT_FIELD'],
                    'OFFERS_SORT_ORDER' => $arParams['OFFERS_SORT_ORDER'],
                    'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
                    'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['element'],
                    'BASKET_URL' => $arParams['BASKET_URL'],
                    'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
                    'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
                    'PRODUCT_QUANTITY_VARIABLE' => 'quantity',
                    'PRODUCT_PROPS_VARIABLE' => 'prop',
                    'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],
                    'AJAX_MODE' => $arParams['AJAX_MODE'],
                    'AJAX_OPTION_JUMP' => $arParams['AJAX_OPTION_JUMP'],
                    'AJAX_OPTION_STYLE' => $arParams['AJAX_OPTION_STYLE'],
                    'AJAX_OPTION_HISTORY' => $arParams['AJAX_OPTION_HISTORY'],
                    'CACHE_TYPE' =>$arParams['CACHE_TYPE'],
                    'CACHE_TIME' => $arParams['CACHE_TIME'],
                    'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                    'META_KEYWORDS' => $arParams['LIST_META_KEYWORDS'],
                    'META_DESCRIPTION' => $arParams['LIST_META_DESCRIPTION'],
                    'BROWSER_TITLE' => $arParams['LIST_BROWSER_TITLE'],
                    'ADD_SECTIONS_CHAIN' => $arParams['ADD_SECTIONS_CHAIN'],
                    'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
                    'DISPLAY_COMPARE' => $arParams['USE_COMPARE'],
                    'SET_TITLE' => $arParams['SET_TITLE'],
                    'SET_STATUS_404' => $arParams['SET_STATUS_404'],
                    'CACHE_FILTER' => $arParams['CACHE_FILTER'],
                    'PRICE_CODE' => $arParams['PRICE_CODE'],
                    'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
                    'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
                    'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
                    'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                    'OFFERS_CART_PROPERTIES' => array(),
                    'DISPLAY_TOP_PAGER' => $arParams['DISPLAY_TOP_PAGER'],
                    'DISPLAY_BOTTOM_PAGER' => $arParams['DISPLAY_BOTTOM_PAGER'],
                    'PAGER_TITLE' => $arParams['PAGER_TITLE'],
                    'PAGER_SHOW_ALWAYS' => $arParams['PAGER_SHOW_ALWAYS'],
                    'PAGER_TEMPLATE' => $arParams['PAGER_TEMPLATE'],
                    'PAGER_DESC_NUMBERING' => $arParams['PAGER_DESC_NUMBERING'],
                    'PAGER_DESC_NUMBERING_CACHE_TIME' => $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'],
                    'PAGER_SHOW_ALL' => $arParams['PAGER_SHOW_ALL'],
                    'AJAX_OPTION_ADDITIONAL' => '',
                    'ADD_CHAIN_ITEM' => 'N',
                    'SHOW_QUANTITY' => $arParams['SHOW_QUANTITY'],
                    'SHOW_QUANTITY_COUNT' => $arParams['SHOW_QUANTITY_COUNT'],
                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                    'USE_STORE' => $arParams['USE_STORE'],
                    'MAX_AMOUNT' => $arParams['MAX_AMOUNT'],
                    'MIN_AMOUNT' => $arParams['MIN_AMOUNT'],
                    'USE_MIN_AMOUNT' => $arParams['USE_MIN_AMOUNT'],
                    'USE_ONLY_MAX_AMOUNT' => $arParams['USE_ONLY_MAX_AMOUNT'],
                    'DISPLAY_WISH_BUTTONS' => $arParams['DISPLAY_WISH_BUTTONS'],
                    'DEFAULT_COUNT' => $arParams['DEFAULT_COUNT'],
                    'LIST_DISPLAY_POPUP_IMAGE' => $arParams['LIST_DISPLAY_POPUP_IMAGE'],
                    'SHOW_MEASURE' => $arParams['SHOW_MEASURE'],
                    'SHOW_HINTS' => $arParams['SHOW_HINTS'],
                    'SHOW_SECTIONS_LIST_PREVIEW' => $arParams['SHOW_SECTIONS_LIST_PREVIEW'],
                    'SECTIONS_LIST_PREVIEW_PROPERTY' => $arParams['SECTIONS_LIST_PREVIEW_PROPERTY'],
                    'SHOW_SECTION_LIST_PICTURES' => $arParams['SHOW_SECTION_LIST_PICTURES'],
                    'ELEMENTS_IDS' => $arElemIDs,
                ),
                $component
            );
            ?>
        </div>
    </div>
<?else:?>
    <div class="left_block catalog">
        <?if('Y' == $arParams['USE_FILTER']):?>
            <?
            if(CModule::IncludeModule("iblock")){
                $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y");
                if(0 < intval($arResult["VARIABLES"]["SECTION_ID"])){
                    $arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
                }
                elseif('' != $arResult["VARIABLES"]["SECTION_CODE"]){
                    $arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
                }
                $obCache = new CPHPCache();
                if($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog")){
                    $arCurSection = $obCache->GetVars();
                }
                else{
                    $arCurSection = array();
                    $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));
                    if(defined("BX_COMP_MANAGED_CACHE")){
                        global $CACHE_MANAGER;
                        $CACHE_MANAGER->StartTagCache("/iblock/catalog");
                        if($arCurSection = $dbRes->GetNext()){
                            $CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);
                        }
                        $CACHE_MANAGER->EndTagCache();
                    }
                    else{
                        if(!$arCurSection = $dbRes->GetNext()){
                            $arCurSection = array();
                        }
                    }
                    $obCache->EndDataCache($arCurSection);
                }
            }
            global $arrFilterBrands;
            $arrFilterBrands = array(
                'PROPERTY_BRAND' => $arBrand['ID'],
                'ID' => $arElemIDs
            );
            $APPLICATION->IncludeComponent(
                "your:catalog.smart.filter",
                "brands",
                Array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "SECTION_ID" => $arCurSection['ID'],
                    "FILTER_NAME" => $arParams["FILTER_NAME"],
                    "PRICE_CODE" => "",
                    "CACHE_TYPE" => "N",
                    "CACHE_TIME" => "36000000",
                    "CACHE_NOTES" => "",
                    "CACHE_GROUPS" => "Y",
                    "SAVE_IN_SESSION" => "N",
                    "XML_EXPORT" => "Y",
                    "SECTION_TITLE" => "NAME",
                    "SECTION_DESCRIPTION" => "DESCRIPTION",
                    "SHOW_HINTS" => $arParams["SHOW_HINTS"],
                ),
                $component, array('HIDE_ICONS' => 'N')
            );
            ?>
        <?endif;?>
        <?if($arParams["SHOW_SECTION_SIBLINGS"] == "Y"):?>
            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.section.list",
                "internal_sections_list",
                Array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    //"SECTION_ID" => $arSection["IBLOCK_SECTION_ID"],
                    //"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                    "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                    "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                    "ADD_SECTIONS_CHAIN" => "N",
                    "SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
                    "TOP_DEPTH" => "3",
                ),$component
            );?>
        <?endif;?>
        <?$APPLICATION->IncludeComponent("bitrix:sale.viewed.product", "main", array(
            "VIEWED_COUNT" => "3",
            "VIEWED_NAME" => "Y",
            "VIEWED_IMAGE" => "Y",
            "VIEWED_PRICE" => "Y",
            "VIEWED_CURRENCY" => "default",
            "VIEWED_CANBUY" => "N",
            "VIEWED_CANBASKET" => "N",
            "BASKET_URL" => SITE_DIR."basket/",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "SET_TITLE" => "N"
        ),
            false
        );?>
        <?$APPLICATION->IncludeComponent("aspro:com.banners", "small_banners", array(
            "IBLOCK_TYPE" => $arParams["IBLOCK_BANNERS_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_BANNERS_ID"],
            "TYPE_BANNERS_IBLOCK_ID" => $arParams["IBLOCK_BANNERS_TYPE_ID"],
            "TYPE_BANNERS" => $arParams["IBLOCK_SMALL_BANNERS_TYPE_ID"],
            "NEWS_COUNT" => "20",
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "SORT_BY2" => "SORT",
            "SORT_ORDER2" => "ASC",
            "PROPERTY_CODE" => array(
                0 => "TARGETS",
                1 => "URL_STRING",
                2 => "",
            ),
            "CHECK_DATES" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000"
        ),
            false
        );?>
    </div>
    <div class="compare_small" id="compare_small"></div>
    <div class="right_block clearfix catalog">
        <div class="content-header">
            <h1><?=$arSubSecItem['NAME']?></h1>
        </div>
        <?if('Y' == $arParams['USE_FILTER']):?>
            <div class="adaptive_filter">
                <a class="filter_opener<?=($_REQUEST["set_filter"] == "y" ? " active" : "")?>"><i></i><span><?=GetMessage("CATALOG_SMART_FILTER_TITLE")?></span></a>
            </div>
            <script type="text/javascript">
                $(".filter_opener").click(function(){
                    $(this).toggleClass("opened");
                    $(".bx_filter_vertical").slideToggle(333);
                });
            </script>
        <?endif;?>
        <?
        $arSortSubSec = array();
        $arSelectSubSec = array(
            'ID',
            'NAME',
            'CODE',
            'PROPERTY_LINK_SECTION_CAT',
            'PROPERTY_LINK_ELEMENTS_CAT',
            'PROPERTY_LEVEL',
            'PROPERTY_LINK_BRAND'
        );
        $arFilterSubSec = array(
            'IBLOCK_ID' => $environment->get('seoSubsectionsIBlock'),
            'PROPERTY_LINK_SECTION_CAT' => $arResult['VARIABLES']['SECTION_ID'],
            'PROPERTY_LEVEL_VALUE' => 2,
            'PROPERTY_LINK_BRAND' => $arBrand['ID']
        );
        $rsSubSec = \CIBlockElement::GetList(
            $arSortSubSec,
            $arFilterSubSec,
            false,
            false,
            $arSelectSubSec
        );

        $curDir = $APPLICATION->GetCurDir();

        $arUrl = array_unique(explode('/', $curDir));
        array_splice($arUrl, sizeof($arUrl)-1);

        $subSecUrl = '';

        $arSubSec = array();
        while($arSubSecItem = $rsSubSec->Fetch())
        {
            $arSubSecUrl   = $arUrl;
            $arSubSecUrl[] = $arSubSecItem['CODE'];
            $arSubSecUrl[] = '';
            $arSubSecUrl = implode('/', $arSubSecUrl);

            $arSubSec[] = array(
                'NAME' => $arSubSecItem['NAME'],
                'SUB_SEC_URL' => $arSubSecUrl ? $arSubSecUrl : 'javascript:void(0)'
            );
        }
        ?>
        <?if(sizeof($arSubSec)){?>
            <div class="product-group-links">
                <ul class="product-group-links__list">
                    <?foreach($arSubSec as $arItem){?>
                        <li class="product-group-links__item">
                            <a href="<?=$arItem['SUB_SEC_URL']?>">
                                <?=$arItem['NAME']?>
                            </a>
                        </li>
                    <?}?>
                    <?
                    $arSubSecUrl   = $arUrl;
                    $arSubSecUrl[] = '';
                    $strSubSecUrl = implode('/', $arSubSecUrl);
                    ?>
                    <li class="product-group-links__item">
                        <a href="<?=$strSubSecUrl?>">Все</a>
                    </li>
                </ul>
            </div>
        <?}?>
        <div class="sort_header">
            <!--noindex-->
            <?
            $arDisplays = array("block", "list", "table");
            if(array_key_exists("display", $_REQUEST) || (array_key_exists("display", $_SESSION)) || $arParams["DEFAULT_LIST_TEMPLATE"])
            {
                if($_REQUEST["display"] && (in_array(trim($_REQUEST["display"]), $arDisplays)))
                {
                    $display = trim($_REQUEST["display"]);
                    $_SESSION["display"]=trim($_REQUEST["display"]);
                }
                elseif($_SESSION["display"] && (in_array(trim($_SESSION["display"]), $arDisplays)))
                {
                    $display = $_SESSION["display"];
                }
                elseif($arSection["DISPLAY"]){
                    $display = $arSection["DISPLAY"];
                }
                else
                {
                    $display = $arParams["DEFAULT_LIST_TEMPLATE"];
                }
            }
            else
            {
                $display = "block";
            }
            $template = "catalog_".$display;
            ?>
            <div class="sort_filter">
                <?
                $arAvailableSort = array();
                $arSorts = $arParams["SORT_BUTTONS"];
                if(in_array("POPULARITY", $arSorts)){
                    $arAvailableSort["SHOWS"] = array("SHOWS", "desc");
                }
                if(in_array("NAME", $arSorts)){
                    $arAvailableSort["NAME"] = array("NAME", "asc");
                }
                if(in_array("PRICE", $arSorts)){
                    $arSortPrices = $arParams["SORT_PRICES"];
                    if($arSortPrices == "MINIMUM_PRICE" || $arSortPrices == "MAXIMUM_PRICE"){
                        $arAvailableSort["PRICE"] = array("PROPERTY_".$arSortPrices, "desc");
                    }
                    else{
                        $price = CCatalogGroup::GetList(array(), array("NAME" => $arParams["SORT_PRICES"]), false, false, array("ID", "NAME"))->GetNext();
                        $arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
                    }
                }
                if(in_array("QUANTITY", $arSorts)){
                    $arAvailableSort["QUANTITY"] = array("QUANTITY", "desc");
                }

                $sort = "SHOWS";
                if((array_key_exists("sort", $_REQUEST) && array_key_exists(ToUpper($_REQUEST["sort"]), $arAvailableSort)) || (array_key_exists("sort", $_SESSION) && array_key_exists(ToUpper($_SESSION["sort"]), $arAvailableSort)) || $arParams["ELEMENT_SORT_FIELD"]){
                    if($_REQUEST["sort"])
                    {
                        $sort = ToUpper($_REQUEST["sort"]);
                        $_SESSION["sort"] = ToUpper($_REQUEST["sort"]);
                    }
                    elseif($_SESSION["sort"])
                    {
                        $sort = ToUpper($_SESSION["sort"]);
                    }
                    else
                    {
                        $sort = ToUpper($arParams["ELEMENT_SORT_FIELD"]);
                    }
                }

                $sort_order=$arAvailableSort[$sort][1];
                if((array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc"))) || (array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc")) ) || $arParams["ELEMENT_SORT_ORDER"]){
                    if($_REQUEST["order"])
                    {
                        $sort_order = $_REQUEST["order"];
                        $_SESSION["order"] = $_REQUEST["order"];
                    }
                    elseif($_SESSION["order"])
                    {
                        $sort_order = $_SESSION["order"];
                    }
                    else
                    {
                        $sort_order = ToLower($arParams["ELEMENT_SORT_ORDER"]);
                    }
                }
                ?>
                <script>
                    $(document).ready(function(){
                        $(".js_link__rep").each(
                            function()
                            {
                                $(this).replaceWith('<a class="'+$(this).data("class")+'" href="'+$(this).data("href")+'" rel="nofollow">'+$(this).html()+'</a>');
                            }
                        );
                    });
                </script>
                <?foreach($arAvailableSort as $key => $val):?>
                    <?$newSort = $sort_order == 'desc' ? 'asc' : 'desc';?>
                    <span data-class="sort_btn <?=($sort == $key ? 'current' : '')?> <?=$sort_order?> <?=$key?>" data-href="<?=$APPLICATION->GetCurPageParam('sort='.$key.'&order='.$newSort, 	array('sort', 'order'))?>" class="js_link__rep">
					    <i class="icon"></i><span><?=GetMessage('SECT_SORT_'.$key)?></span><i class="arr"></i>
					</span>
                <?endforeach;?>
                <?
                if($sort == "PRICE")
                {
                    $sort = $arAvailableSort["PRICE"][0];
                }
                if($sort == "QUANTITY")
                {
                    $sort = "CATALOG_QUANTITY";
                }
                ?>
            </div>
            <div class="sort_display">
                <?foreach($arDisplays as $displayType):?>
                    <span rel="nofollow" data-href="<?=$APPLICATION->GetCurPageParam('display='.$displayType, 	array('display'))?>" data-class="sort_btn <?=$displayType?> <?=($display == $displayType ? 'current' : '')?>" class="js_link__rep">
					    <i title="<?=GetMessage("SECT_DISPLAY_".strtoupper($displayType))?>"></i>
					</span>
                <?endforeach;?>
            </div>
            <!--/noindex-->
        </div>
        <?
        $show = $arParams["PAGE_ELEMENT_COUNT"];
        if (array_key_exists("show", $_REQUEST)) {
            if (intVal($_REQUEST["show"])
                && in_array(
                    intVal($_REQUEST["show"]), array(20, 40, 60, 80, 100)
                )
            ) {
                $show = intVal($_REQUEST["show"]);
                $_SESSION["show"] = $show;
            } elseif ($_SESSION["show"]) {
                $show = intVal($_SESSION["show"]);
            }
        }
        $APPLICATION->IncludeComponent(
            'your:catalog.section.subsections',
            'subsections',
            Array(
                'SEF_URL_TEMPLATES' => $arParams['SEF_URL_TEMPLATES'],
                'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'],
                'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
                'ELEMENT_SORT_FIELD' => $sort,
                'ELEMENT_SORT_ORDER' => $sort_order,
                'FILTER_NAME' => $arParams['FILTER_NAME'],
                'INCLUDE_SUBSECTIONS' => $arParams['INCLUDE_SUBSECTIONS'],
                'PAGE_ELEMENT_COUNT' => $show,
                'LINE_ELEMENT_COUNT' => $arParams['LINE_ELEMENT_COUNT'],
                'PROPERTY_CODE' => $arParams['LIST_PROPERTY_CODE'],
                'OFFERS_FIELD_CODE' => $arParams['LIST_OFFERS_FIELD_CODE'],
                'OFFERS_PROPERTY_CODE' => $arParams['LIST_OFFERS_PROPERTY_CODE'],
                'OFFERS_SORT_FIELD' => $arParams['OFFERS_SORT_FIELD'],
                'OFFERS_SORT_ORDER' => $arParams['OFFERS_SORT_ORDER'],
                'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
                'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['element'],
                'BASKET_URL' => $arParams['BASKET_URL'],
                'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
                'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
                'PRODUCT_QUANTITY_VARIABLE' => 'quantity',
                'PRODUCT_PROPS_VARIABLE' => 'prop',
                'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],
                'AJAX_MODE' => $arParams['AJAX_MODE'],
                'AJAX_OPTION_JUMP' => $arParams['AJAX_OPTION_JUMP'],
                'AJAX_OPTION_STYLE' => $arParams['AJAX_OPTION_STYLE'],
                'AJAX_OPTION_HISTORY' => $arParams['AJAX_OPTION_HISTORY'],
                'CACHE_TYPE' =>$arParams['CACHE_TYPE'],
                'CACHE_TIME' => $arParams['CACHE_TIME'],
                'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                'META_KEYWORDS' => $arParams['LIST_META_KEYWORDS'],
                'META_DESCRIPTION' => $arParams['LIST_META_DESCRIPTION'],
                'BROWSER_TITLE' => $arParams['LIST_BROWSER_TITLE'],
                'ADD_SECTIONS_CHAIN' => $arParams['ADD_SECTIONS_CHAIN'],
                'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
                'DISPLAY_COMPARE' => $arParams['USE_COMPARE'],
                'SET_TITLE' => $arParams['SET_TITLE'],
                'SET_STATUS_404' => $arParams['SET_STATUS_404'],
                'CACHE_FILTER' => $arParams['CACHE_FILTER'],
                'PRICE_CODE' => $arParams['PRICE_CODE'],
                'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
                'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
                'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
                'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                'OFFERS_CART_PROPERTIES' => array(),
                'DISPLAY_TOP_PAGER' => $arParams['DISPLAY_TOP_PAGER'],
                'DISPLAY_BOTTOM_PAGER' => $arParams['DISPLAY_BOTTOM_PAGER'],
                'PAGER_TITLE' => $arParams['PAGER_TITLE'],
                'PAGER_SHOW_ALWAYS' => $arParams['PAGER_SHOW_ALWAYS'],
                'PAGER_TEMPLATE' => $arParams['PAGER_TEMPLATE'],
                'PAGER_DESC_NUMBERING' => $arParams['PAGER_DESC_NUMBERING'],
                'PAGER_DESC_NUMBERING_CACHE_TIME' => $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'],
                'PAGER_SHOW_ALL' => $arParams['PAGER_SHOW_ALL'],
                'AJAX_OPTION_ADDITIONAL' => '',
                'ADD_CHAIN_ITEM' => 'N',
                'SHOW_QUANTITY' => $arParams['SHOW_QUANTITY'],
                'SHOW_QUANTITY_COUNT' => $arParams['SHOW_QUANTITY_COUNT'],
                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                'USE_STORE' => $arParams['USE_STORE'],
                'MAX_AMOUNT' => $arParams['MAX_AMOUNT'],
                'MIN_AMOUNT' => $arParams['MIN_AMOUNT'],
                'USE_MIN_AMOUNT' => $arParams['USE_MIN_AMOUNT'],
                'USE_ONLY_MAX_AMOUNT' => $arParams['USE_ONLY_MAX_AMOUNT'],
                'DISPLAY_WISH_BUTTONS' => $arParams['DISPLAY_WISH_BUTTONS'],
                'DEFAULT_COUNT' => $arParams['DEFAULT_COUNT'],
                'LIST_DISPLAY_POPUP_IMAGE' => $arParams['LIST_DISPLAY_POPUP_IMAGE'],
                'SHOW_MEASURE' => $arParams['SHOW_MEASURE'],
                'SHOW_HINTS' => $arParams['SHOW_HINTS'],
                'SHOW_SECTIONS_LIST_PREVIEW' => $arParams['SHOW_SECTIONS_LIST_PREVIEW'],
                'SECTIONS_LIST_PREVIEW_PROPERTY' => $arParams['SECTIONS_LIST_PREVIEW_PROPERTY'],
                'SHOW_SECTION_LIST_PICTURES' => $arParams['SHOW_SECTION_LIST_PICTURES'],
                'ELEMENTS_IDS' => $arElemIDs,
            ),
            $component
        );
        ?>
    </div>
<?endif;?>
<script type="text/javascript">
    $(".sort_filter a").on("click", function(){
        if($(this).is(".current")){
            $(this).toggleClass("desc").toggleClass("asc");
        }
        else{
            $(this).toggleClass("desc").toggleClass("asc");
            $(this).addClass("current").siblings().removeClass("current");
        }
    });

    $(".sort_display a:not(.current)").on("click", function() {
        $(this).addClass("current").siblings().removeClass("current");
    });

    $(".number_list a:not(.current)").on("click", function() {
        $(this).addClass("current").siblings().removeClass("current");
    });
</script>

<?

if(count($SEO_DATA['seo_meta']) > 0)
{
    foreach($SEO_DATA['seo_meta'] as $code=>$value)
    {
        if($code == 'H1')
        {
            $tmp = trim($value);
            if(strlen($tmp))
            {
                $APPLICATION->SetTitle($tmp);
            }
        }
        else
        {
            $tmp = trim($value);
            if(strlen($tmp))
            {
                $APPLICATION->SetPageProperty($code,$tmp );

            }
        }
    }
}
?>
