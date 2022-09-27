<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lessons = Lesson::query();

        $chapterId = $request->query('chapter_id');
        $lessons->when($chapterId, function ($query) use ($chapterId) {
            return $query->where('chapter_id', $chapterId);
        });

        return \response()->json([
            'status' => 'success',
            'data' => $lessons->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'video' => 'required|string',
            'chapter_id' => 'required|integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return \response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $chapterId = $request->input('chapter_id');
        $chapter = Chapter::find($chapterId);
        if (!$chapter) {
            return \response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        $lesson = Lesson::create($data);

        return \response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return \response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ], 404);
        }

        return \response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);
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
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'video' => 'string',
            'chapter_id' => 'integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return \response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $lesson = Lesson::find($id);
        if (!$lesson) {
            return \response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ], 404);
        }

        $chapterId = $request->input('chapter_id');
        if ($chapterId) {
            $chapter = Chapter::find($chapterId);
            if (!$chapter) {
                return \response()->json([
                    'status' => 'error',
                    'message' => 'chapter not found'
                ], 404);
            }
        }

        $lesson->fill($data);
        $lesson->save();

        return \response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return \response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ], 404);
        }

        $lesson->delete();

        return \response()->json([
            'status' => 'success',
            'message' => 'lesson deleted successfully'
        ]);
    }
}
