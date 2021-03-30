<?php

namespace App\Services\Contracts\Auth;


interface JWTAuthServiceInterface
{
  /**
   * Encode payload for get JWT token.
   *
   * @param any $payload
   * @param string $alg
   * @return string
   */
  public function encode($payload, $alg = '');

  /**
   * Decode JWT token for get payload.
   *
   * @param string $token
   * @param array $algs
   * @return any
   */
  public function decode($token, $algs = []);

  /**
   * Verify JWT token.
   *
   * @param string $token
   * @param array $algs
   * @return bool
   */
  public function verify($token, $algs = []);

}