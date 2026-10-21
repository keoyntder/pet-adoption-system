<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePetsTable extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'species' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'breed' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'sex' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'age' => [
                'type' => 'INT',
                'null' => true,
            ],
            'size' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'color' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'available',
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
        $this->forge->createTable('pets');
    }

    public function down()
    {
        $this->forge->dropTable('pets', true);
    }
}
