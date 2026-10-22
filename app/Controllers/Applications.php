<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Applications extends BaseController
{
   public function index()
    {
        // Require login.
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('login'));
        }

        // Only adopters can access this page.
        if (session()->get('role') !== 'adopter') {
            return redirect()->to(site_url('admin/dashboard'));
        }

        $db = db_connect();

        $applications = $db->table('adoption_applications a')
            ->select(
                'a.id, a.pet_id, a.application_date, a.status,
                a.reason, a.admin_notes,
                p.name AS pet_name,
                p.species AS pet_species,
                p.breed AS pet_breed,
                p.image AS pet_image,
                p.status AS pet_status'
            )
            ->join('pets p', 'p.id = a.pet_id')
            ->where('a.user_id', session()->get('user_id'))
            ->orderBy('a.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('applications/index', [
            'title'        => 'My Applications',
            'applications' => $applications,
        ]);
    }
}
