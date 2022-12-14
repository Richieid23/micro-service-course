<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MentorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mentors = Mentor::all();
        return \response()->json([
            'status' => 'success',
            'data' => $mentors
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
            'profile' => 'required|url',
            'email' => 'required|email',
            'profession' => 'required|string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return \response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $mentor = Mentor::create($data);

        return \response()->json([
            'status' => 'success',
            'data' => $mentor
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
        $mentor = Mentor::find($id);
        if (!$mentor) {
            return \response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        return \response()->json([
            'status' => 'success',
            'data' => $mentor
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
            'name' => 'required|string',
            'profile' => 'required|url',
            'email' => 'required|email',
            'profession' => 'required|string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return \response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $mentor = Mentor::find($id);
        if (!$mentor) {
            return \response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        $mentor->fill($data);
        $mentor->save();

        return \response()->json([
            'status' => 'success',
            'data' => $mentor
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
        $mentor = Mentor::find($id);
        if (!$mentor) {
            return \response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        $mentor->delete();
        return \response()->json([
            'status' => 'error',
            'message' => 'mentor deleted successfully'
        ]);
    }
}
