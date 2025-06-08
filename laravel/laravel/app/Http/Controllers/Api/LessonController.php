<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LessonController extends Controller
{
    /**
     * Display a paginated listing of lessons.
     */
    public function index(Request $request): JsonResponse
    {
        $lessons = Lesson::with('course')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($lessons);
    }

    /**
     * Store a newly created lesson.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $lesson = Lesson::create($validated);
        $lesson->load('course');

        return response()->json($lesson, 201);
    }

    /**
     * Display the specified lesson.
     */
    public function show(string $id): JsonResponse
    {
        $lesson = Lesson::with('course')->findOrFail($id);
        return response()->json($lesson);
    }

    /**
     * Update the specified lesson.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $lesson = Lesson::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $lesson->update($validated);
        $lesson->load('course');

        return response()->json($lesson);
    }

    /**
     * Remove the specified lesson.
     */
    public function destroy(string $id): JsonResponse
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();

        return response()->json(['message' => 'Lesson deleted successfully']);
    }
} 