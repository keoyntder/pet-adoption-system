<?php

namespace App\Controllers;

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

        $pets = $petModel
            ->where('status', 'available')
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('pets/index', [
            'title' => 'Available Pets',
            'pets'  => $pets,
        ]);
    }

    public function show($id = null)
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('login'));
        }

        $pet = (new PetModel())->find($id);

        if (! $pet) {
            return redirect()->to(site_url('pets'))
                ->with('error', 'Pet not found.');
        }

        $existingApplication = (new AdoptionApplicationModel())
            ->where('user_id', session()->get('user_id'))
            ->where('pet_id', $id)
            ->first();

        return view('pets/show', [
            'title'               => $pet['name'],
            'pet'                 => $pet,
            'existingApplication' => $existingApplication,
        ]);
    }

    public function apply($id = null)
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('login'));
        }

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
                ->with('error', 'This pet is no longer available.');
        }

        $applicationModel = new AdoptionApplicationModel();

        $existingApplication = $applicationModel
            ->where('user_id', session()->get('user_id'))
            ->where('pet_id', $id)
            ->first();

        if ($existingApplication) {
            return redirect()->to(site_url('pets/' . $id))
                ->with('error', 'You have already applied for this pet.');
        }

        $rules = [
            'reason' => 'required|min_length[20]|max_length[2000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $applicationModel->insert([
            'user_id'          => session()->get('user_id'),
            'pet_id'           => $id,
            'application_date' => date('Y-m-d'),
            'status'           => 'pending',
            'reason'           => trim($this->request->getPost('reason')),
        ]);

        return redirect()->to(site_url('pets/' . $id))
            ->with('success', 'Your adoption application has been submitted successfully.');
    }
}
