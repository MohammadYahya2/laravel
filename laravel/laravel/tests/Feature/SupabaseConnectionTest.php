<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Facades\Supabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupabaseConnectionTest extends TestCase
{
    /**
     * Test that Supabase connection works.
     */
    public function test_supabase_connection_works(): void
    {
        $response = $this->get('/supabase-test');
        
        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
    }
    
    /**
     * Test that courses can be fetched from Supabase.
     */
    public function test_can_fetch_courses(): void
    {
        $courses = Supabase::from('courses')
            ->select('*')
            ->limit(5)
            ->get();
            
        $this->assertIsArray($courses);
    }
    
    /**
     * Test course API endpoint.
     */
    public function test_courses_api_endpoint(): void
    {
        $response = $this->get('/api/courses');
        
        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
    }
} 