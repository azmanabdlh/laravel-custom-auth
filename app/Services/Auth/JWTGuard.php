<?php

namespace App\Services\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use App\Services\Contracts\Auth\JWTAuthServiceInterface;
use Illuminate\Auth\AuthenticationException;

class JWTGuard implements Guard
{

	use GuardHelpers;


	/**
	 * @var Illuminate\Http\Request
	 */
	private $request;


	/**
	 * @var App\Services\Contracts\Auth\JWTAuthServiceInterface;
	 */
	private $jwt;


	/**
	 * The auth config items.
	 * @var array
	 */
	private $config;



	public function __construct(JWTAuthServiceInterface $jwt, array $config)
	{
		$this->jwt = $jwt;
		$this->config = $config;
	}


	/**
	* Get the currently authenticated user.
	*
	* @return \Illuminate\Contracts\Auth\Authenticatable|null
	*/
	public function user()
	{
		if ($this->hasUser()) {
			return $this->user;
		}

		$user = null;
		$token = request()->bearerToken();

		if (!is_null($token) && $this->validateToken($token)) {
			$payload = $this->getTokenForPayload($token);
			$user = $payload;
		}

		return $this->user = $user;
	}


	/**
	 * The validation of JWT token.
	 *
	 * @param string $token
	 * @return bool
	 */
	private function validateToken($token)
	{
		$payload = $this->getTokenForPayload($token);

		if (is_null($payload) ) {
			throw new AuthenticationException('Your token invalid');
		}

		if ($payload['exp'] < time()) {
			throw new AuthenticationException('Your token expired');
		}

		return true;
	}


	/**
	 * Validate a user's credentials.
	 *
	 * @param  array  $credentials
	 * @return bool
	 */
	public function validate(array $credentials = [])
	{
		if (empty($credentials) && !array_key_exists('token', $credentials) ) {
			return false;
		}

		try {
			$this->validateToken($credentials['token']);

			return true;
		}catch (AuthenticationException $err) {
			return false;
		}
	}



	private function getTokenForPayload(string $token)
	{
		$payload = $this->jwt->decode($token, [ $this->config['algo'] ]);
		return $payload;
	}


}