<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Facades\Supabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class CategoryController extends Controller
{
    protected $table = 'categories';

    /**
     * Display a listing of the categories.
     * Supports pagination with page and per_page query parameters
     * 
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Get list of categories",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of categories",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="datetime"),
     *                 @OA\Property(property="updated_at", type="string", format="datetime")
     *             )),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="last_page", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="debug", type="object", nullable=true)
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);
            
            // Calculate pagination range
            $from = ($page - 1) * $perPage;
            
            // Get total count for pagination metadata
            $totalQuery = Supabase::from($this->table)->select('count(*)');
            $total = $totalQuery->get()[0]['count'] ?? 0;
            
            // Get paginated data
            $categories = Supabase::from($this->table)
                ->select('*')
                ->limit($perPage)
                ->offset($from)
                ->get();
            
            return response()->json([
                'data' => $categories,
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'last_page' => ceil($total / $perPage),
                ]
            ]);
        } catch (\Exception $e) {
            // تحسين رسائل الخطأ
            $message = $e->getMessage();
            $statusCode = 500;
            
            // التحقق مما إذا كان الخطأ متعلق بمفتاح API
            if (strpos($message, 'Invalid API key') !== false) {
                $message = 'Invalid Supabase API key. Please check your configuration in .env file or config/supabase.php';
                $statusCode = 401;
            }
            
            // إظهار معلومات تكوين Supabase في بيئة التطوير
            $debug = [];
            if (app()->environment('local')) {
                $debug = [
                    'supabase_url' => config('supabase.url'),
                    'key_exists' => !empty(config('supabase.key')),
                    'key_length' => strlen(config('supabase.key')),
                    'exception' => $e->getMessage()
                ];
            }
            
            return response()->json([
                'error' => $message,
                'debug' => $debug
            ], $statusCode);
        }
    }

    /**
     * Store a newly created category.
     * 
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Create a new category",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Electronics"),
     *             @OA\Property(property="description", type="string", example="Electronic devices and gadgets", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="datetime"),
     *                 @OA\Property(property="updated_at", type="string", format="datetime")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            // Check if a category with the same name already exists
            $existingCategory = Supabase::from($this->table)
                ->select('*')
                ->filter('name', 'eq', $validated['name'])
                ->get();
            
            if (!empty($existingCategory)) {
                return response()->json([
                    'message' => 'A category with this name already exists',
                    'errors' => ['name' => ['The name has already been taken.']]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            $category = Supabase::from($this->table)
                ->insert([
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? null,
                ]);
            
            return response()->json([
                'message' => 'Category created successfully',
                'data' => $category[0] ?? $category
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified category.
     * 
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Get a specific category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category ID",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category details",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="datetime"),
     *                 @OA\Property(property="updated_at", type="string", format="datetime")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        try {
            $category = Supabase::from($this->table)
                ->select('*')
                ->filter('id', 'eq', $id)
                ->get();
            
            if (empty($category)) {
                return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
            }
            
            return response()->json(['data' => $category[0]]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified category.
     * 
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Update a category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category ID",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Electronics"),
     *             @OA\Property(property="description", type="string", example="Updated description", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="datetime"),
     *                 @OA\Property(property="updated_at", type="string", format="datetime")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
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
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function update(CategoryRequest $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            // Check if the category exists
            $category = Supabase::from($this->table)
                ->select('*')
                ->filter('id', 'eq', $id)
                ->get();
                
            if (empty($category)) {
                return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
            }
            
            // Check if another category has the same name
            $existingCategory = Supabase::from($this->table)
                ->select('*')
                ->filter('name', 'eq', $validated['name'])
                ->get();
            
            if (!empty($existingCategory) && $existingCategory[0]['id'] != $id) {
                return response()->json([
                    'message' => 'A category with this name already exists',
                    'errors' => ['name' => ['The name has already been taken.']]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            $updated = Supabase::from($this->table)
                ->update([
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? null,
                ])
                ->filter('id', 'eq', $id)
                ->get();
            
            return response()->json([
                'message' => 'Category updated successfully',
                'data' => $updated[0] ?? $updated
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified category.
     * 
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Delete a category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category ID",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $category = Supabase::from($this->table)
                ->select('*')
                ->filter('id', 'eq', $id)
                ->get();
                
            if (empty($category)) {
                return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
            }
            
            // Delete the category and handle the boolean response
            // First prepare the query
            $deleteQuery = Supabase::from($this->table)->delete();
            // Then add the filter
            $deleteQuery->filter('id', 'eq', $id);
            
            return response()->json(['message' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 