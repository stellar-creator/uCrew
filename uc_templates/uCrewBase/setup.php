<?php
	// Add JavaScripts
	$this->uc_CompilatorData->addJavaScript('uc_resources/distribution/fontawesome/js/all.min.js');
	$this->uc_CompilatorData->addJavaScript('uc_resources/distribution/bootstrap/js/bootstrap.bundle.min.js');
	$this->uc_CompilatorData->addJavaScript('uc_resources/distribution/bootstrap/js/bootstrap.min.js');
	// Add styles
	$this->uc_CompilatorData->addCSS('uc_templates/uCrewBase/css/style.css');
	// Init header of template
	$this->uc_CompilatorData->template_header = '
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content="uCrew" />
';

	$this->uc_CompilatorData->template_footer = "";
?>