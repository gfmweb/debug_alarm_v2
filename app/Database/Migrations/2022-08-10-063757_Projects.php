<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Projects extends Migration
{
    public function up()
    {
        $this->forge->addField([
			
			'project_id'=>
			[
					'type'              =>'INT',
					'constraint'        => 11,
					'unsigned'          => true,
					'auto_increment'    => true,
			],
	        'project_name'=>
		    [
					'type'                  =>'VARCHAR',
			        'constraint'            => 32,
			        'null'                  => false
	        ],
	        'project_secret'=>
		    [
			        'type'                  =>'VARCHAR',
			        'null'                  => false,
			        'constraint'            => 64,
	        ],
	       
	        'created_at datetime default current_timestamp',
	        'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
		$this->forge->addPrimaryKey('project_id');
		$this->forge->addKey('project_name',false,true);
		$this->forge->addKey('project_secret',false,false);
		$this->forge->createTable('projects');
		
    }

    public function down()
    {
        $this->forge->dropTable('projects');
    }
}
