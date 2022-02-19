<?php
	$this->ucModules->addModule(
		"uCrewProjects",								// Module name
		"uCrew projects module.",						// Description
		"Bussiness module",								// Category
		"Stellar Creator",								// Author
		[
			"main" => "Все проекты"
		],												// Pages
		["configuration" => [							// Configuration
				"menu" => 1, 
				"content" => 1, 
				"privileges" => "projects"
			]
		],
		["section" => "Проекты"],						// Menu section
		["icon" => "fa fa-tasks"]						// Menu icon
	);
?>