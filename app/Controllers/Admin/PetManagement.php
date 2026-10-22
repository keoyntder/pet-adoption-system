<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PetModel;

class PetManagement extends BaseController
{
    protected PetModel $petModel;

    public function __construct()
    {
        $this->petModel = new PetModel();
    }

    /**
     * Ensure that only administrators can manage pets.
     */
    private function requireAdmin()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('login'));
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to(site_url('pets'));
        }

        return null;
    }

    /**
     * Display all pets.
     */
    public function index()
    {
        $redirect = $this->requireAdmin();

        if ($redirect !== null) {
            return $redirect;
        }

        return view('admin/index', [
            'title' => 'Manage Pets',
            'pets'  => $this->petModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    /**
     * Display the add-pet form.
     */
    public function create()
    {
        $redirect = $this->requireAdmin();

        if ($redirect !== null) {
            return $redirect;
        }

        return view('admin/create', [
            'title' => 'Add New Pet',
        ]);
    }

    /**
     * Save a new pet and its optional image.
     */
    public function store()
    {
        $redirect = $this->requireAdmin();

        if ($redirect !== null) {
            return $redirect;
        }

        $rules = [
            'name'        => 'required|min_length[2]|max_length[150]',
            'species'     => 'required|max_length[100]',
            'breed'       => 'permit_empty|max_length[100]',
            'sex'         => 'required|in_list[Male,Female,Unknown]',
            'age'         => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'size'        => 'permit_empty|max_length[50]',
            'color'       => 'permit_empty|max_length[100]',
            'description' => 'permit_empty|max_length[5000]',
            'status'      => 'required|in_list[available,unavailable,adopted]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $image = $this->request->getFile('image');
        $imageName = null;

        if ($image && $image->getError() !== UPLOAD_ERR_NO_FILE) {
            $imageError = $this->validatePetImage($image);

            if ($imageError !== null) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $imageError);
            }

            $uploadPath = FCPATH . 'uploads/pets/';

            if (! is_dir($uploadPath) && ! mkdir($uploadPath, 0755, true) && ! is_dir($uploadPath)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Unable to create the pet image upload folder.');
            }

            $imageName = $image->getRandomName();

            if (! $image->move($uploadPath, $imageName)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Unable to save the uploaded image.');
            }
        }

        $data = $this->getPetData();
        $data['image'] = $imageName;

        if (! $this->petModel->insert($data)) {
            if ($imageName && is_file(FCPATH . 'uploads/pets/' . $imageName)) {
                unlink(FCPATH . 'uploads/pets/' . $imageName);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to save the pet.');
        }

        return redirect()->to(site_url('admin/pets'))
            ->with('success', 'Pet added successfully.');
    }

    /**
     * Display the edit-pet form.
     */
    public function edit($id)
    {
        $redirect = $this->requireAdmin();

        if ($redirect !== null) {
            return $redirect;
        }

        $pet = $this->petModel->find($id);

        if (! $pet) {
            return redirect()->to(site_url('admin/pets'))
                ->with('error', 'Pet not found.');
        }

        return view('admin/edit', [
            'title' => 'Edit Pet',
            'pet'   => $pet,
        ]);
    }

    /**
     * Update pet details and optionally replace its image.
     */
    public function update($id)
    {
        $redirect = $this->requireAdmin();

        if ($redirect !== null) {
            return $redirect;
        }

        $pet = $this->petModel->find($id);

        if (! $pet) {
            return redirect()->to(site_url('admin/pets'))
                ->with('error', 'Pet not found.');
        }

        $rules = [
            'name'        => 'required|min_length[2]|max_length[150]',
            'species'     => 'required|max_length[100]',
            'breed'       => 'permit_empty|max_length[100]',
            'sex'         => 'required|in_list[Male,Female,Unknown]',
            'age'         => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'size'        => 'permit_empty|max_length[50]',
            'color'       => 'permit_empty|max_length[100]',
            'description' => 'permit_empty|max_length[5000]',
            'status'      => 'required|in_list[available,unavailable,adopted]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $image = $this->request->getFile('image');
        $newImageName = null;

        if ($image && $image->getError() !== UPLOAD_ERR_NO_FILE) {
            $imageError = $this->validatePetImage($image);

            if ($imageError !== null) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $imageError);
            }

            $uploadPath = FCPATH . 'uploads/pets/';

            if (! is_dir($uploadPath) && ! mkdir($uploadPath, 0755, true) && ! is_dir($uploadPath)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Unable to create the pet image upload folder.');
            }

            $newImageName = $image->getRandomName();

            if (! $image->move($uploadPath, $newImageName)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Unable to save the uploaded image.');
            }
        }

        $data = $this->getPetData();

        // Preserve the existing image if no replacement was uploaded.
        $data['image'] = $newImageName ?? ($pet['image'] ?? null);

        if (! $this->petModel->update($id, $data)) {
            if ($newImageName && is_file(FCPATH . 'uploads/pets/' . $newImageName)) {
                unlink(FCPATH . 'uploads/pets/' . $newImageName);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to update the pet.');
        }

        // Delete the previous image only after a successful database update.
        if (
            $newImageName &&
            ! empty($pet['image']) &&
            is_file(FCPATH . 'uploads/pets/' . $pet['image'])
        ) {
            unlink(FCPATH . 'uploads/pets/' . $pet['image']);
        }

        return redirect()->to(site_url('admin/pets'))
            ->with('success', 'Pet updated successfully.');
    }

    /**
     * Delete a pet only if no applications reference it.
     */
    public function delete($id)
    {
        $redirect = $this->requireAdmin();

        if ($redirect !== null) {
            return $redirect;
        }

        $pet = $this->petModel->find($id);

        if (! $pet) {
            return redirect()->to(site_url('admin/pets'))
                ->with('error', 'Pet not found.');
        }

        $applicationCount = db_connect()
            ->table('adoption_applications')
            ->where('pet_id', $id)
            ->countAllResults();

        if ($applicationCount > 0) {
            return redirect()->to(site_url('admin/pets'))
                ->with('error', 'This pet cannot be deleted because it has adoption applications.');
        }

        if (! $this->petModel->delete($id)) {
            return redirect()->to(site_url('admin/pets'))
                ->with('error', 'Unable to delete the pet.');
        }

        if (
            ! empty($pet['image']) &&
            is_file(FCPATH . 'uploads/pets/' . $pet['image'])
        ) {
            unlink(FCPATH . 'uploads/pets/' . $pet['image']);
        }

        return redirect()->to(site_url('admin/pets'))
            ->with('success', 'Pet deleted successfully.');
    }

    /**
     * Validate an uploaded image.
     */
    private function validatePetImage($image): ?string
    {
        if (! $image->isValid()) {
            return 'The image upload failed. Please try again.';
        }

        if ($image->hasMoved()) {
            return 'This image has already been uploaded.';
        }

        if (! in_array($image->getMimeType(), [
            'image/jpeg',
            'image/png',
            'image/webp',
        ], true)) {
            return 'Only JPG, PNG, and WEBP images are allowed.';
        }

        if ($image->getSizeByUnit('mb') > 5) {
            return 'The image must not exceed 5 MB.';
        }

        return null;
    }

    /**
     * Collect and normalize pet form fields.
     */
    private function getPetData(): array
    {
        $data = [];

        foreach ([
            'name',
            'species',
            'breed',
            'sex',
            'age',
            'size',
            'color',
            'description',
            'status',
        ] as $field) {
            $value = $this->request->getPost($field);

            $data[$field] = ($value === '' || $value === null)
                ? null
                : $value;
        }

        return $data;
    }
}
