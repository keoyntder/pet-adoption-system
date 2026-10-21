<?php

namespace App\Models;

use CodeIgniter\Model;

class AdoptionApplicationModel extends Model
{
    protected $table            = 'adoption_applications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'user_id',
        'pet_id',
        'application_date',
        'status',
        'reason',
        'admin_notes',
    ];
}
