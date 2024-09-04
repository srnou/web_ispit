<?php

class MidtermDao
{

  private $conn;
  private $table;

  /**
   * constructor of dao class
   */
  public function __construct()
  {
    try {
      /** TODO
       * List parameters such as servername, username, password, schema. Make sure to use appropriate port
       */
      $servername = 'localhost';
      $dbUsername = 'root';
      $dbPassword = 'rootpw';
      $database = 'midterm2';
      $port = '3306';

       /*options array neccessary to enable ssl mode - do not change
       $options = array(
        PDO::MYSQL_ATTR_SSL_CA => 'https://drive.google.com/file/d/1g3sZDXiWK8HcPuRhS0nNeoUlOVSWdMAg/view?usp=share_link',
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,

      ); */

      /** TODO
       * Create new connection
       */
      $this->conn = new PDO("mysql:host=$servername;port=$port;dbname=$database", $dbUsername, $dbPassword);

      // set the PDO error mode to exception
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

 /* protected function query($query, $params = [])
  {
    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);
    return $stmt;
  } */

  /** TODO
   * Implement DAO method used to get cap table
   */
  public function cap_table()
  {
    $query ="SELECT sc.description AS class,
              GROUP_CONCAT(scc.description) AS category,
              CONCAT(inv.first_name, ' ', inv.last_name) AS investor,
              SUM(ct.diluted_shares) AS diluted_shares
              FROM cap_table ct
              JOIN share_classes sc ON ct.share_class_id = sc.id
              JOIN share_class_categories scc ON ct.share_class_category_id = scc.id
              JOIN investors inv ON ct.investor_id = inv.id
              GROUP BY class, investor";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
  }

  /** TODO
   * Implement DAO method used to get summary
   */
  public function summary()
  {
    $query = "SELECT COUNT(distinct ct.investor_id) AS total_investitors, SUM(ct.diluted_shares) AS total_diluted_shares
    FROM cap_table ct;";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
  }

  /** TODO
   * Implement DAO method to return list of investors with their total shares amount
   */
  public function investors()
  {
    $query = "SELECT i.first_name AS first_name, i.last_name AS last_name, i.company AS company, SUM(ct.diluted_shares) as total_shares
              FROM investors i
              JOIN cap_table ct on i.id = ct.investor_id
              GROUP BY i.id;";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
  }
}
