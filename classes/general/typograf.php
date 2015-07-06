<?php

class ALSTypograf {
	public static function Format($data, $charset = 'UTF-8', $entityType = 4, $useBr = 0, $useP = 0, $maxNobr = 3, $quotA = 'laquo raquo', $quotB = 'bdquo ldquo') {
		if(!CModule::IncludeModule('webservice')) {
			return false;
		}

		if(ToUpper($charset) != 'UTF-8') {
			$data = $GLOBALS['APPLICATION']->ConvertCharset($data, $charset, 'UTF-8');
		}

		$request = new CSOAPRequest("ProcessText", "http://typograf.artlebedev.ru/webservices/");

		$request->addParameter("text", htmlspecialchars($data));
		$request->addParameter("entityType", $entityType);
		$request->addParameter("useBr", $useBr);
		$request->addParameter("useP", $useP);
		$request->addParameter("maxNobr", $maxNobr);
		$request->addParameter("quotA", $quotA);
		$request->addParameter("quotB", $quotB);

		$client = new CSOAPClient('typograf.artlebedev.ru', '/webservices/typograf.asmx');
		$response = $client->send($request);

		return $response->Value['ProcessTextResult'];
	}

	static function addButton() {
		global $APPLICATION;
		$APPLICATION->AddHeadScript('/local/modules/als.typograf/f/js/add_htmleditor_button_typograf.js', true);
	}

	static function addButtonName(&$items) {
		global $APPLICATION;

		if($APPLICATION->GetCurPage() == "/bitrix/admin/iblock_element_edit.php") {
			$APPLICATION->SetAdditionalCSS('/local/modules/als.typograf/f/css/style.css');
		}
	}
}