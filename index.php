<?php
	/**
	 * uCrew index code.
	 */
	// Show all errors
	error_reporting(E_ALL);
	error_reporting(-1);
	ini_set('error_reporting', E_ALL);
	// Require base of CMS
	require_once("uc_system/uc_configuration.php");
	require_once("uc_system/uc_database.php");
	require_once("uc_system/uc_system.php");
	require_once("uc_system/uc_session.php");
	require_once("uc_system/uc_compilator.php");
	// Init system
	$ucSystem = new uCrewSystem();
?>