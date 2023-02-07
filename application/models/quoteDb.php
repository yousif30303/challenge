<?php
class quoteDb extends CI_Model
{
 function can_login($password)
 {
  $this->db->where('password', $password);
  $query = $this->db->get('challenge');
  if($query->num_rows() > 0)
  {
   return true;
  }
  else
  {
   return false;
  }
 }

 function getting_quotes(){
  $this->db->where('id', 1);
  $query = $this->db->get('quotes');
  if($query->num_rows() > 0)
    {
    foreach($query->result() as $row)
    {
      return $row->quote;
    }
  }
 }

 function geting_userid($password)
 {
  $this->db->where('password', $password);
  $query = $this->db->get('challenge');
  if($query->num_rows() > 0)
  {
   foreach($query->result() as $row)
   {
     return $row->user_id;
   }
 }
 else
  {
        return false;

  }
}

function insert_token($password,$token){
  $this->db->where('password', $password);
  $query = $this->db->get('challenge');
  if($query->num_rows() > 0)
  {
    $this->db->set('token', $token);
    $this->db->where('password', $password);
    $this->db->update('challenge1');

    return true;
  }
  else
  {
   return false;
  }
 }

 


}

?>