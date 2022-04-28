<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\User;
use \App\Models\postsModel;
use Illuminate\Support\Facades\Notification;
class AdminController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $posts = postsModel::all();  
        return view('layouts.AdminHome' ,[
            'posts' => $posts
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Create()
        {
                return view('auth.admin.AdminCreate');
        }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

            $request->validate([
                'descrption' => 'required|min:10|max:260',
                
            ]);
           
            $post = new postsModel();
                $post->descrption = $request->descrption;
                $post->user_id = auth()->id();
                $post->IsAdmin = auth()->user()->Admin;
                        $save = $post->save();
                           
                if($save){
                    return back()->with('success','The Post Are Created');
                }else{
                    return back()->with('fail','Somthing goes Wrong');
                }    

         
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_posts = postsModel::where('user_id' , '=' , $id)
        ->get(); 
        return view('auth.admin.showAdmin' )->with('user_posts', $user_posts);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('id','=',$id)->delete();
        $post_user= postsModel::where('user_id','=',$id)->delete() ;
        return back();
    }
    ///////////////////////////////
    ///////////////////////////
    ////////////////////////
    ////////////////////

    public function AdminUsers()
    {
        $users = User::all(); 
        return view('auth.admin.users',[
            'USERS' => $users
        ]);
    }
    ////////////posts////////////
    public function AdminPosts()
    {
        $posts = postsModel::all(); 
        $chick = count($posts);
        //dd($chick);
      

           return view('auth.admin.Adminposts',[
               'posts' => $posts
           ]);
        }
    public function AdminSearch(Request $request)
    {
            
        if($request->Name != null || $request->age != null || $request->lost_year != null){
            $posts = postsModel::Where('descrption', 'like', '%' . $request->Name . '%')
            ->where('age', 'like', '%' . $request->age . '%')
            ->where('lost_day', 'like', '%' . $request->lost_year . '%')
            ->get();

                ///////////////
                if(count($posts) == 0){
                    return back()->with('nothing', "NO SUCH ITEM");
                }else{

                    return view('auth.admin.posts',[
                        'posts' => $posts
                ])->with('true', 'pop');

                }
        
         }else{
           

            return back()->with('fail', "EMPTY");
        }
        }

        public function notification()
        {
            return view('auth.admin.notification');
        }


        public function markAsread($id){

            auth()->user()->notifications->where("id" , $id)->markAsRead();
            return back();
        }

}
