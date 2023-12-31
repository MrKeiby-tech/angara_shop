<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$APPLICATION->IncludeComponent(
	"aspro:com.banners.max", 
	"top_big_banners", 
	array(
		"IBLOCK_TYPE" => "aspro_max_adv",
		"IBLOCK_ID" => "27",
		"TYPE_BANNERS_IBLOCK_ID" => "1",
		"SET_BANNER_TYPE_FROM_THEME" => "N",
		"NEWS_COUNT" => "10",
		"NEWS_COUNT2" => "0",
		"NEWS_COUNT3" => "3",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"PROPERTY_CODE" => array(
			0 => "TEXT_POSITION",
			1 => "TARGETS",
			2 => "TEXTCOLOR",
			3 => "URL_STRING",
			4 => "BUTTON1TEXT",
			5 => "BUTTON1LINK",
			6 => "BUTTON2TEXT",
			7 => "BUTTON2LINK",
			8 => "",
		),
		"CHECK_DATES" => "Y",
		"CACHE_GROUPS" => "N",
		"SECTION_ID" => "126",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"BANNER_TYPE_THEME" => "TOP",
		"BANNER_TYPE_THEME_CHILD" => "TOP_SMALL_BANNER",
		"COMPONENT_TEMPLATE" => "top_big_banners",
		"FILTER_NAME" => "arRegionLink",
		"SHOW_MEASURE" => "Y",
		"PRICE_CODE" => array(
		),
		"STORES" => array(
			0 => "",
			1 => "",
		),
		"CONVERT_CURRENCY" => "N"
	),
	false
);?>