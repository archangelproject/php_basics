<?php
namespace Aero\ArchangelProject\PhpBasics\DbManager;

class DbManager {

    public function __construct() {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    // Function to get a database connection
    public function get_db_connection() {
        require_once('config.php');
        try {
            $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
        return $pdo;
    }

    // Function to get a user by the user id
    public function get_user($user_id, $pdo) {
        //TODO: User id must be sanitized
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :value");
        $stmt->bindParam(':value', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Function to check that the specified user or email does not already exist
    function check_duplicate($field, $value, $pdo) {
    	// Sanitizinf $field
        if (!in_array($field,USERS_FIELD_MAP)) {
    	    throw new Exception('ERROR: Invalid field ("' . $field . '") on checking duplicates');
        }

        $value = trim(strtolower($value));
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE $field = :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return ($count > 0);
    }
    // End of function

    // Function create new user
    function create_user($name, $surname, $email, $username, $password, $pdo) {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO users (name, surname, username, email, password) VALUES (:name, :surname, :username, :email, :password)");

        // Bind the parameters to the statement
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hash);

        // Execute the statement to insert the user into the database
        $stmt->execute();

        // Return the id of the created user
        return $pdo->lastInsertId();
    }
    // End of function

    // Function to edit user information (not password)
    function update_user($user_id, $name, $surname, $email, $pdo) {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("UPDATE users SET name = :name, surname = :surname, email = :email WHERE user_id = :user_id");

        // Bind the parameters to the statement
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_id', $user_id);

        // Execute the statement to update the user in the database
        return $stmt->execute();
     }
     // End of function
}
?>
