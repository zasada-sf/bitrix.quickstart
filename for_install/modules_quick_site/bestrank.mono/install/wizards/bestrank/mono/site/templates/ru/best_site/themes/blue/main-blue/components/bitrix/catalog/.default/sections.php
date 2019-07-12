<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?//if ($APPLICATION->GetCurPage(true) == SITE_DIR."catalog/index.php") LocalRedirect(SITE_DIR);?>

    <div id="filter"  class="sidebar" >
<?
if (CModule::IncludeModule("iblock") && $arParams["USE_FILTER"]=="Y" )
{?>

<?$APPLICATION->IncludeComponent(
        "bitrix:catalog.smart.filter",
	"",
        Array(
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "SECTION_ID" => false,
            "FILTER_NAME" => $arParams["FILTER_NAME"],
            "PRICE_CODE" => $arParams["PRICE_CODE"],
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_NOTES" => "",
            "CACHE_GROUPS" => "Y",
            "SAVE_IN_SESSION" => "N"
        ),
        false
    );?>
<?
}
?>


	<div class="actions" style="margin-top: 10px">
	<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
	"AREA_FILE_SHOW" => "sect",
	"AREA_FILE_SUFFIX" => "actions",
	"AREA_FILE_RECURSIVE" => "Y",
	"EDIT_TEMPLATE" => ""
	),
	false
);?></div>
</div>


<div class="catalog-index" id="catalog-main">
<?
if($arParams["USE_COMPARE"]=="Y"):
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.compare.list",
		"best",
		Array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"NAME" => $arParams["COMPARE_NAME"],
			"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
			"COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
		),
		$component
	);
endif;
?>

<?

$sort =  array_key_exists("sort", $_REQUEST) && in_array($_REQUEST["sort"], Array("SORT", "CATALOG_PRICE_1")) ? $_REQUEST["sort"] : 'CATALOG_PRICE_1';
$sort_order = array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc,nulls", "desc")) ? ToLower($_REQUEST["order"]) : 'asc,nulls';

//echo $sort.' - '.$sort_order;

$sorting = '<div class="sort-order">'
.GetMessage("SECT_SORT_LABEL").':<br />'
.'<a  class="'.($sort_order=='asc,nulls'&&$sort=='CATALOG_PRICE_1' ? 'selected' : '').'" href="'.$APPLICATION->GetCurPageParam('order=asc,nulls&sort=CATALOG_PRICE_1', array('order', 'sort')).'" >'.GetMessage("CATALOG_SORT_PRICE_LESS").'</a> &nbsp; '
.'<a  class="'.($sort_order=='desc'&&$sort=='CATALOG_PRICE_1' ? 'selected' : '').'" href="'.$APPLICATION->GetCurPageParam('order=desc&sort=CATALOG_PRICE_1', array('order', 'sort')).'">'.GetMessage("CATALOG_SORT_PRICE_MORE").'</a> &nbsp; '
.'<a  class="'.($sort_order=='desc'&&$sort=='SORT' ? 'selected' : '').'" href="'.$APPLICATION->GetCurPageParam('order=desc&sort=SORT', array('order', 'sort')).'" >'.GetMessage("CATALOG_SORT_PRICE_POPULAR").'</a>'
.'</div>';

?><?=$sorting?>


<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"bar",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
        	"ELEMENT_SORT_FIELD" => $sort,//$arParams["ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $sort_order,//$arParams["ELEMENT_SORT_ORDER"],
		"PROPERTY_CODE" => false, //$arParams["LIST_PROPERTY_CODE"],
		"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
		"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
		"PAGE_ELEMENT_COUNT" => 4*$arParams["PAGE_ELEMENT_COUNT"],
		"LINE_ELEMENT_COUNT" => 4, //$arParams["LINE_ELEMENT_COUNT"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],

		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],

		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		//"INCLUDE_SUBSECTIONS" => "A",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],

		"COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
		"COMPARE_NAME" => $arParams["COMPARE_NAME"],
		"DISPLAY_IMG_WIDTH"	 =>	140, //$arParams["DISPLAY_IMG_WIDTH"],
		"DISPLAY_IMG_HEIGHT" =>	180, //$arParams["DISPLAY_IMG_HEIGHT"],

		"SHARPEN" => $arParams["SHARPEN"],
	),
	$component
);
?><?=$sorting?>
<div style="clear:both"></div>
</div>