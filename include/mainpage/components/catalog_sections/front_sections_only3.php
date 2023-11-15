<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}?>
<?$APPLICATION->IncludeComponent(
	"aspro:wrapper.block.max", 
	"front_sections_only2", 
	array(
		"SHAPE_PICTURES" => "FROM_THEME",
		"SLIDER_ELEMENTS_COUNT" => "FROM_THEME",
		"LAST_LINK_IN_SLIDER" => "FROM_THEME",
		"IBLOCK_TYPE" => "aspro_max_catalog",
		"IBLOCK_ID" => "26",
		"FILTER_NAME" => "",
		"COMPONENT_TEMPLATE" => "front_sections_only2",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"TITLE_BLOCK" => "",
		"TITLE_BLOCK_ALL" => "",
		"ALL_URL" => "catalog/",
		"VIEW_MODE" => "",
		"VIEW_TYPE" => "type3",
		"SHOW_ICONS" => "N",
		"NO_MARGIN" => "N",
		"FILLED" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"TOP_DEPTH" => "2",
		"SHOW_SUBSECTIONS" => "N",
		"SCROLL_SUBSECTIONS" => "N",
		"INCLUDE_FILE" => "",
		"FILTER_PROP_CODE" => "",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "N",
		"HIDE_NOT_AVAILABLE" => "N",
		"ELEMENT_COUNT" => "30",
		"DISPLAY_COMPARE" => "Y",
		"SHOW_MEASURE" => "N",
		"DISPLAY_WISH_BUTTONS" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_DISCOUNT_PERCENT_NUMBER" => "N",
		"SHOW_DISCOUNT_TIME" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_ONE_CLICK" => "Y",
		"PRICE_CODE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPERTIES" => "",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"SHOW_RATING" => "Y",
		"STIKERS_PROP" => "HIT",
		"SALE_STIKER" => "SALE_TEXT",
		"SHOW_GALLERY" => "Y",
		"MAX_GALLERY_ITEMS" => "5",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"FIELDS" => array(
			0 => "",
			1 => "",
		),
		"CONVERT_CURRENCY" => "N",
		"STORES" => array(
			0 => "",
			1 => "",
		)
	),
	false
);?>