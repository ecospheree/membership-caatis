<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VerificationCodeController extends Controller
{
    public function generateCode()
    {
        try {
           
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            
            DB::table('verification_codes')->insert([
                'code' => $code,
                'date' => Carbon::now()->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            
            return response()->json([
                'status' => true,
                'message' => 'OTP generated successfully',
                'data' => [
                    'code' => $code,
                    'date' => Carbon::now()->toDateString()
                ]
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to generate OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getCode()
    {
        try {
            
            $lastCode = DB::table('verification_codes')
                ->latest('created_at')
                ->first();

            if (!$lastCode) {
                return response()->json([
                    'status' => false,
                    'message' => 'No verification code found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'code' => $lastCode->code,
                    'date' => $lastCode->date,
                    'created_at' => $lastCode->created_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to get verification code',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}