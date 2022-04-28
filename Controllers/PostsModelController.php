<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\postsModel;
use \App\Models\User;
use Illuminate\Support\Facades\File; 

class PostsModelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public $imageName ;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if(auth()->user()->Admin==0){
            $request->validate([
                'phone' => 'required|max:13|min:7',
                'image_path' => 'required|image',
                'descrption' => 'required|min:10|max:260',
                'date_lose' => 'required',
                'age' => 'required',
            ]);


            /////////////////////
            $imageName = time(). "-" ."imag".".".$request->image_path->extension();
            $request->image_path->move(public_path('images'), $imageName);

            $age = date('Y', strtotime($request->age));
            $date_lost = date('Y', strtotime($request->date_lose));
            $num = str_replace( " " , "", $request->phone);
            
            
            $post = new postsModel();
                $post->phone_number = $num;
                $post->image_path = $imageName;
                $post->user_id = auth()->id();
                $post->age =$age;
                $post->lost_day =$date_lost;
                $post->IsAdmin =0;
                $post->descrption = $request->descrption;
                $save = $post->save();
                    if($save){
                        return back()->with('success','The Post Are Created');
                    }else{
                        return back()->with('fail','Somthing goes Wrong');
                    }
        }else{

            
            $request->validate([
            
                'descrption' => 'required|min:10|max:260',
            ]);

            $post = new postsModel();
            $post->descrption = $request->descrption;

        }
            
        //return redirect('/home');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $posts = postsModel::find($id);
        //$posts = $posts->attributes; 
        return view('posts/edit',[
            'post'=> $posts
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'phone' => 'required|max:13|min:7',
            'image_path' => 'required|image',
            'descrption' => 'required|min:10|max:260',
            'lost_day' => 'required',
            'age' => 'required',
        ]);

                    $imageName = time(). "-" ."imag".".".$request->image_path->extension();
                    $request->image_path->move(public_path('images'), $imageName);
                    $age = date('Y', strtotime($request->age));
                    $date_lost = date('Y', strtotime($request->date_lose));
                    $num = str_replace( " " , "", $request->phone);
        
            //////////////////////////
            $posts = postsModel::where('id', '=', $id)
            ->update([

                'phone_number' => $num,
                'descrption' => $request->descrption,
                'image_path' => $imageName, 
                'age' => $age,
                'lost_day' =>$date_lost

            ]);
        
            return redirect(route('profile'))->with('success', 'the');

        

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = postsModel::find($id);
            File::delete(public_path("images\\" . $post->image_path));
            postsModel::where('id','=',$post->id)->delete();
        
        return redirect('profile');
    }
}
