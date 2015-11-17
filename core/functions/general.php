<?php


function array_sanitize(&$item){
	$item = htmlentities(strip_tags(mysql_real_escape_string($item)));
}

function sanitize($data){
	return htmlentities(strip_tags(mysql_real_escape_string($data)));
}

function output_errors($errors){
	$output = array();
	foreach($errors as $error){
		$output[] = '<li>'. $error .'.</li>';
	}
	return implode('',$output);
}

?>