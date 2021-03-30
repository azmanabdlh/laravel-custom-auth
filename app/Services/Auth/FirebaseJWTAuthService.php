<?php

namespace App\Services\Auth;

use App\Services\Contracts\Auth\JWTAuthServiceInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\DomainException;

class FirebaseJWTAuthService implements JWTAuthServiceInterface
{

    /**
    * The name of the secret key item from the JWT token.
    */
    const JWT_SECRET_KEY = 'laravel-firebase-jwt';

    /**
    * The key item from the JWT expire token.
    */
    const JWT_EXPIRE = 30; // minutes

    /**
    * The name of the algorithm from the JWT token.
    */
    const JWT_ALGO = 'HS256';


    /**
    * The auth config items.
    * @var array
    */
    private $config;


    public function __construct($config)
    {
        $this->config = $config;
    }


    private function toSerialize($token)
    {
        $expire = time() + 60 * ($this->config['expired_at'] ?? self::JWT_EXPIRE);

        $payload = [
            'exp' => $expire,
            'humanOfExp' => date('d-m-Y H:i:s', $expire),
            'iat' => time(),
            'humanOfIat' => date('d-m-Y H:i:s', time()),
            'iss' => env('APP_URL'),
            'aud' => [env('APP_URL')],
            'profile' => $token,
        ];

        return serialize($payload);
    }

    public function encode($data, $alg = '')
    {
        $payload = $this->toSerialize($data);

        $token = JWT::encode(
            $payload,
            $this->config['secret_key'] ?? self::JWT_SECRET_KEY,
            $alg ?? self::JWT_ALGO
        );

        return $token;
    }


    public function decode($token, $algs = [])
    {
        try {
            $payload = JWT::decode(
                $token,
                $this->config['secret_key'] ?? self::JWT_SECRET_KEY,
                $algs ?? [self::JWT_ALGO]
            );

            return unserialize($payload);
        } catch (\DomainException $err) {
            return null;
        }
    }


    public function verify($token, $algs = [])
    {
        return !is_null($this->decode($token, $algs));
    }
}