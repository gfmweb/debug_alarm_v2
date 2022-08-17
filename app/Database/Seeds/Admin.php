<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Admin extends Seeder
{
    public function run()
    {
		$login = 'admin';
		$password = password_hash('admin',PASSWORD_DEFAULT);
		
		$query = 'INSERT INTO admins SET admin_login = "'.$login.'", admin_password = "'.$password.'",admin_user_id = 1';
        $this->db->query($query);
	 
	    $query = 'INSERT INTO users SET  user_name = "'.$login.'", user_login="'.$login.'", user_password = "'.$password.'"';
	    $this->db->query($query);
		echo file_get_contents('https://api.telegram.org/bot'.TELEGRAM.'/setWebhook?url=https://'.$_SERVER['SERVER_NAME'].'/hook').'<br/> WebHooks was set by default!';
    }
}
