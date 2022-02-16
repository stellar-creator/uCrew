<?php
	$this->ucModules->addModule(
		"uCrewStorage",									// Module name
		"uCrew storage module.",						// Description
		"Bussiness module",								// Category
		"Stellar Creator",								// Author
		[
			"main" => "Сводная информация",
			"nomenclature" => "Номенклатура",
			"extraditions" => "Выдачи",
			"categories" => "&Категории",				// Virtual page
			"locations" => "&Месторасположения", 		// Virtual page
			"add_сategory" => "&Добавить категорию", 	// Virtual page
			"item" => "&Просмотр позиции", 				// Virtual page
			"templates" => "&Управление шаблоном"		// Virtual page
		],												// Pages
		["configuration" => [				
				"menu" => 1, 
				"content" => 1, 
				"privileges" => 0
			]
		],
		["section" => "Склад"]		
	);
?>