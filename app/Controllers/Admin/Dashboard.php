<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PetModel;
use App\Models\UserModel;
use App\Models\AdoptionApplicationModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('login'));
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to(site_url('pets'));
        }

        $petModel = new PetModel();
        $userModel = new UserModel();
        $applicationModel = new AdoptionApplicationModel();

        return view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'totalPets' => $petModel->countAllResults(),
            'totalUsers' => $userModel->countAllResults(),
            'totalApplications' => $applicationModel->countAllResults(),
            'pendingApplications' => $applicationModel
                ->where('status', 'pending')
                ->countAllResults(),
        ]);
    }
}
