<?php
	$this->ucModules->addModule(
		"uCrewProjects",								// Module name
		"uCrew projects module.",						// Description
		"Bussiness module",								// Category
		"Stellar Creator",								// Author
		[
			//"projects" => "Проекты",
			//"device" => "Устройства",
			//"deviceItem" => "&Просмотр устройства",
			//"deviceAdd" => "&Добавить устройство",
			"projects" => "Проекты",
			"projectsView" => "&Просмотр проекта",
			"projectsAdd" => "&Добавить проект",			
			"devices" => "Устройства",
			"devicesView" => "&Просмотр устройства",
			"devicesAdd" => "&Добавить устройство",
			"pcbsItem" => "&Просмотр печатной платы",
			"pcbs" => "Печатные платы",
			"pcbsItem" => "&Просмотр печатной платы",
			"pcbsAdd" => "&Добавить печатную плату",
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