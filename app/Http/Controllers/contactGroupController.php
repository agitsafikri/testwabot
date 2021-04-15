<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\group;
use App\models\group_contact;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class contactGroupController extends Controller
{
	public function __construct() {
        $this->middleware('auth:api');
    }
    public function createGroup(Request $request)
    {
    	$group = new group;
    	$group->group_name = $request->group_name;
    	$group->save();
    	$id = $group->id;

    	return $this->createGroupContact($request, $id);
    }
    public function createGroupContact(Request $request , $id)
    {
    	$currentUser = Auth::user();
    	$groupContact = new group_contact;
    	$groupContact->id_user = $currentUser->id;
    	$groupContact->id_group = $id;
    	$groupContact->id_rule = $request->id_rule;
    	$groupContact->save();

    	
        if (! empty($groupContact->id)) {
            return response()->json([
                'status' => 200,
                'message' => 'Group Contact successfully inserted',
                'data' => $groupContact,
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Failed',
                'data' => $groupContact,
            ]);
        }
    }
}
