<?php
	$this->ucModules->addModule(
		"uCrewAuthorization",					// Module name
		"Default uCrew authorization module.",	// Description
		"System module",						// Category
		"Stellar Creator",						// Author
		[
			"authorization" => "Авторизация",
			"registration" => "Регистрация",
			"recovery" => "Восстановление пароля",
			"newpassword" => "Новый пароль"
		],										// Pages
		["configuration" => [				
				"menu" => 0, 
				"content" => 0, 
				"privileges" => 0
			]
		],
		["section" => "Авторизация"]		
	);
?>