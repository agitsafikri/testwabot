<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\models\contact;

class contactController extends Controller
{
	public function __construct() {
        $this->middleware('auth:api');
    }

	
    public function createContact(Request $request)
    {
    	$currentUser = Auth::user();
    	$contact = new contact;
    	$contact->id_user = $currentUser->id;
    	$contact->contact_number = $request->contact_number;
    	$contact->contact_name = $request->contact_name;
    	$contact->save();

        if (! empty($contact->id)) {
            return response()->json([
                'status' => 200,
                'message' => 'Contact successfully inserted',
                'data' => $contact,
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Failed',
                'data' => $contact,
            ]);
        }

    	return "OK!";
    }
}
