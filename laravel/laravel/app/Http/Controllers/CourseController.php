<?php

namespace App\Http\Controllers;

use App\Facades\Supabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $limit;
        
        try {
            $courses = Supabase::from('courses')
                ->select('*')
                ->limit($limit)
                ->offset($offset)
                ->order('cid', 'desc')
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $courses,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'teacher_id' => 'required|integer'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $course = Supabase::from('courses')->insert([
                'title' => $request->title,
                'description' => $request->description,
                'teacher_id' => $request->teacher_id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Course created successfully',
                'data' => $course
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified course.
     */
    public function show($cid)
    {
        try {
            $course = Supabase::from('courses')
                ->filter('cid', 'eq', $cid)
                ->limit(1)
                ->get();
            
            if (empty($course)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }
            
            $course = $course[0];
            
            // Get related lessons
            $lessons = Supabase::from('lessons')
                ->select('*')
                ->filter('course_id', 'eq', $cid)
                ->order('cid', 'asc')
                ->get();
            
            $course['lessons'] = $lessons;
            
            return response()->json([
                'success' => true,
                'data' => $course
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, $cid)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'teacher_id' => 'sometimes|required|integer'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            // Check if course exists
            $existingCourse = Supabase::from('courses')
                ->filter('cid', 'eq', $cid)
                ->limit(1)
                ->get();
            
            if (empty($existingCourse)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }
            
            $existingCourse = $existingCourse[0];
            
            // Update the course
            $updatedCourse = Supabase::from('courses')
                ->filter('cid', 'eq', $cid)
                ->update([
                    'title' => $request->title ?? $existingCourse['title'],
                    'description' => $request->description ?? $existingCourse['description'],
                    'teacher_id' => $request->teacher_id ?? $existingCourse['teacher_id']
                ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully',
                'data' => $updatedCourse
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy($cid)
    {
        try {
            // Check if course exists
            $existingCourse = Supabase::from('courses')
                ->filter('cid', 'eq', $cid)
                ->limit(1)
                ->get();
            
            if (empty($existingCourse)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }
            
            // Delete the course
            Supabase::from('courses')
                ->filter('cid', 'eq', $cid)
                ->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete course',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 