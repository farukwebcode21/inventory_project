<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller {
    public function user_registration( Request $request ) {
        try {
            User::create( [
                'firstName' => $request->input( 'firstName' ),
                'lastName'  => $request->input( 'lastName' ),
                'email'     => $request->input( 'email' ),
                'mobile'    => $request->input( 'mobile' ),
                'password'  => $request->input( 'password' ),
            ] );
            return response()->json( [
                'status'  => 'success',
                'message' => 'User Registration successful',
            ], status: 200 );
        } catch ( Exception $e ) {
            return response()->json( [
                'status'  => 'Failed',
                'message' => 'User Registration Failed',
            ] );
        }
    }

    public function user_login( Request $request ) {
        $count = User::where( 'email', '=', $request->input( 'email' ) )->where( 'password', '=', $request->input( 'password' ) )->count();
        if ( $count == 1 ) {
            $token = JWTToken::CreateToken( $request->input( 'email' ) );
            return response()->json( [
                'status'  => 'success',
                'message' => 'User Login successful',
                'token'   => $token,
            ] );
        } else {
            return response()->json( [
                'status'  => 'failed',
                'message' => 'unauthorized',
            ] );
        }
    }

    public function send_otpcode( Request $request ) {
        $email = $request->input( 'email' );
        $otp = rand( 1000, 9999 );
        $count = User::where( 'email', '=', $email )->count();
        if ( $count == 1 ) {
            // OTP Email Address
            Mail::to( $email )->send( new OTPMail( $otp ) );
            // OTP Code Table Insert
            User::where( 'email', '=', $email )->update( ['otp' => $otp] );
            return response()->json( [
                'status'  => 'success',
                'message' => 'OTP Send successful',
            ], status: 200 );

        } else {
            return response()->json( [
                'status'  => 'Failed',
                'message' => 'User Registration Failed',
            ] );
        }
    }

    public function verify_otp( Request $request ) {
        $email = $request->input( 'email' );
        $otp = $request->input( 'otp' );
        $count = User::where( 'email', '=', $email )->where( 'otp', '=', $otp )->count();
        if ( $count == 1 ) {
            User::where( 'email', '=', $email )->update( ['otp' => '0'] );
            $token = JWTToken::CreateTokenForSetPassword( $request->input( 'email' ) );
            return response()->json( [
                'status'  => 'success',
                'message' => 'OTP Verification successful',
                'token'   => $token,
            ] );

        } else {
            return response()->json( [
                'status'  => 'Failed',
                'message' => 'User OTP Verification Failed',
            ] );
        }
    }
}
