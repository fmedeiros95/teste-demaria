<?php

use App\Models\Log;

if (!function_exists('add_log')) {
	function add_log($message)
	{
		return Log::create([
			'message' => $message
		]);
	}
}

if (!function_exists('display_money')) {
	function display_money($value)
	{
		return 'R$ ' . number_format($value, 2, ',', '.');
	}
}
