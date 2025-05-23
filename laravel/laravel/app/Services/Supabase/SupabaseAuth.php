<?php

namespace App\Services\Supabase;

use Illuminate\Support\Facades\Http;

class SupabaseAuth
{
    protected $client;

    public function __construct(SupabaseClient $client)
    {
        $this->client = $client;
    }

    /**
     * Sign up a new user
     */
    public function signUp(string $email, string $password, array $userData = [])
    {
        $url = $this->client->getUrl() . '/auth/v1/signup';
        $headers = $this->client->getHeaders();
        
        $response = Http::withHeaders($headers)->post($url, [
            'email' => $email,
            'password' => $password,
            'data' => $userData
        ]);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Supabase Auth error: ' . $response->status() . ' ' . $response->body());
    }

    /**
     * Sign in a user
     */
    public function signIn(string $email, string $password)
    {
        $url = $this->client->getUrl() . '/auth/v1/token?grant_type=password';
        $headers = $this->client->getHeaders();
        
        $response = Http::withHeaders($headers)->post($url, [
            'email' => $email,
            'password' => $password
        ]);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Supabase Auth error: ' . $response->status() . ' ' . $response->body());
    }

    /**
     * Sign out a user
     */
    public function signOut(string $accessToken)
    {
        $url = $this->client->getUrl() . '/auth/v1/logout';
        $headers = $this->client->getHeaders();
        $headers['Authorization'] = 'Bearer ' . $accessToken;
        
        $response = Http::withHeaders($headers)->post($url);
        
        if ($response->successful()) {
            return true;
        }
        
        throw new \Exception('Supabase Auth error: ' . $response->status() . ' ' . $response->body());
    }

    /**
     * Reset password
     */
    public function resetPassword(string $email)
    {
        $url = $this->client->getUrl() . '/auth/v1/recover';
        $headers = $this->client->getHeaders();
        
        $response = Http::withHeaders($headers)->post($url, [
            'email' => $email
        ]);
        
        if ($response->successful()) {
            return true;
        }
        
        throw new \Exception('Supabase Auth error: ' . $response->status() . ' ' . $response->body());
    }

    /**
     * Get user by access token
     */
    public function getUser(string $accessToken)
    {
        $url = $this->client->getUrl() . '/auth/v1/user';
        $headers = $this->client->getHeaders();
        $headers['Authorization'] = 'Bearer ' . $accessToken;
        
        $response = Http::withHeaders($headers)->get($url);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Supabase Auth error: ' . $response->status() . ' ' . $response->body());
    }
} 