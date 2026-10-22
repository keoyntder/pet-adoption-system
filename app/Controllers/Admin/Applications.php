<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;
class Applications extends BaseController
{
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

    public function index()
    {
        $redirect = $this->requireAdmin();

        if ($redirect !== null) {
            return $redirect;
        }

        $db = db_connect();

        $applications = $db->table('adoption_applications a')
            ->select(
                'a.id, a.user_id, a.pet_id, a.application_date,
                 a.status, a.reason, a.admin_notes,
                 u.full_name AS adopter_name,
                 u.email AS adopter_email,
                 u.phone AS adopter_phone,
                 p.name AS pet_name,
                 p.species AS pet_species,
                 p.breed AS pet_breed,
                 p.status AS pet_status'
            )
            ->join('users u', 'u.id = a.user_id')
            ->join('pets p', 'p.id = a.pet_id')
            ->orderBy('a.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/applications/index', [
            'title' => 'Adoption Applications',
            'applications' => $applications,
        ]);
    }

    public function update($id)
    {
        $redirect = $this->requireAdmin();

        if ($redirect !== null) {
            return $redirect;
        }

        $newStatus = $this->request->getPost('status');
        $adminNotes = trim((string) $this->request->getPost('admin_notes'));

        if (! in_array($newStatus, ['approved', 'rejected'], true)) {
            return redirect()->to(site_url('admin/applications'))
                ->with('error', 'Invalid application status.');
        }

        if (mb_strlen($adminNotes) > 2000) {
            return redirect()->to(site_url('admin/applications'))
                ->with('error', 'Admin notes cannot exceed 2000 characters.');
        }

        $db = db_connect();

        $db->transBegin();

        try {
            // Lock the application while it is being reviewed.
            $application = $db->query(
                'SELECT * FROM adoption_applications WHERE id = ? FOR UPDATE',
                [$id]
            )->getRowArray();

            if (! $application) {
                $db->transRollback();

                return redirect()->to(site_url('admin/applications'))
                    ->with('error', 'Application not found.');
            }

            if ($application['status'] !== 'pending') {
                $db->transRollback();

                return redirect()->to(site_url('admin/applications'))
                    ->with('error', 'This application has already been reviewed.');
            }

            if ($newStatus === 'approved') {
                // Lock the pet so it cannot be approved for multiple adopters.
                $pet = $db->query(
                    'SELECT id, status FROM pets WHERE id = ? FOR UPDATE',
                    [$application['pet_id']]
                )->getRowArray();

                if (! $pet || $pet['status'] !== 'available') {
                    $db->transRollback();

                    return redirect()->to(site_url('admin/applications'))
                        ->with('error', 'This pet is no longer available for adoption.');
                }

                $db->table('pets')
                    ->where('id', $application['pet_id'])
                    ->update(['status' => 'adopted']);
            }

            $db->table('adoption_applications')
                ->where('id', $id)
                ->update([
                    'status' => $newStatus,
                    'admin_notes' => $adminNotes !== '' ? $adminNotes : null,
                ]);

            if ($db->transStatus() === false) {
                throw new DatabaseException('The application update failed.');
            }

            $db->transCommit();

            return redirect()->to(site_url('admin/applications'))
                ->with(
                    'success',
                    'Application ' . $newStatus . ' successfully.'
                );
        } catch (\Throwable $e) {
            $db->transRollback();

            log_message('error', 'Application review failed: ' . $e->getMessage());

            return redirect()->to(site_url('admin/applications'))
                ->with('error', 'Unable to update the application. Check your logs and database status values.');
        }
    }
}
