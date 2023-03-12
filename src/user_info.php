<?php
require_once('config.php');
require_once('session_manager.php');
session_start();
$session_manager = new SessionManager();

if (!$session_manager->is_logged_in()) {
  header('Location: '. PAGE_SIGN_IN);
  exit;
}

$user = $session_manager->get_user();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 
  <link rel="stylesheet" type="text/css" href="styles.css">
  <title>The Last Time - User Info</title>
</head>
<body>
  <?php include 'header.php';?>
  <main class="d-flex align-items-center">
    <div class="container">
      <h1 class="text-center">User Information</h1>
      <div class="card shadow">
        <div class="card-body">
          <h5 class="card-title">Name</h5>
          <p class="card-text"><?php echo $user['name']; ?></p>
          <h5 class="card-title">Surname</h5>
          <p class="card-text"><?php echo $user['surname']; ?></p>
          <h5 class="card-title">Email</h5>
          <p class="card-text"><?php echo $user['email']; ?></p>
          <h5 class="card-title">Username</h5>
          <p class="card-text"><?php echo $user['username']; ?></p>
        </div>
      </div>
      <div class="mt-3">
        <?php
          if(isset($_SESSION[TAG_USER_EDIT_BEHAVIOUR]) && $_SESSION[TAG_USER_EDIT_BEHAVIOUR] == TAG_USER_EDIT_BEHAVIOUR_CREATION) {
              echo '<div class="alert alert-success" role="alert">User created successfully!</div>';
              unset($_SESSION[TAG_USER_EDIT_BEHAVIOUR]);
          }
          else if(isset($_SESSION[TAG_USER_EDIT_BEHAVIOUR]) && $_SESSION[TAG_USER_EDIT_BEHAVIOUR] == TAG_USER_EDIT_BEHAVIOUR_EDITION) {
              echo '<div class="alert alert-success" role="alert">User info updated successfully!</div>';
              unset($_SESSION[TAG_USER_EDIT_BEHAVIOUR]);
          }
          else if(isset($_SESSION[TAG_USER_EDIT_BEHAVIOUR]) && $_SESSION[TAG_USER_EDIT_BEHAVIOUR] == TAG_USER_EDIT_BEHAVIOUR_CONSULTATION) {
              // Nothing to do
              unset($_SESSION[TAG_USER_EDIT_BEHAVIOUR]);
          }
        ?>
        <a href="<?php echo PAGE_MAIN; ?>" class="btn btn-primary">Go back to main page</a>
        <a href="<?php echo PAGE_EDIT_USER; ?>" class="btn btn-primary">Edit user info</a>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></scri>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigi>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9U
