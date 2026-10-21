<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdoptionApplicationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'pet_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'application_date' => [
                'type' => 'DATETIME',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'pending',
            ],
            'reason' => [
                'type' => 'TEXT',
            ],
            'admin_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('pet_id');

        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'pet_id',
            'pets',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('adoption_applications');
    }

    public function down()
    {
        $this->forge->dropTable('adoption_applications', true);
    }
}
