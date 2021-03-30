<?php

namespace App\Services\Auth;

use App\Services\Contracts\Auth\JWTAuthServiceInterface;

trait HasToken
{

	public function generateToken(): string
	{
		return app()->get(JWTAuthServiceInterface::class)
			->encode(
				$this,
				config('auth.guards.jwt.algo')
			);
	}
}