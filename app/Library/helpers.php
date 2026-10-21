<?php

if(!function_exists("successResponse")) {
	function successResponse($results = [], $title = '', $message = '') {
		return response()->json([
			"success"			=> true,
			"results"			=> $results,
			"message"			=> $message,
			"title"				=> $title,
		], 200);
	}
}

if(!function_exists("errorResponse")) {
	function errorResponse($error_code = 500, $message = '', $title = '') {
		return response()->json([
			"success"			=> false,
			"message"			=> $message,
			"title"				=> $title,
		], $error_code);
	}
}

if(!function_exists('userRolePermissions')) {
	function userRolePermissions($roles) {
		$results = [];

		foreach ($roles as $role) {
			foreach ($role->permissions as $permission) {
				$results[$role->name][] = $permission->name;
			}
		}

		return $results;
	}
}