<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\CurlNode;

class BlockchainController extends Controller
{
    public function register(Request $request)
    {
        try {
            if($request->email == auth()->user()->email) {
                $response = Curlnode::post('/register', [
                    'name' => $request->name,
                    'email' => $request->email
                ]);
                if(!session('connect_error')) {
                    auth()->user()->address = $response->address;
                    auth()->user()->save();
                    session(['success' => 'Registration on the blockchain successful. Copy and keep this private key safely: ' . $response->key]);
                } else {
                    throw new Exception("Error: " . session('connect_error_message'));
                }
            } else {
                throw new Exception("Error: Invalid email address!");
            }
        }
        catch(Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        return redirect()->back();
    }
}
