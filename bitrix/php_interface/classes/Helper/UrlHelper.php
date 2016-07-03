<?php

namespace HLSP\Helper;

/**
 * Хелпер для работы с URL
 *
 * Class UrlHelper
 *
 * @package Your\Helpers
 *
 * @author Kulichkov Roman <roman@kulichkov.pro>
 */
class UrlHelper
{
    /**
     * ChainHelper constructor.
     */
    public function __construct()
    {}

    /**
     * Получить список брендов по данных из URL
     * @todo не универсальное решение
     */
    public static function getBrandsInDir()
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
        if(sizeof($arUrl) > 3)
        {
            if(preg_match("#/rasprodazha/#", $curDir))
            {
                $index = 2;
            }
            else
            {
                $index = 3;
            }
        }
        else
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

            $rsBrands = \CIBlockElement::GetList(
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
}
?>
