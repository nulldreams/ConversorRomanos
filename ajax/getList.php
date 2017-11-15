<?php
require('../config.php');

$dbLink = new DbLink('roman_numbers');

$orderType = $_POST['order'][0]['dir'];
$search = $_POST['search']['value'];

if (strlen($search) > 0) {
	$dataset = $dbLink->getData(array('roman', 'decimal'), '
		WHERE
		`roman` LIKE \'%' .$search. '%\' OR
		`decimal` LIKE \'%' .$search. '%\'
		ORDER BY `decimal` ' . $orderType . '
		LIMIT ' . $_POST['start'] . ',' . $_POST['length']);
}
else {
	$dataset = $dbLink->getData(array('roman', 'decimal'), '
		ORDER BY `decimal` ' . $orderType . '
		LIMIT ' . $_POST['start'] . ',' . $_POST['length']);
}

$result = new stdClass();

$result->draw = $_POST['draw'];

$result->recordsTotal = $dataset->num_rows;
$result->recordsFiltered = $dataset->num_rows;
$result->data = array();


while ($rowData = $dataset->fetch_object())
{
	$result->data[] = array(
		$rowData->roman,
		$rowData->decimal,
	);
}

echo json_encode($result);