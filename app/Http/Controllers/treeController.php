<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\node;
use App\models\rule;

class treeController extends Controller
{
    public function createNode($response)
    {
    	$node = new node;
    	$node->response = $response;
    	$node->save();

    	//return "node berhasil di buat";
    }

    public function createRule(Request $request)
    {
    	$initialNode = $request->arr[0][0]; 
        foreach ($request->arr as $key) {
            print_r($key);
            foreach ($key as $k) {
                if(is_null($k) != 1){
                    $this->createNode($k);
                }
            }
        }

        $rule = new rule;

    	return $request->arr;
    }
}
