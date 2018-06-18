<?

\CModule::AddAutoloadClasses(
	'als.typograf',
	Array(
		'ALSTypograf'		=> 'classes/general/typograf.php',					// Общий класс для работы с типографом
		'ALSTypografLight'	=> 'classes/general/typograf_light.php',			// Простой типограф на php
	)
);

if(ADMIN_SECTION === true) {
	\CUtil::InitJSCore(Array('jquery'));
}
