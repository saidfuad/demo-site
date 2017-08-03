<?php

class Mdl_accounting extends CI_Model{

    function __construct () {
        parent::__construct();
    }

    public function fetch_expenses($account_id){
        $this->db->select('accounting.accounting_id, accounting.amount, accounting.description,expense_types.name,vehicles.plate_no,vehicles.model,CAST(accounting.add_date AS DATE) as add_date');
        $this->db->from('accounting');
        $this->db->join('expense_types', 'expense_types.expense_type_id = accounting.expense_type_id');
        $this->db->join('vehicles', 'vehicles.vehicle_id = accounting.vehicle_id');
        $this->db->where('accounting.account_id',$account_id);



        $this->db->order_by('accounting.add_date', 'desc');

        $query = $this->db->get();

        return $query->result();
    }

    public function pickextype(){
        $this->db->select('expense_types.*');
        $this->db->from('expense_types');
        

        $query = $this->db->get();

        return $query->result();
    }

    public function fetch_expense($id, $vehicle_id = null){
        $this->db->select('accounting.*,expense_types.name,vehicles.plate_no');
        $this->db->from('accounting');
        $this->db->join('expense_types', 'expense_types.expense_type_id = accounting.expense_type_id');
        $this->db->join('vehicles', 'vehicles.vehicle_id = accounting.vehicle_id');
        $this->db->where('accounting.accounting_id',$id);
        
        if($vehicle_id != null)
           $this->db->where('accounting.vehicle_id',$this->db->where('accounting.accounting_id',$id));


        $query = $this->db->get();

        return $query->row_array();

    }

    

    public function pickvehicles ($hawk_account_id=null) {
        $this->db->where('account_id',$hawk_account_id);
        $query = $this->db->get('vehicles');

        return $query->result();

    }

    public function save_expense($data){
        $query = $this->db->insert('accounting', $data);

        if ($query) {
            return true;
        }
        
        return false;
    }

    public function update_expense($data){
        $this->db->where('accounting_id',$data['accounting_id']);
        $query = $this->db->update('accounting', $data);

        if ($query) {
            return true;
        }
        
        return false;
    }

}

?>
