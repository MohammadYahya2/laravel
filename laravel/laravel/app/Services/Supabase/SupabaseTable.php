<?php

namespace App\Services\Supabase;

use Illuminate\Support\Facades\Http;

class SupabaseTable
{
    protected $client;
    protected $table;
    protected $query;

    public function __construct(SupabaseClient $client, string $table)
    {
        $this->client = $client;
        $this->table = $table;
        $this->query = [];
    }

    /**
     * Select specific columns
     */
    public function select(string $columns = '*')
    {
        $this->query['select'] = $columns;
        return $this;
    }

    /**
     * Add a filter condition
     */
    public function filter(string $column, string $operator, $value)
    {
        if (!isset($this->query['filter'])) {
            $this->query['filter'] = [];
        }
        
        $this->query['filter'][] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];
        
        return $this;
    }

    /**
     * Limit the number of records
     */
    public function limit(int $limit)
    {
        $this->query['limit'] = $limit;
        return $this;
    }

    /**
     * Offset the results
     */
    public function offset(int $offset)
    {
        $this->query['offset'] = $offset;
        return $this;
    }

    /**
     * Order the results
     */
    public function order(string $column, string $direction = 'asc')
    {
        $this->query['order'] = [
            'column' => $column,
            'direction' => $direction
        ];
        
        return $this;
    }

    /**
     * Execute the query and get the results
     */
    public function get()
    {
        $url = $this->client->getUrl() . '/rest/v1/' . $this->table;
        $headers = $this->client->getHeaders();
        
        // Build query parameters
        $params = [];
        
        if (isset($this->query['select'])) {
            $params['select'] = $this->query['select'];
        }
        
        if (isset($this->query['limit'])) {
            $params['limit'] = $this->query['limit'];
        }
        
        if (isset($this->query['offset'])) {
            $params['offset'] = $this->query['offset'];
        }
        
        if (isset($this->query['order'])) {
            $params['order'] = $this->query['order']['column'] . '.' . $this->query['order']['direction'];
        }
        
        // Add filter conditions
        if (isset($this->query['filter']) && !empty($this->query['filter'])) {
            foreach ($this->query['filter'] as $filter) {
                $params[$filter['column']] = $filter['operator'] . '.' . $filter['value'];
            }
        }
        
        // Log request details in development
        if (app()->environment('local')) {
            \Illuminate\Support\Facades\Log::debug('Supabase Request:', [
                'url' => $url,
                'headers' => array_merge(
                    $headers, 
                    ['Authorization' => 'Bearer ' . substr($headers['Authorization'], 7, 3) . '...']
                ),
                'params' => $params
            ]);
        }
        
        // Make the HTTP request
        $response = Http::withHeaders($headers)->get($url, $params);
        
        // Log response in development
        if (app()->environment('local')) {
            \Illuminate\Support\Facades\Log::debug('Supabase Response: ' . $response->status(), [
                'body' => $response->successful() ? 'SUCCESS' : $response->body()
            ]);
        }
        
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Supabase API error: ' . $response->status() . ' ' . $response->body());
    }

    /**
     * Get a single record by id
     */
    public function find($id)
    {
        return $this->filter('id', 'eq', $id)->limit(1)->get()[0] ?? null;
    }

    /**
     * Insert a new record
     */
    public function insert(array $data)
    {
        $url = $this->client->getUrl() . '/rest/v1/' . $this->table;
        $headers = $this->client->getHeaders();
        
        $response = Http::withHeaders($headers)->post($url, $data);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Supabase API error: ' . $response->status() . ' ' . $response->body());
    }

    /**
     * Update records
     */
    public function update(array $data)
    {
        $url = $this->client->getUrl() . '/rest/v1/' . $this->table;
        $headers = $this->client->getHeaders();
        
        // Build query parameters for filtering
        $params = [];
        
        if (isset($this->query['filter']) && !empty($this->query['filter'])) {
            foreach ($this->query['filter'] as $filter) {
                $params[$filter['column']] = $filter['operator'] . '.' . $filter['value'];
            }
        }
        
        $response = Http::withHeaders($headers)->patch($url, $data, $params);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Supabase API error: ' . $response->status() . ' ' . $response->body());
    }

    /**
     * Delete records
     */
    public function delete()
    {
        $url = $this->client->getUrl() . '/rest/v1/' . $this->table;
        $headers = $this->client->getHeaders();
        
        // Build query parameters for filtering
        $params = [];
        
        if (isset($this->query['filter']) && !empty($this->query['filter'])) {
            foreach ($this->query['filter'] as $filter) {
                $params[$filter['column']] = $filter['operator'] . '.' . $filter['value'];
            }
        }
        
        $response = Http::withHeaders($headers)->delete($url, $params);
        
        if ($response->successful()) {
            return true;
        }
        
        throw new \Exception('Supabase API error: ' . $response->status() . ' ' . $response->body());
    }
} 