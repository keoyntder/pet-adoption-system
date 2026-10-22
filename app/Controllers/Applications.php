<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Applications extends BaseController
{
   public function index()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(site_url('login'));
    }

    if (session()->get('role') !== 'admin') {
        return redirect()->to(site_url('pets'))
            ->with('error', 'You are not authorized to access this page.');
    }

    $db = \Config\Database::connect();

    $applications = $db->table('adoption_applications a')
        ->select('
            a.id,
            a.user_id,
            a.pet_id,
            a.application_date,
            a.status,
            a.reason,
            a.admin_notes,
            u.full_name AS adopter_name,
            u.email AS adopter_email,
            u.phone AS adopter_phone,
            p.name AS pet_name,
            p.species AS pet_species,
            p.image AS pet_image,
            p.status AS pet_status
        ')
        ->join('users u', 'u.id = a.user_id')
        ->join('pets p', 'p.id = a.pet_id')
        ->orderBy('a.id', 'DESC')
        ->get()
        ->getResultArray();

    return view('admin/applications/index', [
        'title' => 'Manage Applications',
        'applications' => $applications,
    ]);
}
}
