<?

CModule::AddAutoloadClasses(
	'als.typograf',
	Array(
		'ALSTypograf'		=> 'classes/general/typograf.php',							// Общий класс для работы с типографом
	)
);

if(ADMIN_SECTION == true) {
	CUtil::InitJSCore(Array('jquery'));

}