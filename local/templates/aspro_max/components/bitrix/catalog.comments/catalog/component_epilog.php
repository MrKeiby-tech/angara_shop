<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @var array $arResult */
/** @var array $arParams */
/** @var CBitrixComponent $this */

use Aspro\Max\Functions\Extensions;
$ajaxMode = isset($templateData['BLOG']['BLOG_FROM_AJAX']) && $templateData['BLOG']['BLOG_FROM_AJAX'];
if (!$ajaxMode)
{
	CJSCore::Init(array('window', 'ajax'));
}
if($arParams['NO_USE_IMAGE'] == 'N') Extensions::init('drop');



global $BLOG_DATA;
$BLOG_DATA = $arResult;

if (isset($templateData['BLOG_USE']) && $templateData['BLOG_USE'] == 'Y')
{
	if ($ajaxMode)
	{
		$arBlogCommentParams = array(
			'SEO_USER' => 'N',
			'ID' => $arResult['BLOG_DATA']['BLOG_POST_ID'],
			'BLOG_URL' => $arResult['BLOG_DATA']['BLOG_URL'],
			'PATH_TO_SMILE' => $arParams['PATH_TO_SMILE'],
			'COMMENTS_COUNT' => $arParams['COMMENTS_COUNT'],
			"DATE_TIME_FORMAT" => $DB->DateFormatToPhp(FORMAT_DATETIME),
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"AJAX_POST" => $arParams["AJAX_POST"],
			"AJAX_MODE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"SIMPLE_COMMENT" => "Y",
			"SHOW_SPAM" => $arParams["SHOW_SPAM"],
			"NOT_USE_COMMENT_TITLE" => "Y",
			"SHOW_RATING" => $arParams["SHOW_RATING"],
			"RATING_TYPE" => $arParams["RATING_TYPE"],
			"PATH_TO_POST" => $arResult["URL_TO_COMMENT"],
			"REVIEW_COMMENT_REQUIRED" => $arParams["REVIEW_COMMENT_REQUIRED"],
			"REVIEW_FILTER_BUTTONS" => $arParams["REVIEW_FILTER_BUTTONS"],
			"REAL_CUSTOMER_TEXT" => $arParams["REAL_CUSTOMER_TEXT"],
			"IBLOCK_ID" => (array_key_exists('AJAX_PARAMS', $templateData['BLOG']) && array_key_exists('IBLOCK_ID', $templateData['BLOG']['AJAX_PARAMS']) ? $templateData['BLOG']['AJAX_PARAMS']['IBLOCK_ID'] : ''),
			"ELEMENT_ID" => (array_key_exists('AJAX_PARAMS', $templateData['BLOG']) && array_key_exists('ELEMENT_ID', $templateData['BLOG']['AJAX_PARAMS']) && $templateData['BLOG']['AJAX_PARAMS']['ELEMENT_ID'] ? $templateData['BLOG']['AJAX_PARAMS']['ELEMENT_ID'] : $_REQUEST['ELEMENT_ID']),
			"XML_ID" => (array_key_exists('AJAX_PARAMS', $templateData['BLOG']) && array_key_exists('XML_ID', $templateData['BLOG']['AJAX_PARAMS']) && $templateData['BLOG']['AJAX_PARAMS']['XML_ID'] ? $templateData['BLOG']['AJAX_PARAMS']['XML_ID'] : $_REQUEST['XML_ID']),
			"NO_URL_IN_COMMENTS" => "",
			"USE_FILTER" => $arParams["USE_FILTER"],
			"MAX_IMAGE_COUNT" => $arParams['MAX_IMAGE_COUNT'],
			"NO_USE_IMAGE" => $arParams['NO_USE_IMAGE'],
		);

		$APPLICATION->IncludeComponent(
			'bitrix:blog.post.comment',
			'adapt',
			$arBlogCommentParams,
			$this,
			array('HIDE_ICONS' => 'Y')
		);
		return;
	}
	else
	{
		$_SESSION['IBLOCK_CATALOG_COMMENTS_PARAMS_'.$templateData['BLOG']['AJAX_PARAMS']["IBLOCK_ID"].'_'.$templateData['BLOG']['AJAX_PARAMS']["ELEMENT_ID"]] = $templateData['BLOG']['AJAX_PARAMS'];
		$APPLICATION->SetAdditionalCSS('/bitrix/components/bitrix/blog/templates/.default/style.css');
		$APPLICATION->SetAdditionalCSS('/bitrix/components/bitrix/blog/templates/.default/themes/green/style.css');
		if ($templateData['BLOG']['AJAX_PARAMS']['SHOW_RATING'] == 'Y')
		{
			ob_start();
			$APPLICATION->IncludeComponent(
	"bitrix:rating.vote", 
	"standart_text", 
	array(
		"COMPONENT_TEMPLATE" => "standart_text"
	),
	false
);
			ob_end_clean();
		}
	}
}

if (!$ajaxMode)
{
	if (isset($templateData['FB_USE']) && $templateData['FB_USE'] == "Y")
	{
		if (isset($arParams["FB_USER_ADMIN_ID"]) && strlen($arParams["FB_USER_ADMIN_ID"]) > 0)
		{
			$APPLICATION->AddHeadString('<meta property="fb:admins" content="'.$arParams["FB_USER_ADMIN_ID"].'"/>');
		}
		if (isset($arParams["FB_APP_ID"]) && $arParams["FB_APP_ID"] != '')
		{
			$APPLICATION->AddHeadString('<meta property="fb:app_id" content="'.$arParams["FB_APP_ID"].'"/>');
		}
	}

	if (isset($templateData['TEMPLATE_THEME']))
	{
		$APPLICATION->SetAdditionalCSS($templateData['TEMPLATE_THEME']);
	}
}