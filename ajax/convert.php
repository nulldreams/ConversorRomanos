<?php
require('../config.php');

$RomConverter = new RomConverter();

$result = new stdClass();
$romanValue = '';
$decimalValue = 0;
$errors = 0;

if (intval($_POST['method']) === 0)
{
	$romanValue = strtoupper($_POST['number']);
	
	$decimalValue = $result->convertedValue = $RomConverter->romanToDecimal($romanValue, true, $errors);
}
else
{
	$decimalValue = intval($_POST['number']);
	
	$romanValue = $result->convertedValue = $RomConverter->decimalToRoman($decimalValue, true, $errors);
}

$result->errors = $errors;

if (!$errors)
{
	$DbLink = new DbLink('roman_numbers');
	$dataset = $DbLink->getData(array(), 'WHERE `decimal`=' . $decimalValue);
	
	if ($dataset->num_rows > 0)
	{
		$dbResult = $dataset->fetch_object();
		
		$result->dbData = array($dbResult->id, $dbResult->roman, $dbResult->decimal);
	}
	else
		$DbLink->setData(array('roman' => $romanValue, 'decimal' => $decimalValue));
}

echo json_encode($result);