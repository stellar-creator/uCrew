<?php
	$this->ucModules->addModule(
		"uCrewSetup",							// Module name
		"Default uCrew setup module.",			// Description
		"System module",						// Category
		"Stellar Creator",						// Author
		["install" => "Установка"],				// Pages	
		["configuration" => [				
				"menu" => 0, 
				"content" => 0, 
				"privileges" => 0
			]
		],
		["section" => "Установка"]	
	);
?>