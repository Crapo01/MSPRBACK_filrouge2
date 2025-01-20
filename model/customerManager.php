<?php
require_once('dataBase.php');
class CustomerManager {
  private $db;

  public function __construct(){
    //$this->db = new PDO('mysql:host=localhost;dbname=bibliotheque_PHP', 'root', '');
    $temp= new Database;
    $this->db = $temp->getDb1();
    
  }

  // Récupère tous les customers
  public function getCustomers() {
    $query = $this->db->query(
      "SELECT id, first_name, second_name, address, permit_number	

      FROM customer" 
    );
    
    $customers = $query->fetchAll(PDO::FETCH_ASSOC);
    

    return $customers;
  }

  public function getCustomersByNames($f,$s) {
    $query = $this->db->query(
      "SELECT * 
        FROM Customer
        WHERE LOWER(first_name) LIKE LOWER('%$f%')
        AND LOWER(second_name) LIKE LOWER('%$s%');" 
    );
    
    $customers = $query->fetchAll(PDO::FETCH_ASSOC);
    

    return $customers;
  }

  // Ajoute un nouvel utilisateur avec transaction sql
  public function addCustomer(Customer $c) {
    try{
      $this->db->beginTransaction();
        $query = $this->db->prepare(
        "INSERT INTO customer(`first_name`, `second_name`, `address`, `permit_number`)
        VALUES(:fname, :sname, :address, :permit)"
      );

      $result = $query->execute([
        "fname" => htmlspecialchars($c->getFirstName()),
        "sname" => htmlspecialchars($c->getSecondName()),
        "address" => htmlspecialchars($c->getAddress()),
        "permit" => htmlspecialchars($c->getPermitNumber())        
      ]);
      $this->db->commit();  
      return $result;
    }
    catch (\Exception $e){
      $this->db->rollBack();
      }
  }

  public function editCustomer(Customer $c,int $id) {
    
    
    try{
      $this->db->beginTransaction();
        $query = $this->db->prepare(
        "UPDATE Customer
        SET 
        first_name = :fname,
        second_name = :sname,
        address = :address,
        permit_number = :permit
        WHERE id = $id;"
      );

      $result = $query->execute([
        
        "fname" => htmlspecialchars($c->getFirstName()),
        "sname" => htmlspecialchars($c->getSecondName()),
        "address" => htmlspecialchars($c->getAddress()),
        "permit" => htmlspecialchars($c->getPermitNumber())        
      ]);
      $this->db->commit();  
      return $result;
    }
    catch (\Exception $e){
      $this->db->rollBack();
      }
  }

  public function deleteCustomer(int $id) {
    try{
      $this->db->beginTransaction();
        $query = $this->db->prepare(
        "DELETE FROM customer WHERE id = :id"
      );

      $result = $query->execute([
        
        "id" => $id       
      ]);
      $this->db->commit();  
      return $result;
    }
    catch (\Exception $e){
      $this->db->rollBack();
      echo $e;
      }
  } 
}