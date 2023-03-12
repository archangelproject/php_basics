<?php
session_start();
require_once('db_manager.php');
require_once('session_manager.php');
$db_manager = new DbManager();
$session_manager = new SessionManager();

$errors = [];

// Function to store session variables
function store_session($name, $surname, $email, $username) {
    $_SESSION['name'] = $name;
    $_SESSION['surname'] = $surname;
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;
}
// End of function

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password-confirm'];

    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    if (empty($surname)) {
        $errors[] = 'Surname is required';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is not in a valid format';
    }

    if (empty($username)) {
        $errors[] = 'Username is required';
    }

    if (empty($password)) {
        $errors[] = 'Password is required';
    }

    if ($password !== $password_confirm) {
        $errors[] = 'Passwords do not match';
    }

    if (empty($errors)) {
        // Save the user to the database and redirect to a success page
        // Check if the username already exists in the database
        require_once('config.php');

        $pdo = $db_manager->get_db_connection();
	if ($db_manager->check_duplicate(USERS_FIELD_MAP['email'], $email, $pdo) == false) {
	    // Mail not duplicated
	    if ($db_manager->check_duplicate(USERS_FIELD_MAP['username'], $username, $pdo) == false) {
		// User not duplicated
		// Store new user here
		$user_id = $db_manager->create_user($name, $surname, $email, $username, $password, $pdo);
                $user = $db_manager->get_user($user_id, $pdo);
                if ($user != null) {
                    $session_manager->login($user);
                }
                $_SESSION[TAG_USER_EDIT_BEHAVIOUR] = TAG_USER_EDIT_BEHAVIOUR_CREATION;
		header('Location: '. PAGE_USER_INFO);
		exit;
	    }
	    else {
		// User duplicated
		store_session($name, $surname, $email, $username);
		$errors[] = 'User already exists';
	    }
	}
	else {
	    // Email duplicated
	    store_session($name, $surname, $email, $username);
	    $errors[] = 'Email already exists';
	}
    }
    else{
        // Store the submitted data in session for repopulating the form
        store_session($name, $surname, $email, $username);
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
  <title>The Last Time - Sign Up</title>
</head>
<body>
  <?php include 'header.php';?>
  <main class="d-flex align-items-center">
    <div class="container">
      <h1 class="text-center">Sign Up</h1>
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
          <?php foreach ($errors as $error): ?>
            <div><?php echo $error; ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <form action="<?php require_once('config.php'); echo PAGE_SIGN_UP; ?>" method="post">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>"  required>
        </div>
        <div class="form-group">
          <label for="surname">Surname</label>
          <input type="text" class="form-control" id="surname" name="surname" value="<?php echo isset($_SESSION['name']) ? $_SESSION['surname'] : ''; ?>"  required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_SESSION['name']) ? $_SESSION['email'] : ''; ?>"  required>
        </div>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_SESSION['name']) ? $_SESSION['username'] : ''; ?>"  required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
          <label for="password-confirm">Confirm Password</label>
          <input type="password" class="form-control" id="password-confirm" name="password-confirm" required>
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
      </form>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6TmGk1CfoIZyhku6" crossorigin="anonymous"></script>

</body>
</html>

<?php
// Clear session data after displaying errors or on successful form submission
unset($_SESSION['name']);
unset($_SESSION['surname']);
unset($_SESSION['email']);
unset($_SESSION['username']);
?>
