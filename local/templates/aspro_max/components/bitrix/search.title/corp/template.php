<?
use CMax as Solution,
	Aspro\Max\Functions\Extensions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

Extensions::init('searchtitle');

$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input_corp";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);
$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

global $isFixedTopSearch;
$INPUT_ID_TMP = $INPUT_ID;
if(isset($isFixedTopSearch) && $isFixedTopSearch)
{
	$CONTAINER_ID .= 'tf';
	$INPUT_ID .= 'tf';
	$isFixedTopSearch = false;
}

$bShowSearchType = Aspro\Max\SearchTitle::isNeed2ShowWhere();
if ($bShowSearchType) {
	$searchType = Aspro\Max\SearchTitle::getType();
}
?>
<?if($arParams["SHOW_INPUT"] !== "N"):?>
	<?if($arParams["SHOW_INPUT_FIXED"] != "Y"):?>
		<div class="inline-search-block fixed with-close corp">
	<?endif;?>

		<div class="search-wrapper">
			<div id="<?=$CONTAINER_ID?>">
				<form action="<?=$arResult["FORM_ACTION"]?>" class="search<?=($bShowSearchType ? ' search--hastype' : '')?>">
					<div class="search-input-div">
						<input class="search-input" id="<?=$INPUT_ID?>" type="text" name="q" value="" placeholder="<?=GetMessage("CT_BST_SEARCH_BUTTON")?>" size="20" maxlength="50" autocomplete="off" />
					</div>
					<div class="search-button-div">
						<?if($arParams['SEARCH_ICON'] == 'Y'):?>
							<button class="btn btn-search" type="submit" name="s" value="<?=GetMessage("CT_BST_SEARCH_BUTTON2")?>">
								<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#search", "search2 ", ['WIDTH' => 17,'HEIGHT' => 17]);?>
							</button>
						<?else:?>
							<button class="btn btn-search-corp btn-default btn-lg round-ignore" type="submit" name="s" value="<?=GetMessage("CT_BST_SEARCH_BUTTON2")?>">
								<?=GetMessage("CT_BST_SEARCH_BUTTON2")?>
							</button>
						<?endif;?>

						<span class="close-block inline-search-hide"><?=CMax::showIconSvg("search svg-close close-icons colored_theme_hover", SITE_TEMPLATE_PATH."/images/svg/Close_white_path.svg");?>
						</span>
					</div>
				</form>
			</div>
		</div>

	<?if($arParams["SHOW_INPUT_FIXED"] != "Y"):?>
		</div>
	<?endif;?>
<?endif;?>
<script type="text/javascript">
	var jsControl = new JCTitleSearch4({
		//'WAIT_IMAGE': '/bitrix/themes/.default/images/wait.gif',
		'AJAX_PAGE' : '<?=CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
		'CONTAINER_ID': '<?=$CONTAINER_ID?>',
		'INPUT_ID': '<?=$INPUT_ID?>',
		'INPUT_ID_TMP': '<?=$INPUT_ID_TMP?>',
		'MIN_QUERY_LEN': 2
	});
</script>