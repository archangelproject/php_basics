<?php 
session_start();
$errors = [];

require_once('config.php');
require_once('db_manager.php');
$db_manager = new DbManager();
require_once('session_manager.php');
$session_manager = new SessionManager();

// If no user is logged in, redirect to login page
if (!$session_manager->is_logged_in()) {
    header('Location: '. PAGE_SIGN_IN);
}
else {
    $user = $session_manager->get_user();
}

// Function to store session variables
function store_session($name, $surname, $email, $username) {
    $_SESSION['name'] = $name;
    $_SESSION['surname'] = $surname;
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;
}
// End of function
if (isset($_POST['submit'])) {
	if ($_POST['form_identifier'] == 'user_info') {
	    $name = $_POST['name'];
	    $surname = $_POST['surname'];
	    $email = $_POST['email'];
	    $username = $_POST['username'];

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

	    $pdo = $db_manager->get_db_connection();
	    // Check if the specified email already exists in the database
	    if ($email!=$user['email'] && $db_manager->check_duplicate('email', $email, $pdo)) {
	        $errors[] = 'Email already exists';
	    }

	    // If there are no errors, update the user in the database
	    if (empty($errors)) {
	        if ($db_manager->update_user($user['user_id'], $name, $surname, $email, $pdo)) {
	            store_session($name, $surname, $email, $username);
	            $_SESSION[TAG_USER_EDIT_BEHAVIOUR] = TAG_USER_EDIT_BEHAVIOUR_EDITION;
	            $user = $db_manager->get_user($user['user_id'], $pdo);
	            $session_manager->login($user);
	            header('Location: '. PAGE_USER_INFO);
	        } else {
    	        $errors[] = 'Error updating user';
	        }
		}
    }
    elseif ($_POST['form_identifier'] == 'password_change') {
    	$password = $_POST['password'];
    	$password_confirm = $_POST['password_confirm'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 
  <link rel="stylesheet" type="text/css" href="styles.css">
  <title>The Last Time - Edit User</title>
</head>
<body>
  <?php include 'header.php';?>
  <main class="d-flex align-items-center">
    <div class="container">
      <h1 class="text-center">Edit User Information</h1>
      <div class="card shadow">
        <div class="card-body">
          <form action="<?php require_once('config.php'); echo PAGE_EDIT_USER; ?>" method="post">
          	<div class="form-group">
		        <input type="hidden" name="form_identifier" value="user_info">
		    </div>
            <div class="form-group">
              <label for="inputName">Name</label>
              <input type="text" class="form-control" id="inputName" name="name" value="<?php echo $user['name']; ?>">
            </div>
            <div class="form-group">
              <label for="inputSurname">Surname</label>
              <input type="text" class="form-control" id="inputSurname" name="surname" value="<?php echo $user['surname']; ?>">
            </div>
            <div class="form-group">
              <label for="inputEmail">Email</label>
              <input type="email" class="form-control" id="inputEmail" name="email" value="<?php echo $user['email']; ?>">
            </div>
            <div class="form-group">
              <label for="inputUsername">Username</label>
              <input type="text" class="form-control" id="inputUsername" name="username" value="<?php echo $user['username']; ?>" disabled>
            </div>
            <div class="form-group d-flex justify-content-between">
              <button type="submit" class="btn btn-primary" name="submit">Submit Changes</button>
              <a href="<?php require_once('config.php'); echo PAGE_USER_INFO; ?>" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
          <div class="divider"></div>
          <form action="<?php require_once('config.php'); echo PAGE_EDIT_USER; ?>" method="post">
          	<div class="form-group">
          		<input type="hidden" name="form_identifier" value="password_change">
          	</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" class="form-control" id="password" name="password" required>
			</div>
			<div class="form-group">
				<label for="password-confirm">Confirm Password</label>
				<input type="password" class="form-control" id="password-confirm" name="password-confirm" required>
			</div>
			<div class="form-group d-flex justify-content-between">
              <button type="submit" class="btn btn-primary" name="submit">Change password</button>
              <a href="<?php require_once('config.php'); echo PAGE_USER_INFO; ?>" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
