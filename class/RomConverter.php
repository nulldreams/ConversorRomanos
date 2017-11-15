<?php

class RomConverter
{
	public const INVALID_DIGIT = (1 << 0);
	public const INVALID_PATTERN1 = (1 << 1);
	public const INVALID_PATTERN2 = (1 << 2);
	public const INVALID_PATTERN3 = (1 << 3);
	public const INVALID_PATTERN4 = (1 << 4);
	public const INVALID_RANGE = (1 << 5);
	
	protected const romValues = array(
		'i' => 1,
		'v' => 5,
		'x' => 10,
		'l' => 50,
		'c' => 100,
		'd' => 500,
		'm' => 1000,
	);
	
	protected const allowRepeat = array('i', 'x', 'c', 'm'); // Algarismos que podem se repetir consecutivamente.
	
	protected const specialValues = array('i', 'x', 'c'); // Algarismos que podem ser subtraídos.
	
	public function romanToDecimal(string $romNumber, bool $silent = false, int &$errors = NULL)
	{
		$romNumber = strtolower($romNumber);
		
		$errorBit = $this->validRoman($romNumber);
		
		if ($errors !== NULL) {
			$errors = $errorBit;
		}
		
		if (!$silent) {
			self::showErrors($errorBit);
		}
		
		$stringLength = strlen($romNumber);
		$decNumber = 0;
		$lastVal = 0;
		$curVal = 0;
		
		for ($i = $stringLength - 1; $i >= 0; $i--)
		{
			$curVal = self::romValues[$romNumber[$i]];
			
			if ($curVal >= $lastVal) {
				$decNumber += $curVal;
			}
			else {
				$decNumber -= $curVal;
			}
			
			$lastVal = $curVal;
		}
		
		return $decNumber;
	}
	
	public function DecimalToRoman(int $num, bool $silent = false, &$error = NULL)
	{
		if ($num > 3999 || $num < 1)
		{
			if ($error !== NULL) {
				$error = self::INVALID_RANGE;
			}
			
			if ($silent) {
				return '';
			}
			
			throw new Exception("O sistema aceita penas números entre 1 e 3999.", self::INVALID_RANGE);
		}
		
		$output = '';
		
		$parsedNum = self::parseNumber($num);
		
		foreach ($parsedNum as $chunkNumber) {
			$output .= self::formatDecimalToRoman($chunkNumber);
			
			
			echo "$output\n";
		}
		
		return strtoupper($output);
	}
	
	public function validRoman(string $romNumber)
	{
		$errors = 0;
		
		if (preg_match('/[^ivxlcdm]/', $romNumber)) {
			return self::INVALID_DIGIT;
		}
		
		$lastVal = -1;
		$curVal = -1;
		$curValChar = '';
		$lastValChar = '';
		$lowerBeforeHigherVal = 0;
		$repeat = 0;
		
		for ($i = 0; $i < strlen($romNumber); $i++)
		{
			$curValChar = $romNumber[$i];
			$curVal = self::romValues[$curValChar];
			
			if ($lastVal === -1)
			{
				$lastVal = $curVal;
				$lastValChar = $curValChar;
				
				continue;
			}
			
			if ($curVal > $lastVal)
			{
				if (!in_array($lastValChar, self::specialValues)) {
					$errors |= self::INVALID_PATTERN1;
				}
				
				$lowerBeforeHigherVal++;
				$repeat = 0;
			}
			else if ($curVal < $lastVal)
			{
				$lowerBeforeHigherVal = 0;
				$repeat = 0;
			}
			else
			{
				$lowerBeforeHigherVal = 0;
				
				if (!in_array($curValChar, self::allowRepeat)) {
					$errors |= self::INVALID_PATTERN3;
				}
				
				if (++$repeat === 3) {
					$errors |= self::INVALID_PATTERN4;
				}
			}
			
			if ($lowerBeforeHigherVal > 1) {
				$errors |= self::INVALID_PATTERN2;
			}
			
			$lastVal = $curVal;
			$lastValChar = $curValChar;
		}
		
		return $errors;
	}
	
	private static function showErrors($errors)
	{
		if ($errors === 0) {
			return;
		}
		
		if ($errors & self::INVALID_DIGIT) {
			trigger_error("O conjunto contém algarismos inválidos.", E_USER_NOTICE);
		}
		
		if ($errors & self::INVALID_PATTERN1) {
			trigger_error("Apenas os algarismos 'I', 'X' e 'C' podem ser utilizados para somar ou subtrair.", E_USER_NOTICE);
		}
		
		if ($errors & self::INVALID_PATTERN2) {
			trigger_error("Só é permitido um numeral menor para subtrair um numeral maior.", E_USER_NOTICE);
		}
		
		if ($errors & self::INVALID_PATTERN3) {
			trigger_error("Apenas os algarismos 'I', 'X', 'C' e 'M' podem ser repetidos consecutivamente.", E_USER_NOTICE);
		}
		
		if ($errors & self::INVALID_PATTERN4) {
			trigger_error("Um algarismo pode ser repetido até três vezes consecutivas.", E_USER_NOTICE);
		}
		
		if ($errors > 0) {
			throw new Exception("O número romano informado é inválido.", $errors);
		}
	}
	
	private static function parseNumber(int $number)
	{
		$parsedNum = array(
			intval($number % 10000),
			intval($number % 1000),
			intval($number % 100),
			intval($number % 10)
		);
		
		/* Arredondar valores */
		for ($index = 0; $index < count($parsedNum) - 1; $index++) {
			$parsedNum[$index] -= $parsedNum[$index + 1];
		}
		
		return $parsedNum;
	}
	
	// Encontra o primeiro algarismo de menor valor que pode se repetir.
	private static function findClosestRepetable($roman)
	{
		$romanKeys = array_keys(self::romValues);
		$romanIndex = array_search($roman, $romanKeys);
		
		for ($index = $romanIndex - 1; $index >= 0; $index--)
		{
			if (in_array($romanKeys[$index], self::allowRepeat))
				return $romanKeys[$index];
		}
		
		return false;
	}
	
	public static function formatDecimalToRoman($chunkNumber)
	{
		$result = '';
		
		while ($chunkNumber > 0)
		{
			
			/*
			 * 987
			 * CMLXXXVII
			 **/
			foreach (self::romValues as $roman => $value)
			{
				$digitCount = intval($chunkNumber / $value);
				echo "$chunkNumber\n";
				
				if (self::validRomanDigitRange($roman, $digitCount, $chunkNumber))
				{
					echo "$roman\n";
					$chunkNumber -= ($value * $digitCount);
					
					for ($count = 0; $count < $digitCount; $count++) {
						$result .= $roman;
					}
				}
				else if ($digitCount <= 0)
				{
					foreach (self::specialValues as $subtractRom)
					{
						$subtractValue = self::romValues[$subtractRom];
						
						if ($subtractRom  !== $roman && $chunkNumber / ($value - $subtractValue) === 1)
						{
							$result .= $subtractRom . $roman;
							
							$chunkNumber = 0;
						}
					}
				}
			}
		}
		
		return $result;
	}
	
	private static function validRomanDigitRange(string $roman, int $digitCount, int $number)
	{
		$canRepeat = in_array($roman, self::allowRepeat);
		$closestRepRom = self::findClosestRepetable($roman);
		$closestRep = 0;
		
		if ($closestRepRom)
			$closestRep = self::romValues[$closestRepRom];
		
		if ($digitCount < 1)
			return false;
		
		if ($digitCount > 3)
			return false;
		
		//&& ($canRepeat || (!$canRepeat && ($number % self::romValues[$roman] < $closestRep * 3) && $digitCount === 1)))
		
		if (!$canRepeat && $digitCount > 1)
			return false;
		
		if ($number % self::romValues[$roman] > $closestRep * 3 && $digitCount === 1)
			return false;
		
		return true;
	}
}