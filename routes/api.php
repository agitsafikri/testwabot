<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\treeController;
use App\Http\Controllers\contactGroupController;
use App\Http\Controllers\contactController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user', [AuthController::class, 'userProfile']);   
	 Route::put('/user', [AuthController::class, 'updateProfile']);
	 Route::delete('/user', [AuthController::class, 'deleteProfile']);
});

Route::group([
    'middleware' => 'api'
], function ($router)
{
  Route::post('/contact', [contactController::class, 'createContact']);
  Route::get('/contact', [contactController::class, 'getContact']);
  Route::put('/contact', [contactController::class, 'updateContact']);
  Route::delete('/contact', [contactController::class, 'deleteContact']); 
  Route::post('/contactGroup', [contactGroupController::class, 'createGroup']); 
  Route::get('/contactGroup', [contactGroupController::class, 'getContactGroup']);
  Route::delete('/contactGroup', [contactGroupController::class, 'deleteGroupContact']); 
  Route::put('/contactGroup', [contactGroupController::class, 'updateGroupContact']);
  
});

Route::post('/node', [treeController::class,'createNode']);