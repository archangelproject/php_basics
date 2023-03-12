<?php
session_start();
require_once('session_manager.php');
require_once('config.php');
$errors = [];
$session_manager = new SessionManager();

// Function to get the user information
function get_user($identifier, $pdo) {
    $username = trim(strtolower($identifier));
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :identifier OR email = :identifier");
    $stmt->bindParam(':identifier', $identifier);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return null;
    }

    return $user;
}
// End of function

// Function to check user credentials
function check_credentials($user, $password) {
    return password_verify($password, $user['password']);
}
// End of function

// Function to store session variables
function store_session($name, $surname, $email, $username) {
    $_SESSION['name'] = $name;
    $_SESSION['surname'] = $surname;
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;
}
// End of function

if (isset($_POST['submit'])) {
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];

    if (empty($identifier)) {
        $errors[] = 'Username is required';
    }

    if (empty($password)) {
        $errors[] = 'Password is required';
    }

    if (empty($errors)) {
        try {
            $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }

        $user = get_user($identifier, $pdo);
        if ($user && check_credentials($user, $password)) {
            // Store the user information in the session
	    $session_manager->set_user($user);

            header('Location: '. PAGE_MAIN);
            exit;
        } else {
            $errors[] = 'Username/email or password is incorrect';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="styles.css">
  <title>The Last Time - Login</title>
</head>
<body>
  <?php include 'header.php';?>
  <main class="d-flex align-items-center">
    <div class="container">
      <h1 class="text-center">Login</h1>
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
          <?php foreach ($errors as $error): ?>
            <div><?php echo $error; ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <form action="login.php" method="post">
        <div class="form-group">
          <label for="identifier">Email/Username</label>
          <!-- <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>-->
          <input type="text" class="form-control" id="identifier" name="identifier" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Login</button>
        <button type="button" class="btn btn-secondary" name="cancel">Cancel</button>
      </form>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOY
