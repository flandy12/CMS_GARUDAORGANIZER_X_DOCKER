<?php

namespace App\Library\Enums;

class Status {
	const INACTIVE = FALSE;
	const ACTIVE = TRUE;

	protected static function getList(): array
	{
		return [
			Status::INACTIVE,
			Status::ACTIVE
		];
	}

	public static function getArray(): array
	{
		$results = [];

		foreach (self::getList() as $key => $value) {
			$results[$value] = self::getString($value);
		}

		return $results;
	}

	public static function getString($value): string
	{
		switch ($value) {
			case FALSE:
				return 'Inactive';
			case TRUE:
				return 'Active';
		}
	}
}