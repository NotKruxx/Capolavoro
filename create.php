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
    <!-- Barra di navigazione -->
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
          <a class="nav-link" href="login.php">Login</a>
        </li>
    </ul>
    </div>
  </div>
</nav>
    <form action="create.php" method="post">
        <div class="container">
            <h1 class="titolo">Crea un account <hr> </h1>
            <div class="mb-3">
                <label for="Nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="Nome" name="Nome">
            </div>
            <div class="mb-3">
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control" id="Email" name="Email">
            </div>
            <div class="mb-3">
                <label for="Password" class="form-label">Password</label>
                <input type="password" class="form-control" id="Password" name="Password">
            </div>
            <div class="mb-3">
                <label for="ConfermaPassword" class="form-label">Conferma Password</label>
                <input type="password" class="form-control" id="ConfermaPassword" name="ConfermaPassword">
                <div id="emailHelp" class="form-text">Le password devono coincidere.</div>
            </div>
            <button type="submit" class="btn btn-primary">Crea account</button>
            <div id="emailHelp" class="form-text">Hai già un account? <a href="login.php">Accedi</a></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Nome = $_POST['Nome'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $ConfermaPassword = $_POST['ConfermaPassword'];

    include 'db.php';

    if ($Password === $ConfermaPassword) {
        $query = "SELECT * FROM UserCredentials WHERE Email = '$Email'";
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result) == 0) {
            $Password = hash('sha256', $Password);
            $query = "INSERT INTO UserCredentials (Username, Email, PasswordHash) VALUES ('$Nome', '$Email', '$Password')";
            mysqli_query($db, $query);
            header('Location: login.php');
        } else {
            echo '<div class="container">
                <h1 class="titolo">Errore <hr> </h1>
                <p class="frase"> Questo account è già stato creato. </p>
                </div>';
        }
    } else {
        echo '<div class="container">
            <h1 class="titolo">Errore <hr> </h1>
            <p class="frase"> Le password non coincidono. </p>
            </div>';
    }
}

?>
