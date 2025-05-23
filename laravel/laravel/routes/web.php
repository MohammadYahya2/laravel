<?php

use Illuminate\Support\Facades\Route;
use App\Facades\Supabase;

Route::view('/', 'welcome');

Route::get('/supabase-test', function () {
    try {
        $courses = Supabase::from('courses')->select('*')->limit(5)->get();
        return response()->json([
            'success' => true,
            'message' => 'Supabase connection successful',
            'data' => $courses
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Supabase connection failed',
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('/direct-supabase-test', function () {
    try {
        $url = env('SUPABASE_URL');
        $service_key = env('SUPABASE_SERVICE_ROLE_KEY');
        $anon_key = env('SUPABASE_ANON_KEY');
        $config_key = config('supabase.key');
        
        $client = new \App\Services\Supabase\SupabaseClient($url, $service_key);
        $test = $client->from('categories')->select('count(*)')->get();
        
        return [
            'success' => true,
            'message' => 'تم الاتصال بنجاح!',
            'env_values' => [
                'url' => $url,
                'service_key_exists' => !empty($service_key),
                'service_key_start' => !empty($service_key) ? substr($service_key, 0, 10) . '...' : null,
                'anon_key_exists' => !empty($anon_key),
                'config_key_exists' => !empty($config_key),
            ],
            'test_result' => $test
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage(),
            'env_values' => [
                'url' => $url ?? null,
                'service_key_exists' => !empty($service_key),
                'anon_key_exists' => !empty($anon_key),
                'config_key_exists' => !empty($config_key),
            ]
        ];
    }
});

Route::get('/hardcoded-test', function () {
    try {
        $url = 'https://qegjanhrwcsnspnvrzyk.supabase.co';
        $key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0NzQ4MTA0NywiZXhwIjoyMDYzMDU3MDQ3fQ.NwgKZ1yy3BCtpgCJ37TZWvhqkq_pteetufXiBDgL5PY';
        
        $client = new \App\Services\Supabase\SupabaseClient($url, $key);
        $response = $client->from('categories')->select('count(*)')->get();
        
        return [
            'success' => true,
            'message' => 'تم الاتصال بنجاح مع المفتاح المباشر!',
            'response' => $response
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => 'فشل الاتصال مع المفتاح المباشر',
            'error' => $e->getMessage()
        ];
    }
});

Route::get('/direct-http-test', function () {
    try {
        $url = 'https://qegjanhrwcsnspnvrzyk.supabase.co/rest/v1/categories';
        $key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0NzQ4MTA0NywiZXhwIjoyMDYzMDU3MDQ3fQ.NwgKZ1yy3BCtpgCJ37TZWvhqkq_pteetufXiBDgL5PY';
        
        $headers = [
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
            'Content-Type' => 'application/json'
        ];
        
        // استخدام Guzzle/HTTP مباشرة
        $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
            ->get($url, ['select' => 'count(*)']);
        
        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json(),
            'headers_sent' => [
                'apikey' => substr($key, 0, 10) . '...',
                'Authorization' => 'Bearer ' . substr($key, 0, 10) . '...',
            ]
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
});

// اختبار باستخدام HTTP مباشرة وتجربة صيغ مختلفة للمفتاح
Route::get('/another-test', function () {
    try {
        $url = 'https://qegjanhrwcsnspnvrzyk.supabase.co/rest/v1/categories';
        $key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0NzQ4MTA0NywiZXhwIjoyMDYzMDU3MDQ3fQ.NwgKZ1yy3BCtpgCJ37TZWvhqkq_pteetufXiBDgL5PY';
        
        // تجربة صيغة مختلفة للهيدرز
        $headers = [
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
        
        // تجربة طريقة مختلفة لإرسال الطلب
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'query' => ['select' => 'count(*)']
        ]);
        
        return [
            'success' => true,
            'status' => $response->getStatusCode(),
            'body' => $response->getBody()->getContents(),
            'headers_sent' => [
                'apikey' => substr($key, 0, 10) . '...',
                'Authorization' => 'Bearer ' . substr($key, 0, 10) . '...',
            ]
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
});

// اختبار باستخدام مشروع Supabase للعرض التوضيحي
Route::get('/demo-project-test', function () {
    try {
        // استخدام مشروع العرض التوضيحي الرسمي
        $url = 'https://supabase.supabase.co/rest/v1/todos';
        $key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InN1cGFiYXNlIiwicm9sZSI6ImFub24iLCJpYXQiOjE2ODgzODE0MjEsImV4cCI6MjAwMzk1NzQyMX0.z0j-_AJpP5flcO5j04Ujh-hsI8N7BOAPrS9M1K_hxqg';
        
        $headers = [
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
            'Content-Type' => 'application/json'
        ];
        
        $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
            ->get($url, ['select' => '*', 'limit' => 1]);
        
        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json(),
            'headers_sent' => [
                'apikey' => substr($key, 0, 10) . '...',
                'Authorization' => 'Bearer ' . substr($key, 0, 10) . '...',
            ],
            'message' => 'هذا اختبار على مشروع العرض التوضيحي الرسمي'
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
});

// اختبار التوصيل بالإنترنت
Route::get('/network-test', function () {
    try {
        // اختبار الاتصال بعدة مواقع
        $sites = [
            'https://qegjanhrwcsnspnvrzyk.supabase.co',
            'https://google.com',
            'https://api.github.com'
        ];
        
        $results = [];
        
        foreach ($sites as $site) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(5)->get($site);
                $results[$site] = [
                    'status' => $response->status(),
                    'success' => $response->successful(),
                    'body_size' => strlen($response->body()),
                ];
            } catch (\Exception $e) {
                $results[$site] = [
                    'error' => $e->getMessage()
                ];
            }
        }
        
        // اختبار الـ DNS
        $dns_results = [];
        foreach (['qegjanhrwcsnspnvrzyk.supabase.co', 'google.com'] as $host) {
            $dns_results[$host] = gethostbyname($host) !== $host ? 'يعمل' : 'لا يعمل';
        }
        
        return [
            'message' => 'نتائج اختبار الشبكة',
            'sites' => $results,
            'dns_resolution' => $dns_results,
            'server_ip' => $_SERVER['SERVER_ADDR'] ?? 'غير متوفر',
            'client_ip' => $_SERVER['REMOTE_ADDR'] ?? 'غير متوفر',
            'php_version' => PHP_VERSION,
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
});

// اختبار بسيط ومباشر
Route::get('/simple-test', function () {
    try {
        // بيانات المشروع الخاص بك
        $url = 'https://qegjanhrwcsnspnvrzyk.supabase.co/rest/v1/categories';
        $key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0NzQ4MTA0NywiZXhwIjoyMDYzMDU3MDQ3fQ.NwgKZ1yy3BCtpgCJ37TZWvhqkq_pteetufXiBDgL5PY';
        
        // استخدم CURL مباشرة للتحكم أكثر
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $key,
            'Authorization: Bearer ' . $key
        ]);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        
        curl_close($ch);
        
        if ($error) {
            return [
                'success' => false,
                'error' => $error,
                'info' => $info,
                'url' => $url,
                'tip' => 'تحقق من إعدادات DNS والاتصال بالإنترنت'
            ];
        }
        
        return [
            'success' => true,
            'status_code' => $info['http_code'],
            'response' => json_decode($response, true),
            'connection_time' => $info['connect_time'],
            'total_time' => $info['total_time']
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// اختبار وجود المشروع في Supabase
Route::get('/check-project', function () {
    try {
        // المشروع الخاص بك
        $projectId = 'qegjanhrwcsnspnvrzyk';
        
        // اختبار عدة مسارات محتملة
        $paths = [
            "https://{$projectId}.supabase.co",
            "https://{$projectId}.supabase.co/rest/v1/",
            "https://{$projectId}.supabase.co/auth/v1/",
            "https://api.supabase.io/projects/{$projectId}"
        ];
        
        $results = [];
        foreach ($paths as $path) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(5)
                    ->withHeaders(['Accept' => 'application/json'])
                    ->get($path);
                
                $results[$path] = [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'success' => $response->successful(),
                ];
            } catch (\Exception $e) {
                $results[$path] = [
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return [
            'message' => 'نتائج التحقق من وجود المشروع',
            'project_id' => $projectId,
            'results' => $results,
            'tip' => 'إذا كانت جميع المسارات ترجع خطأ 404، فمن المحتمل أن المشروع غير موجود أو تم حذفه'
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
});

require __DIR__.'/auth.php';
