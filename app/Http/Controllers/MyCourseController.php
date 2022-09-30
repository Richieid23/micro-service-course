<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\MyCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $myCourses = MyCourse::query()->with('course');

        $userId = $request->query('user_id');
        $myCourses->when($userId, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        });

        return \response()->json([
            'status' => 'success',
            'data' => $myCourses->get()
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
            'course_id' => 'required|integer',
            'user_id' => 'required|integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return \response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $courseId = $request->input('course_id');
        $course = Course::find($courseId);
        if (!$course) {
            return \response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        $userId = $request->input('user_id');
        $user = \getUser($userId);

        if ($user['status'] === 'error') {
            return \response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ], $user['http_code']);
        }

        $isExist = MyCourse::where('course_id', $courseId)->where('user_id', $userId)->exists();
        if ($isExist) {
            return \response()->json([
                'status' => 'error',
                'message' => 'user already taken this course'
            ], 409);
        }

        if ($course->type === 'premium') {
            if ($course->price === 0) {
                return \response()->json([
                    'status' => 'error',
                    'message' => 'price cannot be 0'
                ], 405);
            }
            $order = \postOrder([
                'user' => $user['data'],
                'course' => $course->toArray()
            ]);

            if ($order['status'] === 'error') {
                return \response()->json([
                    'status' => $order['status'],
                    'message' => $order['message']
                ], $order['http_code']);
            }

            return \response()->json([
                'status' => $order['status'],
                'data' =>  $order['data']
            ]);
        } else {
            $myCourse = MyCourse::create($data);
            return \response()->json([
                'status' => 'success',
                'data' => $myCourse
            ]);
        }
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function createPremiumAccess(Request $request)
    {
        $data = $request->all();

        $myCourse = MyCourse::create($data);

        return \response()->json([
            'status' => 'success',
            'data' => $myCourse
        ]);
    }
}
