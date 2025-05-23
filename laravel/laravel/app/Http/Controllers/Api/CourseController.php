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
     * Display a listing of the courses.
     * 
     * @OA\Get(
     *     path="/api/api-courses",
     *     tags={"Courses"},
     *     summary="Get list of courses",
     *     @OA\Response(
     *         response=200,
     *         description="List of courses with pagination",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(property="price", type="number", format="float"),
     *                 @OA\Property(property="instructor", type="string"),
     *                 @OA\Property(property="duration", type="string", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="datetime"),
     *                 @OA\Property(property="updated_at", type="string", format="datetime")
     *             )),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(property="last_page", type="integer")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $courses = Course::paginate(10);
        
        return response()->json($courses);
    }

    /**
     * Store a newly created course.
     * 
     * @OA\Post(
     *     path="/api/api-courses",
     *     tags={"Courses"},
     *     summary="Create a new course",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "price", "instructor"},
     *             @OA\Property(property="title", type="string", example="Introduction to Laravel"),
     *             @OA\Property(property="description", type="string", example="A comprehensive course on Laravel framework", nullable=true),
     *             @OA\Property(property="price", type="number", format="float", example=99.99),
     *             @OA\Property(property="instructor", type="string", example="John Doe"),
     *             @OA\Property(property="duration", type="string", example="10 weeks", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Course created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string", nullable=true),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="instructor", type="string"),
     *             @OA\Property(property="duration", type="string", nullable=true),
     *             @OA\Property(property="created_at", type="string", format="datetime"),
     *             @OA\Property(property="updated_at", type="string", format="datetime")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:courses',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'instructor' => 'required|string|max:255',
            'duration' => 'nullable|string',
        ]);

        $course = Course::create($validated);
        
        return response()->json($course, Response::HTTP_CREATED);
    }

    /**
     * Display the specified course.
     * 
     * @OA\Get(
     *     path="/api/api-courses/{id}",
     *     tags={"Courses"},
     *     summary="Get a specific course",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Course ID",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string", nullable=true),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="instructor", type="string"),
     *             @OA\Property(property="duration", type="string", nullable=true),
     *             @OA\Property(property="created_at", type="string", format="datetime"),
     *             @OA\Property(property="updated_at", type="string", format="datetime")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json($course);
    }

    /**
     * Update the specified course.
     * 
     * @OA\Put(
     *     path="/api/api-courses/{id}",
     *     tags={"Courses"},
     *     summary="Update a course",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Course ID",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Laravel Course"),
     *             @OA\Property(property="description", type="string", example="Updated course description", nullable=true),
     *             @OA\Property(property="price", type="number", format="float", example=129.99),
     *             @OA\Property(property="instructor", type="string", example="Jane Smith"),
     *             @OA\Property(property="duration", type="string", example="8 weeks", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string", nullable=true),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="instructor", type="string"),
     *             @OA\Property(property="duration", type="string", nullable=true),
     *             @OA\Property(property="created_at", type="string", format="datetime"),
     *             @OA\Property(property="updated_at", type="string", format="datetime")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255|unique:courses,title,' . $id,
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'instructor' => 'sometimes|required|string|max:255',
            'duration' => 'nullable|string',
        ]);
        
        $course->update($validated);
        
        return response()->json($course);
    }

    /**
     * Remove the specified course.
     * 
     * @OA\Delete(
     *     path="/api/api-courses/{id}",
     *     tags={"Courses"},
     *     summary="Delete a course",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Course ID",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Course deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json(['message' => 'Course not found'], Response::HTTP_NOT_FOUND);
        }
        
        $course->delete();
        
        return response()->noContent();
    }
} 