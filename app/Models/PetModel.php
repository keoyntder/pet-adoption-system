<?php

namespace App\Models;

use CodeIgniter\Model;

class PetModel extends Model
{
      protected $table            = 'pets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'name',
        'species',
        'breed',
        'sex',
        'age',
        'size',
        'color',
        'description',
        'image',
        'status',
    ];
}
