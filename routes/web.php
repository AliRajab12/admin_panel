<?php

use App\Http\Middleware\AdminCheck;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/test','TestController@test');

// Route::any('{slug}',function(){
//     return view('welcome');
// });
// Tags Routes
Route::prefix('app')->middleware([AdminCheck::class])->group(function(){
    Route::post('/create_tag','AdminController@addTag');
    Route::post('/edit_tag','AdminController@editTag');
    Route::post('/delete_tag','AdminController@deleteTag');
    Route::get('/get_tags','AdminController@getTag');
    
    // Category Routes
    Route::post('/upload','AdminController@upload');
    Route::post('/delete_image','AdminController@deleteImage');
    Route::post('/create_category','AdminController@addCategory');
    Route::get('/get_category','AdminController@getCategory');
    Route::post('/edit_category','AdminController@editCategory');
    Route::post('/delete_category','AdminController@deleteCategory');
    
    // Admin user routes 
        // Users Routes
    Route::post('/create_user','AdminController@createUser');
    Route::post('/edit_user','AdminController@editUser');
    Route::get('/get_users','AdminController@getUsers');
    Route::post('/delete_user','AdminController@deleteUser');
    Route::post('/admin_login','AdminController@adminLogin');
        // Roles Routes
    Route::post('/create_role','AdminController@createRole');
    Route::get('/get_roles','AdminController@getRoles');
    Route::post('/edit_role','AdminController@editRole');
    Route::post('/assign_roles','AdminController@assignRole');
    Route::post('/delete_role','AdminController@deleteRole');

    // Blog routes
    Route::post('/create-blog','AdminController@createBlog');
    Route::get('blogsdata','AdminController@blogData');
    Route::post('delete_blog','AdminController@deleteBlog');
    Route::get('blog_single/{id}','AdminController@singleBlogItem');
    Route::post('update_blog/{id}','AdminController@updateBlog');
    Route::get('blogCount','AdminController@blogCount');
    
});

Route::post('createBlog','AdminController@uploadEditorImage');
Route::get('slug','AdminController@slug');
Route::get('blogdata','AdminController@blogData');


Route::get('/','AdminController@index');
Route::get('/logout','AdminController@logout');
Route::any('{slug}','AdminController@index')->where('slug', '([A-z\d-\/_.]+)?');