<?php

namespace MyApiRest\Http\Controllers;

use Illuminate\Http\Request;

use MyApiRest\Http\Requests;
use MyApiRest\Http\Controllers\Controller;
use Auth;
use MyApiRest\Video;

class VideoController extends Controller
{
    /**
     * carga todos los videos
     *
     */
    public function index()
    {
        $videos = Video::get();
        return response()->json([
            'msg'=>"success",
            'videos'=>$videos->toArray()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // Si nuestros datos pasan la validacion creamos un nuevo objeto con base al modelo User para crear un nuevo usuario en la base de datos
        $video = new Video($request->all());
        $video->author = Auth::user()->name;
        $video->save();

       
        return response()->json([
            'success'=>'Video creado correctamente'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $video = Video::find($id);
        return response()->json([
            'video'=>$video->toArray()
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        $video = Video::find($id);
        $video->title = $request->title;
        $video->sumary = $request->sumary;
        $video->save();

        return response()->json([
            'msg'=>"Success"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $video = Video::find($id);
        $video->delete();
       
        return response()->json([
            'msg'=>"Success"
        ], 200);
    }
}
