<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class CourseController extends Controller
{
    /**
     * Display a listing of courses for API.
     */
    public function index(): JsonResponse
    {
        $courses = Course::orderBy('created_at', 'desc')->get();
        return response()->json($courses);
    }

    /**
     * Store a newly created course.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course = Course::create($validated);
        return response()->json($course, 201);
    }

    /**
     * Display the specified course.
     */
    public function show(string $id): JsonResponse
    {
        $course = Course::with('lessons')->findOrFail($id);
        return response()->json($course);
    }

    /**
     * Update the specified course.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course->update($validated);
        return response()->json($course);
    }

    /**
     * Remove the specified course.
     */
    public function destroy(string $id): JsonResponse
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(['message' => 'Course deleted successfully']);
    }
} 