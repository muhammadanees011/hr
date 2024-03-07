<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Auth;
class VideoController extends Controller
{
    public function index()
    {
        // if (\Auth::user()->can('Manage Video')) {
            $videos = Video::where('created_by', '=', \Auth::user()->creatorId())->get();
            return view('video.index', compact('videos'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $videos = Video::where('created_by', '=', \Auth::user()->creatorId())->get();
        return view('video.create', compact('videos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $auth_id = Auth::user()->id;
        $validator = \Validator::make(
            $request->all(),
            [
                'title' => 'string|required',
                'source_type' => 'string|required',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = public_path() . '/videos';
            $file->move($path,$fileName);
        }


        $video = new Video();
        $video->title = $request->title;
        $video->source_type = $request->source_type;
        $video->video_link = $request->video_link;
        $video->video_file = $fileName;
        $video->created_by = $auth_id;
        $video->save();
    
        return redirect()->route('video.index')->with('success', __('Video uploaded successfully.'));
    }

    

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $video =  Video::where('id', $id)->first();
        return view('video.edit', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        $auth_id = Auth::user()->id;
        $validator = \Validator::make(
            $request->all(),
            [
                'title' => 'string|required',
                'source_type' => 'string|required',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = public_path() . '/videos';
            $file->move($path,$fileName);
        }


        $video->title = $request->title;
        $video->source_type = $request->source_type;
        $video->video_link = $request->video_link;
        $video->video_file = $fileName ?? $video->video_file;
        $video->created_by = $auth_id;
        $video->save();
    
        return redirect()->route('video.index')->with('success', __('Video updated successfully.'));
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        $video->delete();
        return redirect()->route('video.index')->with('success', __('Video deleted successfully.'));
        
    }
}
