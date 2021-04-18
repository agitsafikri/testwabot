<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\group;
use App\models\group_contact;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class contactGroupController extends Controller
{
	
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

    public function getContactGroup(Request $request)
    {
        $currentUser = Auth::user();
        $data = group_contact::where('id_user', $currentUser->id)->get();
        foreach ($data as $d) {
            print_r($d->id_group);
            $groupName = group::where('id', $d->id_group)->get('group_name');
            $new[] = ([
             'id' => $d->id,   
             'id_user' => $d->id_user,
             'id_rule' => $d->id_rule,
             'id_group' => $d->id_group,
             'group_name' => $groupName,
             ]);

        }
        return response()->json([
                'status' => 200,
                'message' => 'Success',
                'data' => $new,
            ]);
    }
    public function deleteGroupContact(Request $request)
    {

        $currentUser = Auth::user();
        if((group_contact::where('id_user', $currentUser->id)->count()) > 0) {
            if((group_contact::where('id_group', $request->id)->count())>0){
                $group = group::find($request->id);
                $group->delete();                
                
                // $group_contact = groupContact::find($group->id);
                // $group_contact->delete();
                return response()->json([
                    "status" => 200,
                    "message" => "records deleted",
                    "data" => $group,
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

    public function updateGroupContact(Request $request)
    {
        $currentUser = Auth::user();
        if ((group_contact::where('id_user', $currentUser->id)->count()) > 0){
            if((group_contact::where('id', $request->id)->count()) > 0){
                $group = group::find($request->id);
                $group->group_name = is_null($request->group_name) ? $currentUser->group_name : $request->group_name;
                $group->save();

                return response()->json([
                    "status" => 200,
                    "message" => "Contact updated successfully",
                    "data" => $group
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
}
