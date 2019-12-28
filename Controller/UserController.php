<?php
// src/Controller/UserController.php
namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    public function getCSS()
    {
      // Some logic in here is possible to determine what CSS to use. Front end team then just have to include css
      $css = "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css";
      return $css;
    }

    public function allUsers()
    {
        // Display all users
        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository(User::class)->findAll();

        // Display Results on twig
        /*
        return $this->render('user/users.html.twig', [
            'css' => $this->getCSS(),
            array('users'=>$users),
        ]);
        */
        return $this->render('user/users.html.twig', array('users'=>$users));
    }

    public function createUser(): Response
    {
        // Get data passed in through POST Request

        // Mandatory params
        if(empty($_POST["username"])) {
          return new Response('<h3>You must provide at least a username in your POST request.</h3>');
        } else {
          $username = $_POST["username"];
          $email_address = $_POST["email_address"] ?? 'null';
          $lucky_number = $_POST["lucky_number"] ?? 0;

          $user = new User();
          $user->setUsername($username);
          $user->setEmailAddress($email_address);
          $user->setLuckyNumber($lucky_number);

          // Create entity manager instance
          $entityManager = $this->getDoctrine()->getManager();

          // Tell Doctrine I want to save this data
          $entityManager->persist($user);

          // Execute all stored queries in persist
          $entityManager->flush();

          return new Response(
            '<h3>Saved new user with id '.$user->getId().'</h3>
            <h4>View here: <a href="/user/'.$user->getId().'">click me</a>'
          );
        }
    }

    public function showUser($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        return $this->render('user/user.html.twig', [
            'css' => $this->getCSS(),
            'userid' => $user->getId(),
            'username' => $user->getUsername(),
            'email_address' => $user->getEmailAddress(),
            'lucky_number' => $user->getLuckyNumber(),
        ]);
    }
}
?>
