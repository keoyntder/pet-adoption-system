
<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdoptionApplicationModel;

class Applications extends BaseController
{
    public function index()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('login'));
        }

        if (session()->get('role') !== 'adopter') {
            return redirect()->to(site_url('admin/dashboard'))
                ->with('error', 'This page is for adopter accounts only.');
        }

        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        // Retrieve only the logged-in adopter's applications.
        $applications = $db->table('adoption_applications a')
            ->select('
                a.id,
                a.pet_id,
                a.application_date,
                a.status,
                a.reason,
                a.admin_notes,
                p.name AS pet_name,
                p.species AS pet_species,
                p.breed AS pet_breed,
                p.image AS pet_image,
                p.status AS pet_status
            ')
            ->join('pets p', 'p.id = a.pet_id')
            ->where('a.user_id', $userId)
            ->orderBy('a.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('applications/index', [
            'title' => 'My Applications',
            'applications' => $applications,
        ]);
    }
}
