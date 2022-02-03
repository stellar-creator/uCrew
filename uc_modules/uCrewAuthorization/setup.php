<?php
	$this->ucModules->addModule(
		"uCrewAuthorization",					// Module name
		"Default uCrew authorization module.",	// Description
		"System module",						// Category
		"Stellar Creator",						// Author
		["authorization" => "Авторизация"],		// Pages
		["configuration" => [				
				"menu" => 0, 
				"content" => 0, 
				"privileges" => 0
			]
		]										// Configuration [no menu, no header/footer/sidebar/headbar, no group privileges]				
	);
?>