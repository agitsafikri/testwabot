<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\node;
class treeController extends Controller
{
    public function createNode(request $request)
    {
    	$node = new node;
    	$node->response = $request->response;
    	$node->save();

    	return "node berhasil di buat";
    }
}
