<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Admin extends Seeder
{
    public function run()
    {
		$password = password_hash('admin',PASSWORD_DEFAULT);
		$query = 'INSERT INTO admins SET admin_login = "admin", admin_password = "'.$password.'"';
        $this->db->query($query);
    }
}
