<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "купить косметику");
$APPLICATION->SetTitle("Корейская косметика для очищения, ухода и макияжа");
?>
<?
$APPLICATION->IncludeComponent(
	"your:catalog",
	"catalog",
	Array(
	    "IBLOCK_TYPE" => "aspro_kshop_catalog",	// Тип инфоблока
		"IBLOCK_ID" => "11",	// Инфоблок
		"HIDE_NOT_AVAILABLE" => "N",	// Не отображать товары, которых нет на складах
		"BASKET_URL" => "/basket/",	// URL, ведущий на страницу с корзиной покупателя
		"ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
		"PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
		"SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",	// Название переменной, в которой передается количество товара
		"PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
		"SEF_MODE" => "Y",	// Включить поддержку ЧПУ
		"SEF_FOLDER" => "/catalog/",	// Каталог ЧПУ (относительно корня сайта)
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "250000",	// Время кеширования (сек.)
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
		"SET_STATUS_404" => "Y",	// Устанавливать статус 404
		"USE_ELEMENT_COUNTER" => "Y",	// Использовать счетчик просмотров
		"USE_FILTER" => "Y",	// Показывать фильтр
		"FILTER_NAME" => "KSHOP_SMART_FILTER",	// Фильтр
		"FILTER_FIELD_CODE" => array(	// Поля
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(	// Свойства
			0 => "CML2_MANUFACTURER",
			1 => "PROP_2033",
			2 => "PROP_2083",
			3 => "SPF",
			4 => "",
		),
		"FILTER_PRICE_CODE" => array(	// Тип цены
			0 => "BASE",
		),
		"FILTER_OFFERS_FIELD_CODE" => array(	// Поля предложений
			0 => "ID",
			1 => "",
		),
		"FILTER_OFFERS_PROPERTY_CODE" => array(	// Свойства предложений
			0 => "",
			1 => "NAME_SKU",
			2 => "COLOR",
			3 => "CML2_LINK",
			4 => "",
		),
		"USE_REVIEW" => "Y",	// Разрешить отзывы
		"MESSAGES_PER_PAGE" => "10",	// Количество сообщений на одной странице
		"USE_CAPTCHA" => "Y",	// Использовать CAPTCHA
		"REVIEW_AJAX_POST" => "Y",	// Использовать AJAX в диалогах
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",	// Путь относительно корня сайта к папке со смайлами
		"FORUM_ID" => "1",	// ID форума для отзывов
		"URL_TEMPLATES_READ" => "",	// Страница чтения темы (пусто - получить из настроек форума)
		"SHOW_LINK_TO_FORUM" => "Y",	// Показать ссылку на форум
		"POST_FIRST_MESSAGE" => "N",
		"USE_COMPARE" => "Y",	// Разрешить сравнение товаров
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",	// Уникальное имя для списка сравнения
		"COMPARE_FIELD_CODE" => array(	// Поля
			0 => "PREVIEW_PICTURE",
			1 => "",
		),
		"COMPARE_PROPERTY_CODE" => array(	// Свойства
			0 => "PROP_159",
			1 => "PROP_2056",
			2 => "PROP_2033",
			3 => "PROP_2083",
			4 => "CML2_ARTICLE",
			5 => "PROP_2055",
			6 => "PROP_2059",
			7 => "PROP_2065",
			8 => "",
		),
		"COMPARE_OFFERS_FIELD_CODE" => array(	// Поля предложений
			0 => "ID",
			1 => "",
		),
		"COMPARE_OFFERS_PROPERTY_CODE" => array(	// Свойства предложений
			0 => "PROP_159",
			1 => "PROP_2056",
			2 => "PROP_2033",
			3 => "PROP_2083",
			4 => "COLOR",
			5 => "CML2_LINK",
			6 => "",
		),
		"COMPARE_ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",	// Выводить список элементов инфоблока
		"PRICE_CODE" => array(	// Тип цены
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
		"SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
		"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
		"PRICE_VAT_SHOW_VALUE" => "N",	// Отображать значение НДС
		"PRODUCT_PROPERTIES" => "",	// Характеристики товара, добавляемые в корзину
		"USE_PRODUCT_QUANTITY" => "Y",	// Разрешить указание количества товара
		"CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
		"CURRENCY_ID" => "RUB",
		"OFFERS_CART_PROPERTIES" => array(	// Свойства предложений, добавляемые в корзину
			0 => "TYPE_SKU",
			1 => "PROP_159",
			2 => "PROP_2056",
		),
		"SHOW_TOP_ELEMENTS" => "Y",	// Выводить топ элементов
		"SECTION_COUNT_ELEMENTS" => "Y",	// Показывать количество элементов в разделе
		"SECTION_TOP_DEPTH" => "2",	// Максимальная отображаемая глубина разделов
		"SECTIONS_LIST_PREVIEW_PROPERTY" => "UF_SECTION_DESCR",	// Брать описание из
		"SHOW_SECTION_LIST_PICTURES" => "Y",	// Показывать картинки разделов
		"PAGE_ELEMENT_COUNT" => "20",	// Количество элементов на странице
		"LINE_ELEMENT_COUNT" => "3",	// Количество элементов, выводимых в одной строке таблицы
		"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем товары в разделе
		"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки товаров в разделе
		"ELEMENT_SORT_FIELD2" => "CATALOG_AVAILABLE",	// Поле для второй сортировки товаров в разделе
		"ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки товаров в разделе
		"LIST_PROPERTY_CODE" => array(	// Свойства
			0 => "PROP_159",
			1 => "PROP_2056",
			2 => "PROP_2033",
			3 => "PROP_2083",
			4 => "CML2_ARTICLE",
			5 => "PROP_2055",
			6 => "PROP_2059",
			7 => "PROP_2065",
			8 => "UF_SEC_SEO_TEXT",
			9 => "",
		),
		"INCLUDE_SUBSECTIONS" => "A",	// Показывать элементы подразделов раздела
		"LIST_META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства раздела
		"LIST_META_DESCRIPTION" => "-",	// Установить описание страницы из свойства раздела
		"LIST_BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства раздела
		"LIST_OFFERS_FIELD_CODE" => array(	// Поля предложений
			0 => "ID",
			1 => "CML2_LINK",
			2 => "",
		),
		"LIST_OFFERS_PROPERTY_CODE" => array(	// Свойства предложений
			0 => "PROP_159",
			1 => "PROP_2083",
			2 => "COLOR",
			3 => "CML2_LINK",
			4 => "",
		),
		"LIST_OFFERS_LIMIT" => "10",	// Максимальное количество предложений для показа (0 - все)
		"SORT_BUTTONS" => array(	// Кнопки сортировки
			0 => "POPULARITY",
			1 => "NAME",
			2 => "PRICE",
			3 => "QUANTITY",
		),
		"SORT_PRICES" => "MINIMUM_PRICE",	// Сортировка по цене
		"DEFAULT_LIST_TEMPLATE" => "block",	// Показывать товары в разделе по умолчанию
		"SECTION_DISPLAY_PROPERTY" => "UF_SECTION_TEMPLATE",	// Свойство раздела с шаблоном по умолчанию
		"LIST_DISPLAY_POPUP_IMAGE" => "Y",	// Показывать всплывающее фото товара в шаблоне "Список"
		"SECTION_PREVIEW_PROPERTY" => "UF_SECTION_DESCR",	// Брать описание из
		"SHOW_SECTION_PICTURES" => "Y",	// Показывать картинки разделов
		"SHOW_SECTION_SIBLINGS" => "Y",	// Показывать список разделов
		"DETAIL_PROPERTY_CODE" => array(	// Свойства
			0 => "CML2_MANUFACTURER",
			1 => "BRAND",
			2 => "PROP_159",
			3 => "PROP_2056",
			4 => "PROP_2033",
			5 => "PROP_2055",
			6 => "VIDEO_YOUTUBE",
			7 => "PROP_2065",
			8 => "SOSTAV",
			9 => "props",
			10 => "RECOMMEND",
			11 => "NEW",
			12 => "STOCK",
			13 => "VIDEO",
			14 => "",
		),
		"DETAIL_META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
		"DETAIL_META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
		"DETAIL_BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
		"DETAIL_OFFERS_FIELD_CODE" => array(	// Поля предложений
			0 => "NAME",
			1 => "DETAIL_PICTURE",
			2 => "",
		),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(	// Свойства предложений
			0 => "TYPE_SKU",
			1 => "CML2_LINK",
			2 => "",
		),
		"PROPERTIES_DISPLAY_LOCATION" => "DESCRIPTION",	// Показывать свойства товара
		"SHOW_BRAND_PICTURE" => "Y",	// Показывать логотип бренда
		"SHOW_ASK_BLOCK" => "Y",	// Показывать блок 'Задать вопрос'
		"ASK_FORM_ID" => "1",	// ID формы 'Задать вопрос'
		"SHOW_ADDITIONAL_TAB" => "Y",	// Показывать вкладку 'Дополнительно'
		"PROPERTIES_DISPLAY_TYPE" => "TABLE",	// Показывать свойства
		"SHOW_KIT_PARTS" => "N",	// Показывать состав комплектов
		"SHOW_KIT_PARTS_PRICES" => "N",	// Показывать цены частей комплекта
		"LINK_IBLOCK_TYPE" => "aspro_kshop_catalog",	// Тип инфоблока, элементы которого связаны с текущим элементом
		"LINK_IBLOCK_ID" => "11",	// ID инфоблока, элементы которого связаны с текущим элементом
		"LINK_PROPERTY_SID" => "",	// Свойство, в котором хранится связь
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",	// URL на страницу, где будет показан список связанных элементов
		"USE_ALSO_BUY" => "Y",	// Показывать блок "С этим товаром покупают"
		"ALSO_BUY_ELEMENT_COUNT" => "5",	// Количество элементов для отображения
		"ALSO_BUY_MIN_BUYES" => "2",	// Минимальное количество покупок товара
		"USE_STORE" => "N",	// Показывать блок "Количество товара на складе"
		"USE_STORE_PHONE" => "Y",
		"USE_STORE_SCHEDULE" => "Y",
		"USE_MIN_AMOUNT" => "Y",
		"MIN_AMOUNT" => "3",
		"IBLOCK_BANNERS_TYPE" => "aspro_kshop_adv",	// Тип инфоблока баннеров
		"IBLOCK_BANNERS_ID" => "3",	// ID инфоблока баннеров
		"IBLOCK_BANNERS_TYPE_ID" => "2",	// ID инфоблока с типами баннеров
		"IBLOCK_SMALL_BANNERS_TYPE_ID" => "4",	// Тип банеров для показа в каталоге
		"STORE_PATH" => "/contacts/stores/#store_id#/",
		"MAIN_TITLE" => "Наличие на складах",
		"MAX_AMOUNT" => "6",
		"USE_ONLY_MAX_AMOUNT" => "Y",
		"OFFERS_SORT_FIELD" => "sort",	// По какому полю сортируем предложения товара
		"OFFERS_SORT_ORDER" => "asc",	// Порядок сортировки предложений товара
		"OFFERS_SORT_FIELD2" => "CATALOG_AVAILABLE",	// Поле для второй сортировки предложений товара
		"OFFERS_SORT_ORDER2" => "desc",	// Порядок второй сортировки предложений товара
		"PAGER_TEMPLATE" => "",	// Шаблон постраничной навигации
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
		"PAGER_TITLE" => "Товары",	// Название категорий
		"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
		"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
		"PAGER_SHOW_ALL" => "Y",	// Показывать ссылку "Все"
		"IBLOCK_STOCK_ID" => "8",	// Номер инфоблока акций
		"SHOW_QUANTITY" => "Y",
		"SHOW_MEASURE" => "Y",	// Показывать единицы измерения
		"SHOW_QUANTITY_COUNT" => "Y",
		"USE_RATING" => "Y",	// Использовать рейтинг
		"DISPLAY_WISH_BUTTONS" => "Y",	// Показывать кнопку 'отложить'
		"DEFAULT_COUNT" => "1",	// Количество, добавляемое в корзину по умолчанию
		"SHOW_HINTS" => "Y",	// Показывать подсказки у свойств
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
		"ADD_SECTIONS_CHAIN" => "Y",	// Включать раздел в цепочку навигации
		"ADD_ELEMENT_CHAIN" => "N",	// Включать название элемента в цепочку навигации
		"ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
		"TOP_ELEMENT_COUNT" => "9",	// Количество выводимых элементов
		"TOP_LINE_ELEMENT_COUNT" => "3",	// Количество элементов, выводимых в одной строке таблицы
		"TOP_ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем товары в разделе
		"TOP_ELEMENT_SORT_ORDER" => "desc",	// Порядок сортировки товаров в разделе
		"TOP_ELEMENT_SORT_FIELD2" => "CATALOG_AVAILABLE",	// Поле для второй сортировки товаров в разделе
		"TOP_ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки товаров в разделе
		"TOP_PROPERTY_CODE" => array(	// Свойства
			0 => "",
			1 => "",
		),
		"TOP_OFFERS_FIELD_CODE" => array(	// Поля предложений
			0 => "",
			1 => "",
		),
		"TOP_OFFERS_PROPERTY_CODE" => array(	// Свойства предложений
			0 => "",
			1 => "",
		),
		"TOP_OFFERS_LIMIT" => "5",	// Максимальное количество предложений для показа (0 - все)
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",	// Использовать код группы из переменной, если не задан раздел элемента
		"STORES" => "",
		"USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SHOW_EMPTY_STORE" => "Y",
		"SHOW_GENERAL_STORE_INFORMATION" => "N",
		"COMPONENT_TEMPLATE" => "catalog_ito_15",
		"DETAIL_SET_CANONICAL_URL" => "N",	// Устанавливать канонический URL
		"SHOW_DEACTIVATED" => "N",	// Показывать деактивированные товары
		"USE_MAIN_ELEMENT_SECTION" => "N",	// Использовать основной раздел для показа элемента
		"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
		"SECTION_BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
		"DETAIL_BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
		"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
		"SHOW_404" => "Y",	// Показ специальной страницы
		"COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
		"COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",	// Не подключать js-библиотеки в компоненте
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",	// Включить сохранение информации о просмотре товара на детальной странице для старых шаблонов
		"MESSAGE_404" => "",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"element" => "product/#ELEMENT_CODE#/",
			"brand" => "#SECTION_CODE_PATH#/brand/#BRAND#/",
			"section_dl1" => "#SECTION_CODE_PATH#/brand/#BRAND#/#SECTION_DL1#/",
			"section_dl2" => "#SECTION_CODE_PATH#/brand/#BRAND#/#SECTION_DL1#/#SECTION_DL2#/",
			"compare" => "compare.php?action=#ACTION_CODE#",
			"smart_filter" => "#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/",
		),
		"VARIABLE_ALIASES" => array(
			"compare" => array(
				"ACTION_CODE" => "action",
			),
		)
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
