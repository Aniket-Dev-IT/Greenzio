<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Base Model Class
 * 
 * Provides common database operations and methods that can be inherited by other models
 * This class extends CI_Model and adds common CRUD functionality
 */
class Base_model extends CI_Model
{
    /**
     * Table name - should be overridden in child classes
     * @var string
     */
    protected $table = '';
    
    /**
     * Primary key field name - should be overridden in child classes
     * @var string
     */
    protected $primary_key = 'id';
    
    /**
     * Fillable fields for mass assignment protection
     * @var array
     */
    protected $fillable = [];
    
    /**
     * Whether to use timestamps (created_at, updated_at)
     * @var boolean
     */
    protected $timestamps = true;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Get all records from table with optional conditions and ordering
     * 
     * @param array $conditions WHERE conditions
     * @param string $order_by ORDER BY clause
     * @param int $limit LIMIT clause
     * @param int $offset OFFSET clause
     * @return array
     */
    public function get_all($conditions = [], $order_by = null, $limit = null, $offset = 0)
    {
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get($this->table);
        return $query->result_array();
    }
    
    /**
     * Get a single record by ID or conditions
     * 
     * @param mixed $id_or_conditions ID or array of conditions
     * @return array|null
     */
    public function get_by_id($id_or_conditions)
    {
        if (is_array($id_or_conditions)) {
            $this->db->where($id_or_conditions);
        } else {
            $this->db->where($this->primary_key, $id_or_conditions);
        }
        
        $query = $this->db->get($this->table);
        return $query->row_array();
    }
    
    /**
     * Get first record matching conditions
     * 
     * @param array $conditions WHERE conditions
     * @param string $order_by ORDER BY clause
     * @return array|null
     */
    public function get_first($conditions = [], $order_by = null)
    {
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        
        $this->db->limit(1);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }
    
    /**
     * Insert a new record
     * 
     * @param array $data Data to insert
     * @return int|bool Insert ID on success, FALSE on failure
     */
    public function insert($data)
    {
        // Filter data to only include fillable fields if specified
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }
        
        // Add timestamps if enabled
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        
        return false;
    }
    
    /**
     * Update record(s)
     * 
     * @param mixed $id_or_conditions ID or array of conditions
     * @param array $data Data to update
     * @return bool
     */
    public function update($id_or_conditions, $data)
    {
        // Filter data to only include fillable fields if specified
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }
        
        // Add updated timestamp if enabled
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        if (is_array($id_or_conditions)) {
            $this->db->where($id_or_conditions);
        } else {
            $this->db->where($this->primary_key, $id_or_conditions);
        }
        
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Delete record(s)
     * 
     * @param mixed $id_or_conditions ID or array of conditions
     * @return bool
     */
    public function delete($id_or_conditions)
    {
        if (is_array($id_or_conditions)) {
            $this->db->where($id_or_conditions);
        } else {
            $this->db->where($this->primary_key, $id_or_conditions);
        }
        
        return $this->db->delete($this->table);
    }
    
    /**
     * Count records matching conditions
     * 
     * @param array $conditions WHERE conditions
     * @return int
     */
    public function count($conditions = [])
    {
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        
        return $this->db->count_all_results($this->table);
    }
    
    /**
     * Check if record exists
     * 
     * @param mixed $id_or_conditions ID or array of conditions
     * @return bool
     */
    public function exists($id_or_conditions)
    {
        if (is_array($id_or_conditions)) {
            $this->db->where($id_or_conditions);
        } else {
            $this->db->where($this->primary_key, $id_or_conditions);
        }
        
        $this->db->limit(1);
        $query = $this->db->get($this->table);
        return $query->num_rows() > 0;
    }
    
    /**
     * Get the last inserted ID
     * 
     * @return int
     */
    public function get_last_insert_id()
    {
        return $this->db->insert_id();
    }
    
    /**
     * Truncate table (delete all records)
     * 
     * @return bool
     */
    public function truncate()
    {
        return $this->db->truncate($this->table);
    }
    
    /**
     * Get distinct values from a column
     * 
     * @param string $column Column name
     * @param array $conditions WHERE conditions
     * @return array
     */
    public function get_distinct($column, $conditions = [])
    {
        $this->db->distinct();
        $this->db->select($column);
        
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        
        $query = $this->db->get($this->table);
        return array_column($query->result_array(), $column);
    }
    
    /**
     * Execute raw query and return results
     * 
     * @param string $sql SQL query
     * @param array $params Query parameters
     * @return array
     */
    public function raw_query($sql, $params = [])
    {
        $query = $this->db->query($sql, $params);
        return $query->result_array();
    }
    
    /**
     * Begin transaction
     */
    public function begin_transaction()
    {
        $this->db->trans_begin();
    }
    
    /**
     * Commit transaction
     */
    public function commit_transaction()
    {
        $this->db->trans_commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback_transaction()
    {
        $this->db->trans_rollback();
    }
    
    /**
     * Check if transaction has failed
     * 
     * @return bool
     */
    public function transaction_failed()
    {
        return $this->db->trans_status() === FALSE;
    }
    
    /**
     * Get table name
     * 
     * @return string
     */
    public function get_table()
    {
        return $this->table;
    }
    
    /**
     * Get primary key field name
     * 
     * @return string
     */
    public function get_primary_key()
    {
        return $this->primary_key;
    }
    
    /**
     * Apply common filters like pagination, sorting, searching
     * 
     * @param array $filters Filter parameters
     * @return void
     */
    protected function apply_filters($filters = [])
    {
        // Search filter
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search_fields = isset($filters['search_fields']) ? $filters['search_fields'] : ['name'];
            $this->db->group_start();
            foreach ($search_fields as $field) {
                $this->db->or_like($field, $filters['search']);
            }
            $this->db->group_end();
        }
        
        // Date range filter
        if (isset($filters['date_from']) && !empty($filters['date_from'])) {
            $date_field = isset($filters['date_field']) ? $filters['date_field'] : 'created_at';
            $this->db->where($date_field . ' >=', $filters['date_from']);
        }
        
        if (isset($filters['date_to']) && !empty($filters['date_to'])) {
            $date_field = isset($filters['date_field']) ? $filters['date_field'] : 'created_at';
            $this->db->where($date_field . ' <=', $filters['date_to']);
        }
        
        // Status filter
        if (isset($filters['status']) && $filters['status'] !== '') {
            $this->db->where('status', $filters['status']);
        }
        
        // Sorting
        if (isset($filters['sort_by']) && !empty($filters['sort_by'])) {
            $sort_order = isset($filters['sort_order']) ? $filters['sort_order'] : 'ASC';
            $this->db->order_by($filters['sort_by'], $sort_order);
        }
    }
    
    /**
     * Get paginated results
     * 
     * @param int $page Current page
     * @param int $per_page Records per page
     * @param array $conditions WHERE conditions
     * @param string $order_by ORDER BY clause
     * @return array
     */
    public function get_paginated($page = 1, $per_page = 10, $conditions = [], $order_by = null)
    {
        $offset = ($page - 1) * $per_page;
        
        // Get total count for pagination
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        $total_records = $this->db->count_all_results($this->table, false);
        
        // Get paginated results
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        
        $this->db->limit($per_page, $offset);
        $query = $this->db->get();
        $records = $query->result_array();
        
        return [
            'records' => $records,
            'total_records' => $total_records,
            'current_page' => $page,
            'per_page' => $per_page,
            'total_pages' => ceil($total_records / $per_page)
        ];
    }
}
