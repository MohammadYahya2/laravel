<?php

namespace App\Services\Supabase;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseClient
{
    protected $url;
    protected $key;
    protected $headers;

    public function __construct(string $url, string $key)
    {
        $this->url = rtrim($url, '/');
        $this->key = $key;
        
        if (app()->environment('local')) {
            Log::debug('Supabase URL: ' . $this->url);
            Log::debug('Key length: ' . strlen($key));
            Log::debug('Key starts with: ' . substr($key, 0, 10) . '...');
        }
        
        $this->headers = [
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation',
        ];
    }

    /**
     * Get a reference to a Supabase table
     */
    public function from(string $table)
    {
        return new SupabaseTable($this, $table);
    }

    /**
     * Get the base URL for Supabase API calls
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get the headers for Supabase API calls
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Handle authentication with Supabase
     */
    public function auth()
    {
        return new SupabaseAuth($this);
    }

    /**
     * Execute a raw SQL query
     */
    public function query(string $query, array $params = [])
    {
        // For simplicity, we're not implementing this yet
        // Would require the proper Supabase REST API endpoint for raw queries
        throw new \Exception('Raw query method not implemented yet');
    }
} 