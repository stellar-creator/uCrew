<?php
	$this->ucModules->addModule(
		"uCrewProjectsUploader",					// Module name
		"uCrewProjects remote uploader module.",	// Description
		"System module",							// Category
		"Stellar Creator",							// Author
		[
			"api" => "api"
		],											// Pages
		["configuration" => [				
				"menu" => 0, 
				"content" => 0, 
				"privileges" => 0
			]
		],
		["section" => "API"]		
	);
?>