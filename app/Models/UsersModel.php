<?php
/**
 * 
 * User Model: contains all user related data and database configuration
 * @author sumit mishra cr7sumitmishra@gmail.com
 * @version 1.0
 * 
 */
namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table            = 'USERS';
    protected $primaryKey       = 'User_ID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'Name',
        'Email',
        'Password',
        'GoogleCalendarAccessToken',
        'GoogleCalendarRefreshToken',
        'FMDataAPIToken'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getUserByEmail($email)
    {
        return $this->where('Email', $email)->first();
    }

    public function createUser($data)
    {
        return $this->insert($data);
    }
}
