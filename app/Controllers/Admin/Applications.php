<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Applications extends BaseController
{
    /**
     * Ensure that the user is logged in and has administrator access.
     */
    private function requireAdmin()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(site_url('login'));
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to(site_url('pets'))
                ->with('error', 'You are not authorized to access this page.');
        }

        return null;
    }

    /**
     * Display all adoption applications for the administrator.
     */
    public function index()
    {
        $redirect = $this->requireAdmin();

        if ($redirect !== null) {
            return $redirect;
        }

        $db = db_connect();

        $applications = $db->table('adoption_applications a')
            ->select(
                'a.id,
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
                 p.breed AS pet_breed,
                 p.image AS pet_image,
                 p.status AS pet_status'
            )
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

    /**
     * Approve or reject a pending adoption application.
     */
    public function update($id)
    {
        $redirect = $this->requireAdmin();

        if ($redirect !== null) {
            return $redirect;
        }

        $returnUrl = site_url('admin/applications');

        $newStatus = $this->request->getPost('status');
        $adminNotes = trim(
            (string) $this->request->getPost('admin_notes')
        );

        if (! in_array($newStatus, ['approved', 'rejected'], true)) {
            return redirect()->to($returnUrl)
                ->with('error', 'Invalid application status.');
        }

        if (mb_strlen($adminNotes) > 2000) {
            return redirect()->to($returnUrl)
                ->with('error', 'Admin notes cannot exceed 2000 characters.');
        }

        $db = db_connect();

        $db->transBegin();

        try {
            // Lock the application while reviewing it.
            $application = $db->query(
                'SELECT *
                 FROM adoption_applications
                 WHERE id = ?
                 FOR UPDATE',
                [$id]
            )->getRowArray();

            if (! $application) {
                $db->transRollback();

                return redirect()->to($returnUrl)
                    ->with('error', 'Application not found.');
            }

            if ($application['status'] !== 'pending') {
                $db->transRollback();

                return redirect()->to($returnUrl)
                    ->with('error', 'This application has already been reviewed.');
            }

            if ($newStatus === 'approved') {
                // Lock the pet to prevent conflicting approvals.
                $pet = $db->query(
                    'SELECT id, status
                     FROM pets
                     WHERE id = ?
                     FOR UPDATE',
                    [$application['pet_id']]
                )->getRowArray();

                if (! $pet || $pet['status'] !== 'available') {
                    $db->transRollback();

                    return redirect()->to($returnUrl)
                        ->with('error', 'This pet is no longer available for adoption.');
                }

                // Mark the pet as adopted.
                $db->table('pets')
                    ->where('id', $application['pet_id'])
                    ->update([
                        'status' => 'adopted',
                    ]);

                // Reject other pending applications for the same pet.
                $db->table('adoption_applications')
                    ->where('pet_id', $application['pet_id'])
                    ->where('id !=', $id)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'rejected',
                        'admin_notes' => 'Automatically rejected because another adoption application was approved.',
                    ]);
            }

            // Update the application being reviewed.
            $db->table('adoption_applications')
                ->where('id', $id)
                ->update([
                    'status' => $newStatus,
                    'admin_notes' => $adminNotes !== ''
                        ? $adminNotes
                        : null,
                ]);

            if ($db->transStatus() === false) {
                throw new DatabaseException(
                    'The application update failed.'
                );
            }

            $db->transCommit();

            return redirect()->to($returnUrl)
                ->with(
                    'success',
                    'Application ' . $newStatus . ' successfully.'
                );

        } catch (\Throwable $e) {
            $db->transRollback();

            log_message(
                'error',
                'Application review failed: ' . $e->getMessage()
            );

            return redirect()->to($returnUrl)
                ->with(
                    'error',
                    'Unable to update the application. Check the application logs.'
                );
        }
    }
}
