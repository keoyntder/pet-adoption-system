<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PetModel;
class PetManagement extends BaseController
{
      private function requireAdmin()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('login'))
                ->with('error', 'Please log in first.');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to(site_url('pets'))
                ->with('error', 'You are not authorized to access that page.');
        }

        return null;
    }

    /**
     * Display all pets in the admin management page.
     */
    public function index()
    {
        if ($response = $this->requireAdmin()) {
            return $response;
        }

        $petModel = new PetModel();

        return view('admin/index', [
            'title' => 'Pet Management',
            'pets'  => $petModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    /**
     * Display the add-pet form.
     */
    public function create()
    {
        if ($response = $this->requireAdmin()) {
            return $response;
        }

        return view('admin/create', [
            'title' => 'Add New Pet',
        ]);
    }

    /**
     * Validate and save a new pet.
     */
    public function store()
    {
        if ($response = $this->requireAdmin()) {
            return $response;
        }

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'species' => 'required|max_length[50]',
            'breed' => 'permit_empty|max_length[100]',
            'sex' => 'required|in_list[Male,Female,Unknown]',
            'age' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'size' => 'permit_empty|max_length[30]',
            'color' => 'permit_empty|max_length[100]',
            'description' => 'permit_empty|max_length[5000]',
            'status' => 'required|in_list[available,pending,adopted,unavailable]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $petModel = new PetModel();

        $petModel->insert([
            'name'        => trim($this->request->getPost('name')),
            'species'     => trim($this->request->getPost('species')),
            'breed'       => trim($this->request->getPost('breed') ?? ''),
            'sex'         => $this->request->getPost('sex'),
            'age'         => $this->request->getPost('age') !== ''
                ? $this->request->getPost('age')
                : null,
            'size'        => trim($this->request->getPost('size') ?? ''),
            'color'       => trim($this->request->getPost('color') ?? ''),
            'description' => trim($this->request->getPost('description') ?? ''),
            'status'      => $this->request->getPost('status'),
        ]);

        return redirect()->to(site_url('admin/dashboard'))
            ->with('success', 'Pet added successfully.');
    }
}
