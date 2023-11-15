<?
use Bitrix\Main\Localization\Loc; 
Loc::loadLanguageFile(__FILE__);

if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php")){ 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");  
}

if (!function_exists("logto")){
	function logto($var, $url="/log.txt", $append = false, $admin = false)
	{
		$arg = func_get_args();
		if($arg[2] === true){
			$append = true;
		}else{
			$append = false;
		}
		if($arg[3] === true){
			$admin = true;
		}else{
			$admin = false;
		}
		unset($arg[0],$arg[1]);
		
		
		$admin_enable = array(
			"admin","ad","aa",
		);
		$append_enable = array(
			"append","apend","aa","ap",
		);
		
		foreach($arg as $key=>$val){
			$val = strtolower($val);
			if (in_array($val,$admin_enable)){
				$admin = true;
			}
			if (in_array($val,$append_enable)){
				$append = true;
			}
		}
		
		
		if ($url === true){
			$url="/log.txt";
		}elseif(is_numeric($url)){
			$url="/log".$url.".txt";
		}elseif(substr($url, 0, 1) !== "/"){
			$url="/".$url;
		}
		
		if (is_bool($var)){
			if ($var === true){
				$var = 'true';
			}elseif($var === false){
				$var = 'false';
			}
		}
		
		if(!is_array($var) && !is_object($var)){
			
			if($ex = $GLOBALS["APPLICATION"]->GetException()){
				$strError = $ex->GetString();
				$var .= "\r\n".$strError;
			}
			
			$var = $var."\r\n";
			
		}else{
			if(is_array($var)){
				if($ex = $GLOBALS["APPLICATION"]->GetException()){
					$strError = $ex->GetString();
					$var['EXEPTION'] = $strError;
				}		
			}
			$var = var_export($var,true);
		}	
		
		
		$io = CBXVirtualIo::GetInstance();
		$path = $io->ExtractPathFromPath($url);
		$abs_path = $io->RelativeToAbsolutePath($path);
		if(!$io->DirectoryExists($abs_path)){
			$bool = $io->CreateDirectory($abs_path);
		}
		
		$abs_url = $io->RelativeToAbsolutePath($url);
		
		
		if ($admin){
			global $USER;
			if(!is_object($USER))
				$USER = new CUser;
			
			if ($USER->IsAdmin()){
				if ($append){
					file_put_contents($abs_url,$var, FILE_APPEND);
				}else{
					file_put_contents($abs_url,$var);
				}
			}
			
		}else{
			if ($append){
				file_put_contents($abs_url, $var, FILE_APPEND);
			}else{
				file_put_contents($abs_url, $var);
			}	
		}
	}
}

if (!function_exists("pre")){
	function pre($arr,$admin = false)
	{
		if ($admin){
			global $USER;
			if(!is_object($USER))
				$USER = new CUser;	
			if (!$USER->IsAdmin()){
				return;
			}
		}
		echo "<pre>";
		if (is_object($arr)){
			print_r(get_class($arr));
			echo PHP_EOL;
			print_r((array)$arr);
		}elseif(is_array($arr)){
			print_r($arr);
		}else{
			var_dump($arr);
		}
		
		if($GLOBALS["APPLICATION"]){
			if($ex = $GLOBALS["APPLICATION"]->GetException()){
				$strError = $ex->GetString();
				print_r($strError);
			}		
		}	
		echo "</pre>";
	}
}
function LA($arData)
{
    global $USER;
    if ($USER->IsAdmin()) {

        echo "<pre>";
        print_r($arData);
        echo "</pre>";


    }
}



 
AddEventHandler('aspro.max', 'OnAsproGetBuyBlockElement', 'GetAddToBasketArrayCustom');
function GetAddToBasketArrayCustom(
	$arItem, $totalCount, $arParams, &$arOptions //,$defaultCount = 1, $basketUrl = '', $bDetail = false, $arItemIDs = array(), $class_btn = "small", $arParams=array()
){
		 
		$defaultCount = $arParams["DEFAULT_COUNT"];
		$basketUrl = $arParams["BASKET_URL"];
		$bDetail = true;
		if(is_array($arItem['PROPERTIES']['CML2_LINK'])){
			//sku
			$bDetail = false;
		}
		$arItemIDs = CMax::GetItemsIDs($arItem, "Y");
		$class_btn = 'btn-lg'; 
		
		/*
		1 - Нужно добавить доп сво-во к элементу торгового предложения ТП - Под заказ
		2- Делаем механизм с возможностью:
		- купить через корзину - для ТП В НАЛИЧИИ
		- заказать - для ТП под заказ
		*/
		if(is_array($arItem['PROPERTIES']['ON_ORDER'])){
			 if("Y" == $arItem['PROPERTIES']['ON_ORDER']['VALUE']){
				$totalCount = 0;
			 }else{
				$totalCount = 10000;
			 }
		}
		
		 
		
		 
		static $arAddToBasketOptions, $bUserAuthorized;
		if($arAddToBasketOptions === NULL){
			$arAddToBasketOptions = array(
				"SHOW_BASKET_ONADDTOCART" => \Bitrix\Main\Config\Option::get(CMax::moduleID, "SHOW_BASKET_ONADDTOCART", "Y", SITE_ID) == "Y",
				"USE_PRODUCT_QUANTITY_LIST" => \Bitrix\Main\Config\Option::get(CMax::moduleID, "USE_PRODUCT_QUANTITY_LIST", "Y", SITE_ID) == "Y",
				"USE_PRODUCT_QUANTITY_DETAIL" => \Bitrix\Main\Config\Option::get(CMax::moduleID, "USE_PRODUCT_QUANTITY_DETAIL", "Y", SITE_ID) == "Y",
				"BUYNOPRICEGGOODS" => \Bitrix\Main\Config\Option::get(CMax::moduleID, "BUYNOPRICEGGOODS", "NOTHING", SITE_ID),
				"BUYMISSINGGOODS" => \Bitrix\Main\Config\Option::get(CMax::moduleID, "BUYMISSINGGOODS", "ADD", SITE_ID),
				"EXPRESSION_ORDER_BUTTON" => \Bitrix\Main\Config\Option::get(CMax::moduleID, "EXPRESSION_ORDER_BUTTON", GetMessage("EXPRESSION_ORDER_BUTTON_DEFAULT"), SITE_ID),
				"EXPRESSION_ORDER_TEXT" => \Bitrix\Main\Config\Option::get(CMax::moduleID, "EXPRESSION_ORDER_TEXT", GetMessage("EXPRESSION_ORDER_TEXT_DEFAULT"), SITE_ID),
				"EXPRESSION_SUBSCRIBE_BUTTON" => \Bitrix\Main\Config\Option::get(CMax::moduleID, "EXPRESSION_SUBSCRIBE_BUTTON", GetMessage("EXPRESSION_SUBSCRIBE_BUTTON_DEFAULT"), SITE_ID),
				"EXPRESSION_SUBSCRIBED_BUTTON" => \Bitrix\Main\Config\Option::get(CMax::moduleID, "EXPRESSION_SUBSCRIBED_BUTTON", GetMessage("EXPRESSION_SUBSCRIBED_BUTTON_DEFAULT"), SITE_ID),
				"EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT" => \Bitrix\Main\Config\Option::get(CMax::moduleID, "EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT", GetMessage("EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT"), SITE_ID),
				"EXPRESSION_ADDEDTOBASKET_BUTTON_DEFAULT" => \Bitrix\Main\Config\Option::get(CMax::moduleID, "EXPRESSION_ADDEDTOBASKET_BUTTON_DEFAULT", GetMessage("EXPRESSION_ADDEDTOBASKET_BUTTON_DEFAULT"), SITE_ID),
				"EXPRESSION_READ_MORE_OFFERS_DEFAULT" => \Bitrix\Main\Config\Option::get(CMax::moduleID, "EXPRESSION_READ_MORE_OFFERS_DEFAULT", GetMessage("EXPRESSION_READ_MORE_OFFERS_DEFAULT"), SITE_ID),
			);

			global $USER;
			$bUserAuthorized = $USER->IsAuthorized();
		}
		
		
		$buttonText = $buttonHTML = $buttonACTION = '';
		$quantity=$ratio=1;
		$max_quantity=0;
		$float_ratio=is_double($arItem["CATALOG_MEASURE_RATIO"]);		

		$minPriceRangeQty = 0;
		// if (isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) {
		if (isset($arItem['ITEM_PRICE_MODE']) && $arItem['ITEM_PRICE_MODE'] === 'Q') {
			$priceSelected = $arItem['ITEM_PRICE_SELECTED'];
			if (isset($arItem['FIX_PRICE_MATRIX']) && $arItem['FIX_PRICE_MATRIX']) {
				$priceSelected = $arItem['FIX_PRICE_MATRIX']['PRICE_SELECT'];
			}
			if (isset($arItem['ITEM_PRICES']) && $arItem['ITEM_PRICES'][$priceSelected]['MIN_QUANTITY'] != 1) {
				$minPriceRangeQty = $arItem['ITEM_PRICES'][$priceSelected]['MIN_QUANTITY'];
			}
		}	
		
		$setMinQty = false;
		if ($arItem["CATALOG_MEASURE_RATIO"] || $minPriceRangeQty) {
			if ($minPriceRangeQty && ($minPriceRangeQty > $arItem["CATALOG_MEASURE_RATIO"])) {
				$quantity=$minPriceRangeQty;
				$setMinQty = true;
			} else {
				$quantity=$arItem["CATALOG_MEASURE_RATIO"];
			}
			if ($arItem["CATALOG_MEASURE_RATIO"]) {
				$ratio=$arItem["CATALOG_MEASURE_RATIO"];
			}
		} else {
			$quantity=$defaultCount;
		}

		if($arItem["CATALOG_QUANTITY_TRACE"]=="Y"){
			if($totalCount < $quantity){
				$quantity=($totalCount>$arItem["CATALOG_MEASURE_RATIO"] ? $totalCount : $arItem["CATALOG_MEASURE_RATIO"] );
			}
			if ($arItem["CATALOG_CAN_BUY_ZERO"] !== "Y") {
				$max_quantity=$totalCount;
			}
		}
		
		$canBuy = $arItem["CAN_BUY"];
		if($arParams['USE_REGION'] == 'Y' && $arParams['STORES'])
		{
			$canBuy = ( ($totalCount && ($arItem["OFFERS"] || $arItem["CAN_BUY"])) || ((!$totalCount && $arItem["CATALOG_QUANTITY_TRACE"] == "N") || (!$totalCount && $arItem["CATALOG_QUANTITY_TRACE"] == "Y" && $arItem["CATALOG_CAN_BUY_ZERO"] == "Y")) );
		}
		$arItem["CAN_BUY"] = $canBuy;

		//for buy_services in basket_fly
		if( isset($arParams["EXACT_QUANTITY"]) && $arParams["EXACT_QUANTITY"] > 0 )
			$quantity=$arParams["EXACT_QUANTITY"];
		

		$arItemProps = $arItem['IS_OFFER'] === 'Y' ? ($arParams['OFFERS_CART_PROPERTIES'] ? implode(';', $arParams['OFFERS_CART_PROPERTIES']) : "") : ($arParams['PRODUCT_PROPERTIES'] ? implode(';', $arParams['PRODUCT_PROPERTIES']) : "");
		$partProp=($arParams["PARTIAL_PRODUCT_PROPERTIES"] ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : "" );
		$addProp=($arParams["ADD_PROPERTIES_TO_BASKET"] ? $arParams["ADD_PROPERTIES_TO_BASKET"] : "" );
		$emptyProp=$arItem["EMPTY_PROPS_JS"];
		$bShowOutOfProduction = isset($arItem['PROPERTIES']['OUT_OF_PRODUCTION']) && $arItem['PROPERTIES']['OUT_OF_PRODUCTION']['VALUE'] === 'Y';

		if ($bShowOutOfProduction) {
			$buttonACTION = "OUT_OF_PRODUCTION";
			if (isset($arItem['PROPERTIES']['PRODUCT_ANALOG_FILTER']) && $arItem['PROPERTIES']['PRODUCT_ANALOG_FILTER']['VALUE']) {
				$buttonTEXT = GetMessage('EXPRESSION_OUT_OF_PRODUCTION_TEXT');
				$buttonHTML = "<a href='".$arItem['PROPERTIES']['PRODUCT_ANALOG_FILTER']['VALUE']."' class='btn btn-default btn-wide transition_bg ".$class_btn." has-ripple btn--wrap-text fill-use-999' title='".$buttonTEXT."'>".(!$bDetail ? CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/catalog/catalog_icons.svg#similar-19-16", "", ['WIDTH' => 19,'HEIGHT' => 16]) : '')."<span>".$buttonTEXT."</span></a>";
			}elseif (!$bDetail) {
				$buttonText = array($arAddToBasketOptions['EXPRESSION_READ_MORE_OFFERS_DEFAULT']);
				$buttonHTML = '<a class="btn btn-default basket read_more '.$class_btn.'" rel="nofollow" href="'.$arItem["DETAIL_PAGE_URL"].'" data-item="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/more_c.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></a>';
			}
		} elseif ($arItem["OFFERS"]) {
			global $arTheme;
			$type_sku = is_array($arTheme) ? (isset($arTheme["TYPE_SKU"]["VALUE"]) ? $arTheme["TYPE_SKU"]["VALUE"] : $arTheme["TYPE_SKU"]) : 'TYPE_1';
			if(!$bDetail && $arItem["OFFERS_MORE"] != "Y" && $type_sku != "TYPE_2"){
				$buttonACTION = 'ADD';
				$buttonText = array($arAddToBasketOptions['EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT'], $arAddToBasketOptions['EXPRESSION_ADDEDTOBASKET_BUTTON_DEFAULT']);
				$buttonHTML = '<span class="btn btn-default transition_bg '.$class_btn.' read_more1 to-cart animate-load" id="'.$arItemIDs['BUY_LINK'].'" data-offers="N" data-iblockID="'.$arItem["IBLOCK_ID"].'" data-item="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/basket.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></span><a rel="nofollow" href="'.$basketUrl.'" id="'.$arItemIDs['BASKET_LINK'].'" class="'.$class_btn.' in-cart btn btn-default transition_bg" data-item="'.$arItem["ID"].'"  style="display:none;">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/inbasket.svg", $buttonText[1]).'<span>'.$buttonText[1].'</span></a>';
			}
			elseif(($bDetail && $arItem["FRONT_CATALOG"] == "Y") || $arItem["OFFERS_MORE"]=="Y" || $type_sku == "TYPE_2"){
				$buttonACTION = 'MORE';
				$buttonText = array($arAddToBasketOptions['EXPRESSION_READ_MORE_OFFERS_DEFAULT']);
				$buttonHTML = '<a class="btn btn-default basket read_more '.$class_btn.'" rel="nofollow" href="'.$arItem["DETAIL_PAGE_URL"].'" data-item="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/more_c.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></a>';
			}
		} elseif ($arItem["SHOW_MORE_BUTTON"] == "Y") {
			$buttonACTION = 'MORE';
			$buttonText = array($arAddToBasketOptions['EXPRESSION_READ_MORE_OFFERS_DEFAULT']);
			$buttonHTML = '<a class="btn btn-default basket read_more '.$class_btn.'" rel="nofollow" href="'.$arItem["DETAIL_PAGE_URL"].'" data-item="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/more_c.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></a>';
		}
		else{
			if($bPriceExists = isset($arItem["MIN_PRICE"]) && $arItem["MIN_PRICE"]["VALUE"] > 0){
				// price exists
				if($totalCount > 0 && (isset($arItem["CAN_BUY"]) && $arItem["CAN_BUY"])){
					// rest exists
					if((isset($arItem["CAN_BUY"]) && $arItem["CAN_BUY"]) || (isset($arItem["MIN_PRICE"]) && $arItem["MIN_PRICE"]["CAN_BUY"] == "Y")){
						if($bDetail && $arItem["FRONT_CATALOG"] == "Y"){
							$buttonACTION = 'MORE';
							$buttonText = array($arAddToBasketOptions['EXPRESSION_READ_MORE_OFFERS_DEFAULT']);
							$rid=($arItem["RID"] ? "?RID=".$arItem["RID"] : "");
							$buttonHTML = '<a class="btn btn-default transition_bg basket read_more '.$class_btn.'" rel="nofollow" href="'.$arItem["DETAIL_PAGE_URL"].$rid.'" data-item="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/more_c.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></a>';
						}
						else{

							$arItem["CAN_BUY"] = 1;
							$buttonACTION = 'ADD';
							$buttonText = array($arAddToBasketOptions['EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT'], $arAddToBasketOptions['EXPRESSION_ADDEDTOBASKET_BUTTON_DEFAULT']);
							$buttonHTML = '<span data-value="'.$arItem["MIN_PRICE"]["DISCOUNT_VALUE"].'" data-currency="'.$arItem["MIN_PRICE"]["CURRENCY"].'" class="'.$class_btn.' to-cart btn btn-default transition_bg animate-load" data-item="'.$arItem["ID"].'" data-float_ratio="'.$float_ratio.'" data-ratio="'.$ratio.'" data-bakset_div="bx_basket_div_'.$arItem["ID"].($arItemIDs['DOP_ID'] ? '_'.$arItemIDs['DOP_ID'] : '').'" data-props="'.$arItemProps.'" data-part_props="'.$partProp.'" data-add_props="'.$addProp.'"  data-empty_props="'.$emptyProp.'" data-offers="'.$arItem["IS_OFFER"].'" data-iblockID="'.$arItem["IBLOCK_ID"].'"  data-quantity="'.$quantity.'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/basket.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></span><a rel="nofollow" href="'.$basketUrl.'" class="'.$class_btn.' in-cart btn btn-default transition_bg" data-item="'.$arItem["ID"].'"  style="display:none;">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/inbasket.svg", $buttonText[1]).'<span>'.$buttonText[1].'</span></a>';
						}
					}
					elseif($arItem["CATALOG_SUBSCRIBE"] == "Y"){
						$buttonACTION = 'SUBSCRIBE';
						$buttonText = array($arAddToBasketOptions['EXPRESSION_SUBSCRIBE_BUTTON'], $arAddToBasketOptions['EXPRESSION_SUBSCRIBED_BUTTON']);
						$buttonHTML = '<span class="'.$class_btn.' ss to-subscribe'.(!$bUserAuthorized ? ' auth' : '').(CMax::checkVersionModule('16.5.3', 'catalog') ? ' nsubsc' : '').' btn btn-default transition_bg" rel="nofollow" data-param-form_id="subscribe" data-name="subscribe" data-param-id="'.$arItem["ID"].'" data-item="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/subsribe_c.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></span><span class="'.$class_btn.' ss in-subscribe btn btn-default transition_bg" rel="nofollow" style="display:none;" data-item="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/subsribe_c.svg", $buttonText[0]).'<span>'.$buttonText[1].'</span></span>';
					}
				}
				else{
					if(!strlen($arAddToBasketOptions['EXPRESSION_ORDER_BUTTON'])){
						$arAddToBasketOptions['EXPRESSION_ORDER_BUTTON']=GetMessage("EXPRESSION_ORDER_BUTTON_DEFAULT");
					}
					if(!strlen($arAddToBasketOptions['EXPRESSION_SUBSCRIBE_BUTTON'])){
						$arAddToBasketOptions['EXPRESSION_SUBSCRIBE_BUTTON']=GetMessage("EXPRESSION_SUBSCRIBE_BUTTON_DEFAULT");
					}
					if(!strlen($arAddToBasketOptions['EXPRESSION_SUBSCRIBED_BUTTON'])){
						$arAddToBasketOptions['EXPRESSION_SUBSCRIBED_BUTTON']=GetMessage("EXPRESSION_SUBSCRIBED_BUTTON_DEFAULT");
					}
					// no rest
					if($bDetail && $arItem["FRONT_CATALOG"] == "Y"){
						$buttonACTION = 'MORE';
						$buttonText = array($arAddToBasketOptions['EXPRESSION_READ_MORE_OFFERS_DEFAULT']);
						$rid=($arItem["RID"] ? "?RID=".$arItem["RID"] : "");
						$buttonHTML = '<a class="btn btn-default basket read_more '.$class_btn.'" rel="nofollow" href="'.$arItem["DETAIL_PAGE_URL"].$rid.'" data-item="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/more_c.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></a>';
					}
					else{
						$buttonACTION = $arAddToBasketOptions["BUYMISSINGGOODS"];
						if($arAddToBasketOptions["BUYMISSINGGOODS"] == "ADD" /*|| $arItem["CAN_BUY"]*/){
							if($arItem["CAN_BUY"]){
								$buttonText = array($arAddToBasketOptions['EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT'], $arAddToBasketOptions['EXPRESSION_ADDEDTOBASKET_BUTTON_DEFAULT']);
								$buttonHTML = '<span data-value="'.$arItem["MIN_PRICE"]["DISCOUNT_VALUE"].'" data-currency="'.$arItem["MIN_PRICE"]["CURRENCY"].'" class="'.$class_btn.' to-cart btn btn-default transition_bg animate-load" data-item="'.$arItem["ID"].'" data-float_ratio="'.$float_ratio.'" data-ratio="'.$ratio.'" data-bakset_div="bx_basket_div_'.$arItem["ID"].'" data-props="'.$arItemProps.'" data-part_props="'.$partProp.'" data-add_props="'.$addProp.'"  data-empty_props="'.$emptyProp.'" data-offers="'.$arItem["IS_OFFER"].'" data-iblockID="'.$arItem["IBLOCK_ID"].'" data-quantity="'.$quantity.'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/basket.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></span><a rel="nofollow" href="'.$basketUrl.'" class="'.$class_btn.' in-cart btn btn-default transition_bg" data-item="'.$arItem["ID"].'"  style="display:none;">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/inbasket.svg", $buttonText[1]).'<span>'.$buttonText[1].'</span></a>';
							}else{
								if($arAddToBasketOptions["BUYMISSINGGOODS"] == "SUBSCRIBE" && $arItem["CATALOG_SUBSCRIBE"] == "Y"){
									$buttonText = array($arAddToBasketOptions['EXPRESSION_SUBSCRIBE_BUTTON'], $arAddToBasketOptions['EXPRESSION_SUBSCRIBED_BUTTON']);
									$buttonHTML = '<span class="'.$class_btn.' ss to-subscribe'.(!$bUserAuthorized ? ' auth' : '').(CMax::checkVersionModule('16.5.3', 'catalog') ? ' nsubsc' : '').' btn btn-default transition_bg" rel="nofollow" data-name="subscribe" data-param-form_id="subscribe" data-param-id="'.$arItem["ID"].'"  data-item="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/subsribe_c.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></span><span class="'.$class_btn.' ss in-subscribe btn btn-default transition_bg" rel="nofollow" style="display:none;" data-item="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/subsribe_c.svg", $buttonText[0]).'<span>'.$buttonText[1].'</span></span>';
								}else{
									$buttonText = array($arAddToBasketOptions['EXPRESSION_ORDER_BUTTON']);
									$buttonHTML = '<span class="'.$class_btn.' to-order btn btn-default animate-load" data-event="jqm" data-param-form_id="TOORDER" data-name="toorder" data-autoload-product_name="'.CMax::formatJsName($arItem["NAME"]).'" data-autoload-product_id="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/mail_c.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></span>';
									if($arAddToBasketOptions['EXPRESSION_ORDER_TEXT']){
										$buttonHTML .='<div class="more_text">'.$arAddToBasketOptions['EXPRESSION_ORDER_TEXT'].'</div>';
									}
								}
							}

						}
						elseif($arAddToBasketOptions["BUYMISSINGGOODS"] == "SUBSCRIBE" && $arItem["CATALOG_SUBSCRIBE"] == "Y"){

							$buttonText = array($arAddToBasketOptions['EXPRESSION_SUBSCRIBE_BUTTON'], $arAddToBasketOptions['EXPRESSION_SUBSCRIBED_BUTTON']);
							$buttonHTML = '<span class="'.$class_btn.' ss to-subscribe '.(!$bUserAuthorized ? ' auth' : '').(CMax::checkVersionModule('16.5.3', 'catalog') ? ' nsubsc' : '').' btn btn-default transition_bg" data-name="subscribe" data-param-form_id="subscribe" data-param-id="'.$arItem["ID"].'"  rel="nofollow" data-item="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/mail_c.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></span><span class="'.$class_btn.' ss in-subscribe btn btn-default transition_bg" rel="nofollow" style="display:none;" data-item="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/mail_c.svg", $buttonText[0]).'<span>'.$buttonText[1].'</span></span>';
						}
						elseif($arAddToBasketOptions["BUYMISSINGGOODS"] == "ORDER"){
							$buttonText = array($arAddToBasketOptions['EXPRESSION_ORDER_BUTTON']);
							$buttonHTML = '<span class="'.$class_btn.' to-order btn btn-default animate-load" data-event="jqm" data-param-form_id="TOORDER" data-name="toorder" data-autoload-product_name="'.CMax::formatJsName($arItem["NAME"]).'" data-autoload-product_id="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/mail_c.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></span>';
							if($arAddToBasketOptions['EXPRESSION_ORDER_TEXT']){
								$buttonHTML .='<div class="more_text">'.$arAddToBasketOptions['EXPRESSION_ORDER_TEXT'].'</div>';
							}
						}
					}
				}
			}
			else{
				// no price or price <= 0
				if($bDetail && $arItem["FRONT_CATALOG"] == "Y"){
					$buttonACTION = 'MORE';
					$buttonText = array($arAddToBasketOptions['EXPRESSION_READ_MORE_OFFERS_DEFAULT']);
					$buttonHTML = '<a class="btn btn-default transition_bg basket read_more '.$class_btn.'" rel="nofollow" href="'.$arItem["DETAIL_PAGE_URL"].'" data-item="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/more_c.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></a>';
				}
				else{
					$buttonACTION = $arAddToBasketOptions["BUYNOPRICEGGOODS"];
					if($arAddToBasketOptions["BUYNOPRICEGGOODS"] == "ORDER"){
						$buttonText = $arAddToBasketOptions['EXPRESSION_ORDER_BUTTON'] ? array($arAddToBasketOptions['EXPRESSION_ORDER_BUTTON']) : array(Loc::getMessage('EXPRESSION_ORDER_BUTTON_DEFAULT'));
						$buttonHTML = '<span class="'.$class_btn.' to-order btn btn-default animate-load" data-event="jqm" data-param-form_id="TOORDER" data-name="toorder" data-autoload-product_name="'.CMax::formatJsName($arItem["NAME"]).'" data-autoload-product_id="'.$arItem["ID"].'">'.CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/mail_c.svg", $buttonText[0]).'<span>'.$buttonText[0].'</span></span>';
						if($arAddToBasketOptions['EXPRESSION_ORDER_TEXT']){
							$buttonHTML .='<div class="more_text">'.$arAddToBasketOptions['EXPRESSION_ORDER_TEXT'].'</div>';
						}
					}
				}
			}
		}
		
		//add name atr for js notice
		$buttonHTML .= '<span class="hidden" data-js-item-name="'.CMax::formatJsName($arItem['IPROPERTY_VALUES']["ELEMENT_PAGE_TITLE"] ?? $arItem['NAME']).'"></span>';

		$arOptions = array("OPTIONS" => $arAddToBasketOptions, "TEXT" => $buttonText, "HTML" => $buttonHTML, "ACTION" => $buttonACTION, "RATIO_ITEM" => $ratio, "MIN_QUANTITY_BUY" => $quantity, "MAX_QUANTITY_BUY" => $max_quantity, "CAN_BUY" => $canBuy);

		if ($setMinQty) {
			$arOptions["SET_MIN_QUANTITY_BUY"] = true;
		}
		
		 
		
		return $arOptions;
	}


/*
 * Работа с canonical
 */
/*
Необходимо со страниц карточек товаров типа*?oid=* и страниц пагинации удалить canonical. 
*/
//получение canonical url
function get_canonical_url($APPLICATION)
{
    $canonical = $APPLICATION->IsHTTPS() ? 'https://' : 'http://';
    $canonical .= $_SERVER['HTTP_HOST'];
    //$canonical .= $APPLICATION->GetCurPage();
	
	$not_ok_param = array();
	foreach($_GET as $key=>$val){
		if(substr( $key, 0, 5 ) == "PAGEN"){
			$not_ok_param[] = $key;
		}
		if($key == "oid"){
			$not_ok_param[] = $key;
		}
	}
	 
	if(count($not_ok_param) == 0){
		$canonical .= $APPLICATION->GetCurPage();
	}else{
		$canonical = false;
	}
	
    return $canonical;
}



AddEventHandler("main", "OnEpilog", "addCanonical",9999);

function addCanonical()
{
    global $APPLICATION;

    if (!defined('ERROR_404')) {
		if($canonical = get_canonical_url($APPLICATION)){
			$APPLICATION->SetPageProperty('canonical', $canonical);
		} 
	}
}

function getNumEnding($number, $endingArray)
{
    $number = $number % 100;
    if ($number>=11 && $number<=19) {
        $ending=$endingArray[2];
    }
    else {
        $i = $number % 10;
        switch ($i)
        {
            case (1): $ending = $endingArray[0]; break;
            case (2):
            case (3):
            case (4): $ending = $endingArray[1]; break;
            default: $ending=$endingArray[2];
        }
    }
    return $ending;
}

// {=number_ending this.property.MWI_BOX_QUANTITY "штука" "штуки" "штук"}
//Подключаем модуль инфоблоков 
if (\Bitrix\Main\Loader::includeModule('iblock'))
{ 
   //регистрируем обработчик события
   \Bitrix\Main\EventManager::getInstance()->addEventHandler(
      "iblock",
      "OnTemplateGetFunctionClass",
      array("FunctionNumberEnding", "eventHandler")
   ); 
   //подключаем файл с определением класса FunctionBase
   //это пока требуется т.к. класс не описан в правилах автозагрузки
   include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/lib/template/functions/fabric.php");
   class FunctionNumberEnding extends \Bitrix\Iblock\Template\Functions\FunctionBase
   {
      //Обработчик события на вход получает имя требуемой функции
      //парсер её нашел в строке SEO
      public static function eventHandler($event)
      {
         $parameters = $event->getParameters();
         $functionName = $parameters[0];
         if ($functionName === "number_ending")
         {
            //обработчик должен вернуть SUCCESS и имя класса
            //который будет отвечать за вычисления
            return new \Bitrix\Main\EventResult(
               \Bitrix\Main\EventResult::SUCCESS,
               "\\FunctionNumberEnding"
            );
         }
      }
      //собственно функция выполняющая "магию"
      public function calculate($parameters)
      {
         $result = $this->parametersToArray($parameters);
		 
         return getNumEnding($result[0], array($result[1],$result[2],$result[3]));  
      }
   }
}



AddEventHandler('aspro.max', 'OnAsproShowSectionGallery', 'OnAsproShowSectionGalleryCustom');
function OnAsproShowSectionGalleryCustom(
	$arItem,  &$html
	
){
	
	$TITLE = $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] ?: $arItem['NAME'];
	
	$html = str_replace(
		'href="'.$arItem["DETAIL_PAGE_URL"].'"', 
		'href="'.$arItem["DETAIL_PAGE_URL"].'" title="'.$TITLE.'" data-img-title '
		, $html
	);
}

