<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentsModel extends Model
{
    protected $table            = 'APPOINTMENTS';
    protected $primaryKey       = 'Appointment_ID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'Appointment_ID',
        'User_ID',
        'Therapist_ID',
        'Therapist_Name',
        'Date',
        'Time_slot',
        'Status',
        'fmRecordId'
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

    public function getAppointments()
    {
        return $this->where('User_ID', session()->get('user_id'))->findAll();
    }

    public function getAppointment($id)
    {
        return $this->where('Appointment_ID', $id)->first();
    }

    public function getAppointmentByFMRecordId($id){
        return $this->where('fmRecordId', $id)->first();
    }
}
