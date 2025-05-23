<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CourseController as ApiCourseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// مستخدم مركَّز (Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Supabase Auth
Route::post('/register',        [AuthController::class, 'register']);
Route::post('/login',           [AuthController::class, 'login']);
Route::post('/logout',          [AuthController::class, 'logout']);
Route::post('/user',            [AuthController::class, 'user']);
Route::post('/reset-password',  [AuthController::class, 'resetPassword']);

// دورات Laravel (قاعدة البيانات الداخلية)
Route::get('/courses',          [CourseController::class, 'index']);
Route::post('/courses',         [CourseController::class, 'store']);
Route::get('/courses/{cid}',    [CourseController::class, 'show']);
Route::put('/courses/{cid}',    [CourseController::class, 'update']);
Route::delete('/courses/{cid}', [CourseController::class, 'destroy']);

// موارد Supabase
Route::apiResource('categories',  CategoryController::class);
Route::apiResource('products',    ProductController::class);
Route::apiResource('api-courses', ApiCourseController::class);

// راوت لتصحيح متغيرات البيئة
Route::get('/debug-supabase-env', function () {
    return response()->json([
        'url'          => env('SUPABASE_URL'),
        'anon_key_len' => strlen(env('SUPABASE_ANON_KEY') ?? ''),
        'service_key_len'=> strlen(env('SUPABASE_SERVICE_ROLE_KEY') ?? ''),
    ]);
});

// راوت لاختبار الاتصال بـ Supabase
Route::get('/test-supabase', function () {
    try {
        $response = app('supabase')
            ->from('categories')
            ->select('id')
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Supabase connection successful!',
            'data'    => $response,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Supabase connection failed',
            'error'   => $e->getMessage(),
        ], 500);
    }
});
