<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PetModel;
use App\Models\AdoptionApplicationModel;
class Pets extends BaseController
{
     public function index()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('login'));
        }

        $petModel = new PetModel();

        return view('pets/index', [
            'title' => 'Available Pets',
            'pets'  => $petModel
                ->where('status', 'available')
                ->orderBy('id', 'DESC')
                ->findAll(),
        ]);
    }

    /**
     * Display the details of one pet.
     */
    public function show($id = null)
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('login'));
        }

        $petModel = new PetModel();
        $pet = $petModel->find($id);

        if (! $pet) {
            return redirect()->to(site_url('pets'))
                ->with('error', 'Pet not found.');
        }

        $applicationModel = new AdoptionApplicationModel();

        $existingApplication = $applicationModel
            ->where('user_id', session()->get('user_id'))
            ->where('pet_id', $id)
            ->first();

        return view('pets/show', [
            'title'               => $pet['name'],
            'pet'                 => $pet,
            'existingApplication' => $existingApplication,
        ]);
    }

    /**
     * Submit an adoption application.
     */
    public function apply($id = null)
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('login'));
        }

        // Only adopter accounts can submit applications.
        if (session()->get('role') !== 'adopter') {
            return redirect()->to(site_url('admin/dashboard'))
                ->with('error', 'Only adopter accounts can apply for pets.');
        }

        $petModel = new PetModel();
        $pet = $petModel->find($id);

        if (! $pet) {
            return redirect()->to(site_url('pets'))
                ->with('error', 'Pet not found.');
        }

        if ($pet['status'] !== 'available') {
            return redirect()->to(site_url('pets/' . $id))
                ->with('error', 'This pet is no longer available for adoption.');
        }

        $applicationModel = new AdoptionApplicationModel();

        // Prevent the same user from applying for the same pet twice.
        $existingApplication = $applicationModel
            ->where('user_id', session()->get('user_id'))
            ->where('pet_id', $id)
            ->first();

        if ($existingApplication) {
            return redirect()->to(site_url('pets/' . $id))
                ->with('error', 'You have already submitted an application for this pet.');
        }

        $rules = [
            'reason' => [
                'rules'  => 'required|min_length[20]|max_length[2000]',
                'errors' => [
                    'required'   => 'Please explain why you want to adopt this pet.',
                    'min_length' => 'Your explanation must be at least 20 characters.',
                    'max_length' => 'Your explanation cannot exceed 2000 characters.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $applicationModel->insert([
            'user_id'         => session()->get('user_id'),
            'pet_id'          => $id,
            'application_date' => date('Y-m-d'),
            'status'          => 'pending',
            'reason'          => trim($this->request->getPost('reason')),
        ]);

        return redirect()->to(site_url('pets/' . $id))
            ->with('success', 'Your adoption application has been submitted successfully.');
    }
}