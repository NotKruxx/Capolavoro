<?php

session_start();

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand">Capolavoro</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Homepage</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="create.php">Crea un account!</a>
        </li>
    </ul>
    </div>
  </div>
</nav>
<form action="login.php" method="post">
  <div class="container">
    <h1 class="titolo">Login <hr> </h1>
    <div class="mb-3">
      <label for="Email" class="form-label">Email</label>
      <input type="email" class="form-control" id="Email" name="Email">
    </div>
    <div class="mb-3">
      <label for="Password" class="form-label">Password</label>
      <input type="password" class="form-control" id="Password" name="Password">
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
    <div id="emailHelp" class="form-text">Non hai un account? <a href="create.php">Crea un account</a></div>
  </div>
</form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

<?php

include 'db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $email = $_POST['Email'];
    $password = $_POST['Password'];

    $password = hash('sha256', $password);

    $query = "SELECT * FROM UserCredentials WHERE Email = '$email' AND PasswordHash = '$password'";
    $result = mysqli_query($db, $query);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        $_SESSION['id'] = $row['UserID'];
        header('Location: index.php');
    } else {
        echo '<div class="container">
            <h1 class="titolo">Errore <hr> </h1>
            <p class="frase"> Email o password errati. </p>
            <p class="frase"> <a class="link" href="login.php"> Riprova </a> </p>
            </div>';
    }
}

?>