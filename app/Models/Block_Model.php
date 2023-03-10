<?php 
namespace App\Models;

use CodeIgniter\Model;

class Block_Model extends Model {

    protected $table      = 'block_list';
    protected $primaryKey = 'block_fid';

    protected $returnType = 'object'; 
    protected $allowedFields = ['block_ip', 'block_state', 'block_error', 'block_updated']; 

    
    public function getByIp($ip){

        $where = "block_ip = ".$this->db->escape($ip)." AND block_state = '1' ";

        return $this->where($where)
                    ->first(); 
    }

    
}