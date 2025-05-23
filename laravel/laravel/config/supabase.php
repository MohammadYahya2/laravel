<?php

return [

    // عنوان مشروع Supabase
    'url'        => env('SUPABASE_URL'),

    // المفتاح العام (قراءة/كتابة عامة)
    'anon_key'   => env('SUPABASE_ANON_KEY'),

    // مفتاح الخدمة (صلاحيات كاملة، لا تعرضه في الكود المتصفح)
    'service_key'=> env('SUPABASE_SERVICE_ROLE_KEY'),

    // لتأكيد أنّ الملف تمت قراءته
    'config_loaded' => true,
];
