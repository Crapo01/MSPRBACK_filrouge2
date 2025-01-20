<?php
require_once('dataBase.php');
class VehicleManager {
  private $db;

  public function __construct(){
    //$this->db = new PDO('mysql:host=localhost;dbname=bibliotheque_PHP', 'root', '');
    $temp= new Database;
    $this->db = $temp->getDb1();
    
  }

  // Récupère tous les customers
  public function getVehicles() {
    $query = $this->db->query(
      "SELECT id, licence_plate , informations, km 	

      FROM vehicle" 
    );
    
    $vehicles = $query->fetchAll(PDO::FETCH_ASSOC);
    

    return $vehicles;
  }

  public function getVehicleByPlates($p) {
    $query = $this->db->query(
      "SELECT * 
        FROM Vehicle
        WHERE licence_plate LIKE '%$p%'
    " 
    );
    
    $vehicles = $query->fetchAll(PDO::FETCH_ASSOC);
    

    return $vehicles;
  }

  // Ajoute un nouvel utilisateur avec transaction sql
  public function addVehicle(Vehicle $v) {
    try{
      $this->db->beginTransaction();
        $query = $this->db->prepare(
        "INSERT INTO vehicle(`licence_plate`, `informations`, `km`)
        VALUES(:plate, :info, :km)"
      );

      $result = $query->execute([
        
        "plate" => htmlspecialchars($v->getLicencePlate()),
        "info" => htmlspecialchars($v->getInformations()),
        "km" => htmlspecialchars($v->getKm())        
      ]);
      $this->db->commit();  
      return $result;
    }
    catch (\Exception $e){
      $this->db->rollBack();
      echo $e;
      }
  }

  public function editVehicle(Vehicle $v,int $id) {
    
    
    try{
      $this->db->beginTransaction();
        $query = $this->db->prepare(
        "UPDATE Vehicle
        SET 
        licence_plate = :plate,
        informations = :info,
        km = :km
        WHERE id = $id;"
      );

      $result = $query->execute([
        
        "plate" => htmlspecialchars($v->getLicencePlate()),
        "info" => htmlspecialchars($v->getInformations()),
        "km" => htmlspecialchars($v->getKm())        
      ]);
      $this->db->commit();  
      return $result;
    }
    catch (\Exception $e){
      $this->db->rollBack();
      echo $e;
      }
  }

  public function deleteVehicle(int $id) {
    try{
      $this->db->beginTransaction();
        $query = $this->db->prepare(
        "DELETE FROM Vehicle WHERE id = :id"
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