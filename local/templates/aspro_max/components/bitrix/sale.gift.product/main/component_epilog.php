<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(\Bitrix\Main\Loader::includeModule("aspro.max"))
{
	global $arRegion;
	$arRegion = CMaxRegionality::getCurrentRegion();
}
?>