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
    public function login_auth(): Response
    {
      if(empty($_POST["username"]) || empty($_POST["password"])) {
        return new Response('<h3>You must provide a username and a password in your POST request to login.</h3>');
      } else {
        // Both fields passed through - now authenticate
        $entityManager = $this->getDoctrine()->getRepository(User::class);
        $user = $entityManager->findOneBy([
          'username' => $_POST["username"],
          'password' => $_POST["password"],
        ]);

        if (!$user) {
          return new Response(
            'No user found for username: '.$_POST["username"].' and password: '.$_POST["password"].'.'
          );
        } else {
          return new Response(
            'Logged in! Your ID is: '.$user->getId().' and your lucky number is: '.$user->getLuckyNumber().'.'
          );
        }
      }
    }

    public function login()
    {
      return $this->render('login/login.html.twig');
    }

    public function allUsers()
    {
        // Display all users
        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository(User::class)->findAll();

        // Display Results on twig
        return $this->render('user/users.html.twig', array('users'=>$users));
    }

    public function createUser(): Response
    {
        // Get data passed in through POST Request

        // Mandatory params
        if(empty($_POST["username"]) || empty($_POST["password"])) {
          return new Response('<h3>You must provide at least a username and a password in your POST request.</h3>');
        } else {
          $username = $_POST["username"];
          $password = $_POST["password"]; // In reality, this would be encrypted but it isn't for demo
          $email_address = $_POST["email_address"] ?? 'null';
          $lucky_number = $_POST["lucky_number"] ?? 0;

          $user = new User();
          $user->setUsername($username);
          $user->setPassword($password);
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
            'userid' => $user->getId(),
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
            'email_address' => $user->getEmailAddress(),
            'lucky_number' => $user->getLuckyNumber(),
        ]);
    }
}
?>
