<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Blogcategory;
use App\Blogtag;
use App\Category;
use App\Role;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request){
        // return Auth::check();
        // first check if you are loggedin and admin user ...
        if(!Auth::check() && $request->path() != 'login'){
            return redirect('/login');
        }
        if(!Auth::check() && $request->path() == 'login'){
            return view('welcome');
        }
        // check if already logged in ... and check if he is admin user...
        $user = Auth::user();
        if($user->userType=='User'){
            return redirect('/login');
        }
        if($request->path() == 'login'){
            return redirect('/');
        }
        return $this->checkForPermission($user,$request);
        // return view('notfound');
        // return view('welcome');
        // return $request->path();
    }
    public function checkForPermission($user,$request){
            $permission = json_decode($user->role->permission);
            // echo $user;
            // echo $permission[0]->name;
            // echo $request->path();
            if(!$permission) return view('welcome');
            $hasPermission = false;
            foreach($permission as $p){
                if($p->name == $request->path()){
                    if($p->read){
                        $hasPermission = true;
                    }
                }
            }
            if($hasPermission) return view('welcome');
            return view('notfound');
    }
    public function logout(){
        Auth::logout();
        return redirect('/login');
    }

    public function addTag(Request $request){
        // Validate request
        $this->validate($request,[
            'tagName'=> 'required'
        ]);
        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }
    public function editTag(Request $request){
        // Validate request
        $this->validate($request,[
            'id' => 'required',
            'tagName'=> 'required'
        ]);
        return Tag::where('id',$request->id)->update([
            'tagName' => $request->tagName
        ]);
        // return response()->json([
        //     'tagName' => $request->tagName
        // ]);
    }
    public function deleteTag(Request $request){
        // Validate request
        $this->validate($request,[
            'id' => 'required',
        ]);
        return Tag::where('id',$request->id)->delete();
    }
    public function getTag(){
        // return Tag::all();
        return Tag::orderBy('id','desc')->get();
    }
    public function upload(Request $request){
        $this->validate($request,[
            'file' => 'required|mimes:jpeg,jpg,png'
        ]);
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'),$picName);
        return $picName;
    }
    // upload image from editor.js
    public function uploadEditorImage(Request $request){
        $this->validate($request,[
            'image' => 'required|mimes:jpeg,jpg,png'
        ]);
        $picName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads'),$picName);
        return response()->json([
            'success' => 1,
            'file' =>[
                'url' => "http://127.0.0.1:8000/uploads/$picName"
            ]
        ]);
        return $picName;
    }
    public function deleteImage(Request $request){
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName,false); //, false
        return 'done';
    }
    public function deleteFileFromServer($fileName,$hasFullPath = false){ //
        if(!$hasFullPath){
            $filePath = public_path().$fileName;
        }
            if(file_exists($filePath)){
                @unlink($filePath);
            }
            return;
    }
    public function addCategory(Request $request){
        // validate request
        $this->validate($request,[
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);
        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }
    public function getCategory(){
        return Category::orderBy('id','desc')->get();
    }
    public function editCategory(Request $request){
        // validate request
        $this->validate($request,[
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);
        return Category::where('id',$request->id)->update([
            'categoryName'=> $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }
    public function deleteCategory(Request $request){
        
        // delete the original file from the server
        $this->deleteFileFromServer($request->iconImage);
        // validate request
        $this->validate($request,[
            'id' => 'required'
        ]);
        return Category::where('id',$request->id)->delete();     
    }
    public function createUser(Request $request){
        // validate request
        $this->validate($request,[
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'role_id' => 'required'
        ]);
        $password = bcrypt($request->password);
        // dd($request->role_id);
        // dd($request);
        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role_id,
        ]);
        // dd($user);
        return $user;
    }
    public function getUsers(){
        // return User::where('userType','!=','User')->get();
        return User::get();
    }
    public function editUser(Request $request){
        // validate request
        $this->validate($request,[
            'fullName' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id",
            'password' => 'min:6',
            'userType' => 'required'
        ]);
        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'userType' => $request->userType
        ];
        if($request->password){
            $password = bcrypt($request->fullName);
            $data['password'] = $password;
        }
        $user = User::where('id',$request->id)->update($data);
        return $user;
    }
    public function deleteUser(Request $request){
        $this->validate($request,[
            'id' => 'required',
        ]);
        return User::where('id',$request->id)->delete();
    }
    public function adminLogin(Request $request){
        // validate request
        $this->validate($request,[
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6'
        ]);
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user = Auth::user();
            // dd($user);
            // if($user->userType == 'User'){
            if($user->role->isAdmin == 0){
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect login details'
                ],401);
            } 
            return response()->json([
                'msg' => 'You are logged in',
                'user' => $user
            ]);
        }else{
            return response()->json([
                'msg' => 'Incorrect login details ' 
            ],401);
        }
    }
    public function createRole(Request $request){
        // validate request
        $this->validate($request,[
            'roleName'=> 'required'
        ]); 
        return $role = Role::create([
            'roleName' => $request->roleName
        ]);
    }
    public function getRoles(){
        return Role::all();
    }
    public function editRole(Request $request){
        // Validate request
        $this->validate($request,[
            'id' => 'required',
            'roleName'=> 'required'
        ]);
        return Role::where('id',$request->id)->update([
            'roleName' => $request->roleName
        ]);
    }
    public function deleteRole(Request $request){
        $this->validate($request,[
            'id' => 'required',
        ]);
        return Role::where('id',$request->id)->delete();
    }
    public function assignRole(Request $request){
        $this->validate($request,[
            'permission' => 'required',
            'id' => 'required'
        ]);
        return Role::where('id',$request->id)->update([
            'permission'=> $request->permission
        ]);
    }
    public function slug(){
        $title = 'This nice';
        return Blog::create([
            'title' => $title,
            'post' =>'some post',
            'post_excerpt' => 'aed',
            'user_id' => 1,
            'metaDescription' => 'aed', 
        ]);
        return $title;
    }
    public function createBlog(Request $request){

        $this->validate($request,[
            'title' => 'required',
            'post' => 'required',
            'post_excerpt' => 'required',
            'metaDescription' => 'required',
            'jsonData' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required',
        ]);
        $categories = $request->category_id;
        $tags = $request->tag_id;
        $blogCategories = [];
        $blogTags = [];
        DB::beginTransaction();
        try{
            // \Log::info($categories);
        $blog = Blog::create([
            'title' => $request->title,
            'slug' => $request->title,
            'post' => $request->post,
            'post_excerpt' => $request->post_excerpt,
            'user_id' => Auth::user()->id,
            'metaDescription' => $request->metaDescription,
            'jsonData' => $request->jsonData 
        ]);
        // insert blog categories
        foreach($categories as $c){
            array_push($blogCategories,['category_id' => $c,'blog_id'=>$blog->id]);
        }
        Blogcategory::insert($blogCategories);
        // insert blog tags
        foreach($tags as $t){
            array_push($blogTags,['tag_id'=>$t,'blog_id'=>$blog->id]);
        }
        Blogtag::insert($blogTags);
        DB::commit();
        return 'done';
        }catch(\Throwable $th){
            DB::rollBack();
            return 'not done';
        }
    }
    public function blogdata(Request $request){
        // return Blog::with(['tag','cat'])->orderBy('id','desc')->get();
        return Blog::with(['tag','cat'])->orderBy('id','desc')->paginate($request->total);
        
    }
    public function blogCount(){
        $blogCount = Blog::count();
        $userCount = User::count();
        $tagCount = Tag::count();
        $catCount = Category::count();
        return [$blogCount,$userCount,$tagCount,$catCount];
    }
    public function deleteBlog(Request $request){
        return Blog::where('id',$request->id)->delete();
    }
    public function singleBlogItem(Request $request, $id){
        return Blog::with(['tag','cat'])->where('id',$id)->first();
    }
    public function updateBlog(Request $request,$id){

        $this->validate($request,[
            'title' => 'required',
            'post' => 'required',
            'post_excerpt' => 'required',
            'metaDescription' => 'required',
            'jsonData' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required',
        ]);
        $categories = $request->category_id;
        $tags = $request->tag_id;
        $blogCategories = [];
        $blogTags = [];
        DB::beginTransaction();
        try{
            // \Log::info($categories);
        $blog = Blog::where('id',$id)->update([
            'title' => $request->title,
            'slug' => $request->title,
            'post' => $request->post,
            'post_excerpt' => $request->post_excerpt,
            'user_id' => Auth::user()->id,
            'metaDescription' => $request->metaDescription,
            'jsonData' => $request->jsonData 
        ]);
        // insert blog categories
        foreach($categories as $c){
            array_push($blogCategories,['category_id' => $c,'blog_id'=>$id]);
        }
        // delete all previous categories
        Blogcategory::where('blog_id',$id)->delete();
        Blogcategory::insert($blogCategories);
        // insert blog tags
        foreach($tags as $t){
            array_push($blogTags,['tag_id'=>$t,'blog_id'=>$id]);
        }
        Blogtag::where('blog_id',$id)->delete();
        Blogtag::insert($blogTags);
        DB::commit();
        return 'done';
        }catch(\Throwable $th){
            // return $th;
            DB::rollBack();
            return 'not done';
        }
    }
}
