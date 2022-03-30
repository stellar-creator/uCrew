<?php
	$this->ucModules->addModule(
		"uCrewProjects",								// Module name
		"uCrew projects module.",						// Description
		"Bussiness module",								// Category
		"Stellar Creator",								// Author
		[
			"projects" => "Проекты",
			"device" => "Изделия",
			"deviceItem" => "&Просмотр изделия",
			"deviceAdd" => "&Добавить изделие",
			"pcb" => "Печатные платы",
			"pcbItem" => "&Просмотр печатной платы",
			"pcbAdd" => "&Добавить печатную плату",
			"mechanics" => "Механика",
			"mechanicsItem" => "&Просмотр изделия",
			"mechanicsAdd" => "&Добавить изделие",
			"cables" => "Кабели",
			"cablesItem" => "&Просмотр кабеля",
			"cablesAdd" => "&Добавить кабель"
			//"other" => "Различные изделия",
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