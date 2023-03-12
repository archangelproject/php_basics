<?php
  require_once('session_manager.php');
  require_once('config.php');

  $session_manager = new SessionManager();

  try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
  } catch (PDOException $e) {
   die('Database connection failed: ' . $e->getMessage());
  }

  $user = $session_manager->get_user();

  if ($user) {
        $link1 = PAGE_USER_INFO;
        $text1 = 'User info';
        $link2 = PAGE_LOGOUT;
        $text2 = 'Logout';
  } else {
        $link1 = PAGE_SIGN_IN;
        $text1 = 'Log in';
        $link2 = PAGE_SIGN_UP;
        $text2 = 'Sign up';
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <title><?php echo TAG_PROJECT_NAME; ?></title>
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="<?php echo PAGE_MAIN; ?>"><?php echo TAG_PROJECT_NAME; ?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $link1; ?>"><?php echo $text1; ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $link2; ?>"><?php echo $text2; ?></a>
            </li>
        </ul>
      </div>
    </nav>
  </header>
