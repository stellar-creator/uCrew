<?php
	$this->ucModules->addModule(
		"uCrewProjects",								// Module name
		"uCrew projects module.",						// Description
		"Bussiness module",								// Category
		"Stellar Creator",								// Author
		[
			"projects" => "Проекты",
			"pcb" => "Печатные платы",
			"mechanics" => "Механика",
			"cables" => "Кабели",
			"other" => "Различные изделия",
		],												// Pages
		["configuration" => [							// Configuration
				"menu" => 1, 
				"content" => 1, 
				"privileges" => "projects"
			]
		],
		["section" => "Разработки"],						// Menu section
		["icon" => "fa fa-tasks"]						// Menu icon
	);

	// TODO: add applets API
	$this->ucModules->addApplet(
		"info",											// File
		"uCrewProjectsInfo",							// Class name	
		[
			"getTotalProjectsCount" => "getTotalProjectsCount",	// Virtual Link => Function in class
			"getRemovedProjects" => "getRemovedProjects"
		]
	);
?>