<?php

/**
 * AJAX скрипт, возвращает оттипографированный текст
 *
 * CONTENT - контент, который нужно оттипографировать
 */

define("NO_KEEP_STATISTIC", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("als.typograf");


$sContentIn = $_REQUEST["CONTENT"];


if(!$sContentIn) {
	$arResult = Array(
		"ERROR"	=> true,
		"MSG"	=> "Требуется входной параметр CONTENT"
	);

	$jsonResult = json_encode($arResult);

	echo $jsonResult;
	return;
}


$sContentOut = ALSTypograf::Format($sContentIn);

$arResult = Array(
	"RESULT" => $sContentOut
);

$jsonResult = json_encode($arResult);


echo $jsonResult;
return;