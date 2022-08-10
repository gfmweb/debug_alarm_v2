<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Logs extends Migration
{
    public function up()
    {
        $this->forge->addField([
			'log_id'=>
			[
				'type'              => 'INT',
				'constraint'        =>  11,
				'unsigned'          =>  true,
				'auto_increment'    =>  true,
			],
	        'log_project_id'=>
	        [
		        'type'              => 'INT',
		        'constraint'        =>  11,
		        'unsigned'          =>  true,
		        'null'              =>  false,
	        ],
	        'log_row_data'=>
		    [
		        'type'                 => 'TEXT',
		        'null'                 =>  false,
	        ],
	        'log_structured_data'=>
	        [
		        'type'                 => 'JSON',
		        'null'                 =>  false,
	        ],
	        'created_at datetime default current_timestamp',
	        'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
		$this->forge->addPrimaryKey('log_id');
		$this->forge->addKey('log_project_id',false,false);
		$this->forge->createTable('logs');
    }

    public function down()
    {
        $this->forge->dropTable('logs');
    }
}
