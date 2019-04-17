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


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth'
], function ($router) {

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('verify/{verification_code}', 'AuthController@verifyUser');
    Route::post('recover', 'AuthController@recover');
    Route::post('refresh', 'AuthController@refresh');
});

Route::group([
    'middleware' => ['api'],
], function ($router) {

    //Home
    Route::get('/', 'HomeController@index');

    //Games
    Route::get('games', 'GameController@index');
    Route::get('games/{id}', 'GameController@show');
    Route::get('games/{id}/reviews', 'ReviewController@gameIndex');
    Route::get('games/{id}/recommendations', 'RecommendationController@gameIndex');

    //Reviews
    Route::get('reviews', 'ReviewController@index');
    Route::get('reviews/{id}', 'ReviewController@show');

    //Recommendations
    Route::get('recommendations', 'RecommendationController@index');
    Route::get('recommendations/{id}', 'RecommendationController@show');

    //Users
    Route::get('users', 'UserController@index');
    Route::get('users/{id}', 'UserController@show');
    Route::get('users/{id}/reviews', 'ReviewController@userIndex');
    Route::get('users/{id}/recommendations', 'RecommendationController@userIndex');
    Route::get('users/{id}/library', 'LibraryController@userIndex');
    Route::get('users/{id}/following', 'FollowController@showFollowing');
    Route::get('users/{id}/followers', 'FollowController@showFollowers');

    //Library
    Route::get('library/games/{gid}/', 'LibraryController@gameEntries');

    //Forums
    Route::get('forums/tags', 'Forums\TagController@index' );

    //Misc
    Route::get('allgames', function() {return App\Game::all();} );
    Route::get('developers', function() {return App\Developer::select('id', 'name')->get();} );
    Route::get('publishers', function() {return App\Publisher::select('id', 'name')->get();} );
    Route::get('genres', function() {return App\Genre::select('id', 'name')->get();} );
    Route::get('platforms', function() {return App\Platform::select('id', 'name')->get();} );
    Route::get('regions', function() {return App\Region::select('id', 'name')->get();} );
    Route::get('datetypes', function() {return App\DateType::select('id', 'format')->get();} );
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::group([
    'middleware' => ['auth:api'],
    'prefix' => 'auth'
], function ($router) {

    Route::post('logout', 'AuthController@logout');
    Route::post('me', 'AuthController@me');
    Route::post('checkAdmin', 'AuthController@checkAdmin');
});

Route::group([
    'middleware' => ['auth:api'],
], function ($router) {

    //Favourites
    Route::get('favourites/games/{gid}/users/{uid}', 'FavouriteController@checkUser');
    Route::post('favourites', 'FavouriteController@store');
    Route::patch('favourites/{id}', 'FavouriteController@update');
    Route::delete('favourites/{id}', 'FavouriteController@destroy');

    //Wishlist
    Route::get('wishlist/games/{gid}/users/{uid}', 'WishlistController@checkUser');
    Route::post('wishlist', 'WishlistController@store');
    Route::patch('wishlist/{id}', 'WishlistController@update');
    Route::delete('wishlist/{id}', 'WishlistController@destroy');
    
    //Libraries
    Route::get('libraries/games/{gid}/users/{uid}', 'LibraryController@userEntriesGame');
    Route::post('libraries', 'LibraryController@store');
    Route::patch('libraries/{id}', 'LibraryController@update');
    Route::delete('libraries/{id}', 'LibraryController@destroy');
    Route::get('playstatuses', 'PlayStatusController@index');

    //Reviews
    Route::post('reviews', 'ReviewController@store');
    Route::patch('reviews/{id}', 'ReviewController@update');
    Route::delete('reviews/{id}', 'ReviewController@destroy');

    //Recommendations
    Route::post('recommendations', 'RecommendationController@store');
    Route::patch('recommendations/{id}', 'RecommendationController@update');
    Route::delete('recommendations/{id}', 'RecommendationController@destroy');

    //Review Likes/Dislikes
    Route::get('reviews/{id}/user/', 'LikeController@checkUserReview');
    Route::post('reviews/like/', 'LikeController@likeReview');
    Route::post('reviews/dislike/', 'LikeController@dislikeReview');

    //Followings
    Route::get('users/{id}/follow', 'FollowController@checkUser');
    Route::post('users/{id}/follow', 'FollowController@store');
    Route::delete('users/{id}/unfollow', 'FollowController@destroy');

    //User Settings
    Route::patch('users/{id}', 'UserController@update');

    //Forums
    Route::post('forums/discussions', 'Forums\DiscussionController@store');
    Route::patch('forums/discussions/{id}', 'Forums\DiscussionController@update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (require authenticated user to have admin role)
|--------------------------------------------------------------------------
*/
Route::group([
    'middleware' => ['auth:api', 'role:admin'],
    'prefix' => 'admin'
], function ($router) {

    Route::get('developers', 'DeveloperController@index');
    Route::post('developers', 'DeveloperController@store');
    Route::put('developers/{id}/edit', 'DeveloperController@update');
    Route::delete('developers/{id}/delete', 'DeveloperController@destroy');

    Route::get('publishers', 'PublisherController@index');
    Route::post('publishers', 'PublisherController@store');
    Route::put('publishers/{id}/edit', 'PublisherController@update');
    Route::delete('publishers/{id}/delete', 'PublisherController@destroy');       

    Route::get('genres', 'GenreController@index');
    Route::post('genres', 'GenreController@store'); 
    Route::put('genres/{id}/edit', 'GenreController@update');
    Route::delete('genres/{id}/delete', 'GenreController@destroy');    

    Route::get('platforms', 'PlatformController@index');
    Route::post('platforms', 'PlatformController@store');
    Route::put('platforms/{id}/edit', 'PlatformController@update');
    Route::delete('platforms/{id}/delete', 'PlatformController@destroy');   

    Route::get('games', 'GameController@adminIndex');
    Route::post('games', 'GameController@store');
    Route::put('games/{id}/edit', 'GameController@update');
    Route::put('games/{id}/releases/edit', 'ReleaseController@updateGame'); //Update Releases for a Game
    Route::delete('games/{id}/delete', 'GameController@destroy');    

    Route::put('releases/{id}/edit', 'ReleaseController@update'); //Edit one Release entry
    Route::delete('releases/{id}/delete', 'ReleaseController@destroy');

    //Forums
    Route::post('forums/tags', 'Forums\TagController@store');
    Route::put('forums/tags/{id}/edit', 'Forums\TagController@update');
    Route::delete('forums/tags/{id}/delete', 'Forums\TagController@destroy');   
});

// // Route to create a new role
// Route::post('role', 'RoleController@createRole');
// // Route to create a new permission
// Route::post('permission', 'RoleController@createPermission');
// // Route to assign role to user
// Route::post('assign-role', 'RoleController@assignRole');
// // Route to attache permission to a role
// Route::post('attach-permission', 'RoleController@attachPermission');