<?php

namespace yii2rails\extension\validator\helpers;

use Exception;
use Yii;

class IbanParser {
	
	const IIN_LENGTH = 34;
	
	private static function validateLength($value) {
		$value = strval($value);
		if (strlen($value) > self::IIN_LENGTH) {
			throw new Exception(Yii::t('yii', '{attribute} should contain at most {max, number} {max, plural, one{character} other{characters}}.', [
				'max' => self::IIN_LENGTH,
			]));
		}
	}
	
	private static function validate($value) {
		self::validateLength($value);
	}
	
	public static function parse($value) {
		self::validate($value);
		$part['country_code'] = substr($value, 0, 2);
		$part['check_sum'] = substr($value, 2, 2);
		$part['bank_bic'] = substr($value, 4, 4);
		$part['account'] = substr($value, 8);
		return $part;
	}
	
}

/*
Структура IBAN code

Утвержденная структура IBAN не может превышать 34 буквенно-цифровых символов (буквы в коде используются из латинского алфавита и только заглавные).
Структура IBAN кода (счета) включает в себя следующие значения:

1-2 символ – буквенный код страны, где находится банк получателя (в соответствии со стандартом ISO 3166-1 alpha-2)
3-4 символ - контрольное уникальное число IBAN, рассчитываемое по стандарту (ISO 7064)
5-8 символ - первые 4 символа BIC кода банка
9-34 символ – внутригосударственный/внутрибанковский номер счета клиента банка.
*/