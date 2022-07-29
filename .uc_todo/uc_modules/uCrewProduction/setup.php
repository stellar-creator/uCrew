<?php
	$this->ucModules->addModule(
		"uCrewProduction",								// Module name
		"uCrew production module.",					// Description
		"Bussiness module",							// Category
		"Stellar Creator",							// Author
		[
			"main" => "Сводная информация",
			"addTask" => "Поставить задачу",
			"controlTasks" => "&Управление задачами",
			"nomenclature" => "Номенклатура"
		],
		["configuration" => [				
				"menu" => 1, 
				"content" => 1, 
				"privileges" => "production"
			]
		],
		["section" => "Производство"],
		["icon" => "fa fa-industry"]	
	);
?>