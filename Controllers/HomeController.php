<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\postsModel;
use \App\Models\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('posts.home');

    }
        
    public function profile()
        
    {
            $posts = postsModel::get();
            return view('auth.profile.profile',[
                'posts' => $posts,
            ]);
    
        
    }
    public function search_value()
    {
        return view('posts.SearchPosts');
    }
    
    public function Search(Request $request)
    {
    // dd($request);
            
            if($request->Name != null || $request->age != null || $request->lost_year != null){
            $posts = postsModel::Where('descrption', 'like', '%' . $request->Name . '%')
            ->where('age', 'like', '%' . $request->age . '%')
            ->where('lost_day', 'like', '%' . $request->lost_year . '%')
            -> get();   
            

                ///////////////
                if(count($posts) == 0){
                    return back()->with('nothing', "NO SUCH ITEM")->with('Chick',"");
                }else{

                    return view('posts.SearchPosts',[
                        'posts' => $posts
                ]);

                }
        
        }else{       

            return back()->with('fail', "EMPTY");
        }
            //dd($posts);

    }
    public function postsPage(){
        $post = postsModel::get();
        return view('posts.postsPage',[
            'posts' => $post,
        ]);
    }
    public function About(){
        if(!auth()->user()){
        return view('posts.About');
        }else{
            return back();
        }
    }
    
}