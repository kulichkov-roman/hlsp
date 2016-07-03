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
     * @todo не универсальное решение
     */
    public static function getChainExtended($arResult)
    {
        global $USER, $APPLICATION;

        $environment = \Your\Environment\EnvironmentManager::getInstance();

        $curDir = $APPLICATION->GetCurDir();

        $arRatio = array(
            4 => 1,
            5 => 1,
            6 => 2,
            7 => 3,
        );

        $arUrl = array_unique(explode('/', $curDir));
        $arUrl = array_diff($arUrl, array(''));

        $arSubUrl = $arBaseUrl = $arUrl;

        /*
         * Базовый ЧПУ
         */
        array_splice($arBaseUrl, sizeof($arBaseUrl)-$arRatio[sizeof($arBaseUrl)]);

        /**
         * Срез из базового ЧПУ
         */
        $arSubUrl = array_slice($arSubUrl, sizeof($arSubUrl)-$arRatio[sizeof($arSubUrl)]);

        array_unshift($arBaseUrl, '');
        array_push($arBaseUrl, '');

        $strUrl = implode('/', $arBaseUrl);

        $arChainUrl = array();
        foreach ($arSubUrl as $dir)
        {
            $arDir = array(
                $dir,
                ''
            );

            $strSubDir = implode('/', $arDir);

            $arChainUrl[] = $strUrl.$strSubDir;

            $strUrl = $strUrl.$strSubDir;
        }

        if(
            sizeof($arSubUrl) == sizeof($arChainUrl) &&
            sizeof($arBaseUrl)>=3
        )
        {
            $arSef = array(
                'BRAND',
                'SECTION_DL1',
                'SECTION_DL2'
            );

            $arPageSef = array();
            foreach ($arSubUrl as $key => $value)
            {
                $arPageSef[$arSef[$key]] = array(
                    'ID'   => '',
                    'NAME' => '',
                    'CODE' => $arSubUrl[$key],
                    'LINK'  => $arChainUrl[$key],
                );
            }

            if(is_array($arPageSef['BRAND']))
            {
                /**
                 * Получить NAME для Бренда
                 */
                $arBrandSort = array();
                $arBrandSelect = array(
                    'ID',
                    'NAME',
                    'CODE'
                );

                $arBrandFilter = array(
                    'IBLOCK_ID' => $environment->get('brandIBlock'),
                    'CODE'      => $arPageSef['BRAND']['CODE']
                );

                $rsBrands = \CIBlockElement::GetList(
                    $arBrandSort,
                    $arBrandFilter,
                    false,
                    false,
                    $arBrandSelect
                );

                if($arBrand = $rsBrands->Fetch()){}

                $arPageSef['BRAND']['ID']   = $arBrand['ID'];
                $arPageSef['BRAND']['NAME'] = $arBrand['NAME'];

                $arTmpResult['BRAND'] = array(
                    'TITLE' => $arPageSef['BRAND']['NAME']
                );

                if(is_array($arPageSef['SECTION_DL1']))
                {
                    /**
                     * Получить ID раздела по code из url
                     */
                    $baseSecCode = $arBaseUrl[3];
                    if($baseSecCode <> '')
                    {
                        $arCatSort = array();
                        $arCatFilter = array(
                            'IBLOCK_ID' => $environment->get('catalogIBlock'),
                            'CODE' => $baseSecCode
                        );
                        $arCatSelect = array(
                            'ID',
                            'NAME',
                            'CODE'
                        );

                        $rsCatSection = \CIBlockSection::GetList(
                            $arCatSort,
                            $arCatFilter,
                            false,
                            $arCatSelect,
                            false
                        );

                        if ($arCatSection = $rsCatSection->Fetch())
                        {
                            /**
                             * Получить NAME для Dl1
                             */
                            $arSortSubSecDl1 = array();
                            $arSelectSubSecDl1 = array(
                                'ID',
                                'NAME',
                                'CODE'
                            );
                            $arFilterSubSecDl1 = array(
                                'IBLOCK_ID'  => $environment->get('seoSubsectionsIBlock'),
                                'CODE'       => $arPageSef['SECTION_DL1']['CODE'],
                                'PROPERTY_LINK_SECTION_CAT' => $arCatSection['ID'],
                                'PROPERTY_LEVEL_VALUE' => 1,
                            );

                            $rsSubSecDl1 = \CIBlockElement::GetList(
                                $arSortSubSecDl1,
                                $arFilterSubSecDl1,
                                false,
                                false,
                                $arSelectSubSecDl1
                            );

                            if($arSubSecDl1 = $rsSubSecDl1->Fetch()){}

                            $arPageSef['SECTION_DL1']['ID']   = $arSubSecDl1['ID'];
                            $arPageSef['SECTION_DL1']['NAME'] = $arSubSecDl1['NAME'];

                            /**
                             * Если есть SECTION_DL1 добавить ссылку в BRAND
                             */
                            $arTmpResult['BRAND'] = array(
                                'TITLE' => $arPageSef['BRAND']['NAME'],
                                'LINK'  => $arPageSef['BRAND']['LINK']
                            );

                            $arTmpResult['SECTION_DL1'] = array(
                                'TITLE' => $arPageSef['SECTION_DL1']['NAME']
                            );

                            if(is_array($arPageSef['SECTION_DL2']))
                            {
                                /**
                                 * Получить NAME для Dl2
                                 */
                                $arSortSubSecDl2 = array();
                                $arSelectSubSecDl2 = array(
                                    'ID',
                                    'NAME',
                                    'CODE'
                                );
                                $arFilterSubSecDl2 = array(
                                    'IBLOCK_ID'  => $environment->get('seoSubsectionsIBlock'),
                                    'CODE'       => $arPageSef['SECTION_DL2']['CODE'],
                                    'PROPERTY_LINK_SECTION_CAT' => $arCatSection['ID'],
                                    'PROPERTY_LEVEL_VALUE' => 2,
                                );

                                $rsSubSecDl2 = \CIBlockElement::GetList(
                                    $arSortSubSecDl2,
                                    $arFilterSubSecDl2,
                                    false,
                                    false,
                                    $arSelectSubSecDl2
                                );

                                if($arSubSecDl2 = $rsSubSecDl2->Fetch()){}

                                $arPageSef['SECTION_DL2']['ID']   = $arSubSecDl2['ID'];
                                $arPageSef['SECTION_DL2']['NAME'] = $arSubSecDl2['NAME'];

                                /**
                                 * Еслить есть SECTION_DL2 добавить ссылку в SECTION_DL1
                                 */
                                $arTmpResult['SECTION_DL1'] = array(
                                    'TITLE' => $arPageSef['SECTION_DL1']['NAME'],
                                    'LINK'  => $arPageSef['SECTION_DL1']['LINK']
                                );

                                $arTmpResult['SECTION_DL2'] = array(
                                    'TITLE' => $arPageSef['SECTION_DL2']['NAME']
                                );
                            }
                        }
                    }
                }

                foreach ($arTmpResult as $arItem)
                {
                    $arResult[] = $arItem;
                }
                return $arResult;
            }
            else
            {
                return $arResult;
            }
        }
        else
        {
            return $arResult;
        }
    }
}
?>
