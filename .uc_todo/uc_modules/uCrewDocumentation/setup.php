<?php
	$this->ucModules->addModule(
		"uCrewDocumentation",						// Module name
		"uCrew documentation module.",				// Description
		"Bussiness module",							// Category
		"Stellar Creator",							// Author
		[
			"main" => "Документация",		// Pages
		],											
		["configuration" => [				
				"menu" => 1, 
				"content" => 1, 
				"privileges" => 0
			]
		],
		["section" => "Документация"],
		["icon" => "fa fa-book"]	
	);
?>