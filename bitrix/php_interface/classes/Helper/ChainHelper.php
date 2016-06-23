<?php

namespace HLSP\Helper;

/**
 * Хелпер для работы с хлебными крошками
 *
 * Class ChainHelper
 *
 * @package Your\Helpers
 *
 * @author Kulichkov Roman <roman@kulichkov.pro>
 */
class ChainHelper
{
    /**
     * ChainHelper constructor.
     */
    public function __construct()
    {}

    /**
     * Добавить в цепочку дополнительные разделы
     */
    public static function getChainExtended($arResult)
    {
        global $USER, $APPLICATION;

        $environment = \Your\Environment\EnvironmentManager::getInstance();

        $curDir = $APPLICATION->GetCurDir();

        $arRatio = array(
            4 => 0,
            6 => 1,
            7 => 2,
            8 => 3,
        );

        $arUrl = array_unique(explode('/', $curDir));

        $brand = array_pop($arUrl);

        array_splice($arUrl, sizeof($arUrl)-$arRatio[sizeof($arUrl)]);

        $arSecSort = array();
        $arSecFilter = array(
            "IBLOCK_ID" => $environment->get('catalogIBlock'),
            "CODE" => $arUrl['3']
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

        $rsBrands = \CIBlockElement::GetList(
            $arBrandSort,
            $arBrandFilter,
            false,
            false,
            $arBrandSelect
        );

        $arBrands = array();
        while($arBrandItem = $rsBrands->Fetch())
        {
            $arBrands[$arBrandItem['CODE']] = $arBrandItem;
        }

        $arResult[] = array(
            'TITLE' => $arBrands[$brand]['NAME']
        );

        return $arResult;
    }
}
?>
