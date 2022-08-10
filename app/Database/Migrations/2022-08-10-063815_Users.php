<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
			'user_id'=>
			[
				'type'              =>'INT',
				'constraint'        => 11,
				'unsigned'          => true,
				'auto_increment'    => true,
			],
	        'user_telegram_id'=>[
		        'type'              =>'INT',
		        'constraint'        => 11,
		        'null'              => true,
	        ],
	        'user_name'=>[
		        'type'              =>'VARCHAR',
		        'constraint'        => 16,
		        'null'              => false
	        ],
			'user_login'=>[
				'type'              =>'VARCHAR',
				'constraint'        => 8,
				'null'              => false
			],
			'user_password'=>[
				'type'              =>'TEXT',
				'null'              => false
			],
	        'created_at datetime default current_timestamp',
	        'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
		$this->forge->addPrimaryKey('user_id');
		$this->forge->addKey('user_telegram_id',false,true);
		$this->forge->addKey('user_login',false,true);
		$this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
