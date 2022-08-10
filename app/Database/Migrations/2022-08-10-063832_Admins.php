<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Admins extends Migration
{
    public function up()
    {
       $this->forge->addField([
	       'admin_id'=>
		       [
			       'type'              =>'INT',
			       'constraint'        => 11,
			       'unsigned'          => true,
			       'auto_increment'    => true,
		       ],
	       'admin_user_id'=>
		       [
		       	    'type'              =>'INT',
		            'constraint'        => 11,
		            'default'           => 0
	           ],
	       'admin_login'=>
		       [
		            'type'              =>'VARCHAR',
		            'constraint'        => 8,
		            'null'              => false
	            ],
	       'admin_password'=>
		       [
		       'type'              =>'TEXT',
		       'null'              => false
	            ],
	       'created_at datetime default current_timestamp',
	       'updated_at datetime default current_timestamp on update current_timestamp',
       ]);
	   $this->forge->addPrimaryKey('admin_id');
	   $this->forge->addKey('admin_user_id',false,true);
	   $this->forge->addKey('admin_login',false,true);
	   $this->forge->createTable('admins');
    }

    public function down()
    {
        $this->forge->dropTable('admins');
    }
}
