<?php
namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken {
    public static function CreateToken( $userEmail ): string {
        $key = env( 'SESSION_DRIVER' );
        $payload = [
            'iss'       => 'laravel-token',
            'iat'       => time(),
            'exp'       => time() + 60 * 60,
            'userEmail' => $userEmail,
        ];
        return JWT::encode( $payload, $key, 'HS256' );
    }
    public static function CreateTokenForSetPassword( $userEmail ): string {
        $key = env( 'SESSION_DRIVER' );
        $payload = [
            'iss'       => 'laravel-token',
            'iat'       => time(),
            'exp'       => time() + 60 * 10,
            'userEmail' => $userEmail,
        ];
        return JWT::encode( $payload, $key, 'HS256' );
    }

    public static function VerifyToken( $token ): string {
        try {
            $key = env( 'SESSION_DRIVER' );
            $decode = JWT::decode( $token, new Key( $key, 'HS256' ) );
            return $decode->userEmail;
        } catch ( Exception $e ) {
            return 'unauthorized';
        }
    }

}