<?php
// src/Controller/UserController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    public function getCSS()
    {
      // Some logic in here is possible to determine what CSS to use. Front end team then just have to include css
      $css = "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css";
      return $css;
    }


    public function display()
    {
      if (empty($_POST['user_id'])) {
          $userID = $_GET['user_id'];
      } else {
          $userID = $_POST['user_id'];
      }

      $mysqli = new \mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
      if($mysqli->connect_error) {
        exit('Error connecting to database');
      }
      $mysqli->set_charset("utf8mb4");

      // Build the query, in this case joining FILE table to the MIMES table to get Content Type data to display correctly
      $stmt = $mysqli->prepare("SELECT * FROM users WHERE user_id = ? LIMIT 1");
      $stmt->bind_param("i", $userID);
      $stmt->execute();

      $stmt->store_result();
      if($stmt->num_rows === 0) {
        exit('User not found.');
      } else {
        $stmt->bind_result($user_id, $username, $email_address, $lucky_number);
        $stmt->fetch();
      }
      $stmt->close();

      return $this->render('user/user.html.twig', [
          'css' => $this->getCSS(),
          'userid' => $user_id,
          'username' => $username,
          'email_address' => $email_address,
          'lucky_number' => $lucky_number,
      ]);
    }
}
?>
