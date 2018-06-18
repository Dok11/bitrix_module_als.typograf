<?
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

Class als_typograf extends CModule {
	var $MODULE_ID = 'als.typograf';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = 'Y';

	public function __construct() {
		$arModuleVersion = array();

		$path = str_replace('\\', '/', __FILE__);
		$path = substr($path, 0, strlen($path) - strlen('/index.php'));
		include($path . '/version.php');

		$this->MODULE_VERSION		= $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE	= $arModuleVersion['VERSION_DATE'];

		$this->MODULE_NAME			= GetMessage('ALS_TYPOGRAF_INSTALL_NAME');
		$this->MODULE_DESCRIPTION	= GetMessage('ALS_TYPOGRAF_INSTALL_DESCRIPTION');

		$this->PARTNER_NAME			= GetMessage('ALS_TYPOGRAF_PARTNER');
		$this->PARTNER_URI			= 'http://www.artlebedev.ru/';
	}

	function InstallDB($install_wizard = true) {
		RegisterModule($this->MODULE_ID);

		$eventManager = \Bitrix\Main\EventManager::getInstance();

		$eventManager->registerEventHandler("fileman", "OnIncludeHTMLEditorScript", "als.typograf", "ALSTypograf", "addButton");
		$eventManager->registerEventHandler("main", "OnAdminContextMenuShow", "als.typograf", "ALSTypograf", "addButtonName");

		return true;
	}

	function UnInstallDB($arParams = Array()) {
		UnRegisterModule($this->MODULE_ID);

		$eventManager = \Bitrix\Main\EventManager::getInstance();

		$eventManager->unRegisterEventHandler("fileman", "OnIncludeHTMLEditorScript", "als.typograf", "ALSTypograf", "addButton");
		$eventManager->unRegisterEventHandler("main", "OnAdminContextMenuShow", "als.typograf", "ALSTypograf", "addButtonName");

		return true;
	}


	function InstallFiles() {
		return true;
	}

	function UnInstallFiles() {
		return true;
	}

	function DoInstall() {
		$this->InstallDB(false);
	}

	function DoUninstall() {
		$this->UnInstallDB();
	}
}
