<?php

use Illuminate\Http\Request;

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



Route::get('getStripeData', 'API\ApiController@getStripeData');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');
Route::post('forget-password', 'API\AuthController@resetPassword');

Route::group(['middleware' => ['auth:api', 'roles'], 'namespace' => 'API'], function() {
    
});
Route::group(['middleware' => 'auth:api'], function() {
    Route::post('change-password', 'API\AuthController@changePassword');
    Route::post('update', 'API\AuthController@Update');
    Route::post('get/profile', 'API\AuthController@getProfile');
    Route::post('notification/status', 'API\AuthController@updateNotifyStatus');
    Route::get('logout', 'API\AuthController@logout');
    
    Route::post('tournament/store', 'API\TournamentsController@createTournaments');
    Route::post('tournament/list', 'API\TournamentsController@tournamentList');
    Route::post('tournament/history', 'API\TournamentsController@tournamentHistory');
    Route::post('tournament/upcoming', 'API\TournamentsController@tournamentUpcoming');
    Route::post('tournament/score', 'API\TournamentsController@addScoreToTournament');
    Route::post('tournament/winner', 'API\TournamentsController@lastMatchWinner');
    Route::post('tournament', 'API\TournamentsController@getTournament');
    
    Route::post('tournament/report', 'API\TournamentsController@addTournamentFixtureReport');
    Route::post('tournament/report/result', 'API\TournamentsController@acceptRejectReportedFixtures');
    Route::post('report/list', 'API\TournamentsController@getReportedFixtures');
    Route::post('report/list/id', 'API\TournamentsController@getReportedFixtureById');
    
    Route::post('banner', 'API\TournamentsController@getBannerImages');
    
    Route::post('users', 'API\TournamentsController@findFriend');
    
    Route::post('friends/store', 'API\TournamentsController@addFriend');
    Route::post('friends', 'API\TournamentsController@myFriends');
    Route::post('friends/requests', 'API\TournamentsController@pendingRequests');
    Route::post('friends/accept', 'API\TournamentsController@acceptRejectRequests');
    Route::post('friends/remove', 'API\TournamentsController@removeFriend');
    
    Route::post('chat/store', 'API\MessageController@store');
    Route::post('chat/getItems', 'API\MessageController@getItems');
    Route::post('chat/getItemsByReceiverId', 'API\MessageController@getItemsByReceiverId');
    Route::post('chat/delete', 'API\MessageController@deleteChat');
    
    
    Route::post('connectWithStripe', 'API\ApiController@connectWithStripe');
    
    Route::post('game/twitch', 'API\TournamentsController@getVideosByTwitchId');
    Route::post('game/teams', 'API\TournamentsController@teamList');
    Route::post('game/clubs', 'API\TournamentsController@clubList');
    Route::post('game/players', 'API\TournamentsController@playerList');
    
    Route::post('notification/list', 'API\TournamentsController@notifications');
    Route::post('notification/count', 'API\TournamentsController@notificationCount');
    Route::post('notification/read', 'API\TournamentsController@notificationRead');
    Route::post('notification/delete', 'API\NotificationController@deleteNotifications');
});
Route::get('player-config/{column}', 'API\ConfigurationController@getConfigurationPlayer');
Route::get('service-config/{column}', 'API\ConfigurationController@getConfigurationService');
Route::post('testing-push', 'API\ConfigurationController@testingPush');
    
