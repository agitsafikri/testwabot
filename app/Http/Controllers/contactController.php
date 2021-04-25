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
    }

    public function getContact(Request $request)
    {
        $currentUser = Auth::user();
       
        if((contact::where('id_user', $currentUser->id)->count()) > 0){
            $data = contact::where('id_user', $currentUser->id)->get();
            return response()->json([
                'status' => 200,
                'message' => 'Success',
                'data' => $data,
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Contact not found',
                
            ]);    
        }
        
    }
    public function updateContact(Request $request)
    {
        $currentUser = Auth::user();
        if ((contact::where('id_user', $currentUser->id)->count()) > 0){
            if((contact::where('id', $request->id)->count()) > 0){
                $contact = contact::find($request->id);
                $contact->contact_name = is_null($request->contact_name) ? $currentUser->contact_name : $request->contact_name;
                $contact->contact_number = is_null($request->contact_number) ? $currentUser->contact_number : $request->contact_number;
                $contact->save();

                return response()->json([
                    "status" => 200,
                    "message" => "Contact updated successfully",
                    "data" => $contact
                ]);
            }else{
                return response()->json([
                    "status" => 404,
                    "message" => "Contact not found",
                ]);
            }
            
        }else{
            return response()->json([
                "status" => 404,
                "message" => "User not found"
            ]);
        }
    }

    public function deleteContact(Request $request)
    {

        $currentUser = Auth::user();
        $count = contact::where('id_user', $currentUser->id)->count();
        if($count > 0) {
            $countID = contact::where('id', $request->id)->count();
            if($countID>0){
                $contact = contact::find($request->id);
                $contact->delete();                
            
                return response()->json([
                    "status" => 200,
                    "message" => "records deleted",
                    "data" => $contact,
                ]);
            }else{
                 return response()->json([
                    "status" => 404,
                    "message" => "contact not found",
                ]);
            }
        }else {
            return response()->json([
                "status" => 404,
                "message" => "user not found",
            ]);
      }
    }
}
