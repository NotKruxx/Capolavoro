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
            <a class="nav-link" href="create.php">Crea un account!</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- Fine barra di navigazione -->
  
  <?php
  // Se l'utente non è loggato mostro un messaggio di benvenuto
  if (!isset($_SESSION['id'])) {
    echo '<div class="container">
            <h1 class="titolo">Benvenuto <hr> </h1>
            <p class="frase"> Qui potrai gestire e visionare in modo semplice e veloce le tue spese o guadagni. </p>
            <p class="frase"> Cosa aspetti? <a class="link" href="create.php"> Crea un account </a> e inizia subito! </p>
          </div>';
  } else {
    include 'db.php';
    $id = $_SESSION['id'];
    $query = "SELECT * FROM UserCredentials WHERE UserID = $id";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_array($result);
    echo '<div class="container">
            <h1 class="titolo">Benvenuto ' . $row['Username'] . ' <hr> </h1>
            <div class="frase"> Tabella delle informazioni di tutte le tue transazioni </div>';

            $query = "SELECT * FROM Transactions WHERE UserID = $id";

            $result = mysqli_query($db, $query);

            if ($result->num_rows == 0) {
              echo "<div class = 'frase'> Non ci sono transazioni da visualizzare. </div>";
            } else {
              echo "<table class='table'>
                      <thead>
                        <tr>
                          <th scope='col'>Tipo</th>
                          <th scope='col'>Importo</th>
                          <th scope='col'>Data</th>
                        </tr>
                      </thead>
                      <tbody>"; // Add tbody tag here

              while ($row = mysqli_fetch_array($result)) {
                echo "<tr>
                        <td>" . $row['Tipo'] . "</td>
                        <td>" . $row['Amount'] . "</td>
                        <td>" . $row['Date'] . "</td>
                      </tr>";
              }

              echo "</tbody></table>"; // Close tbody and table tags here
            }
            
            echo '<hr>
              <div class="row">
                <div class="col-sm">
                <div class= "container">
                  Aggiungi una transazione
                  <form action="aggiungi.php" method="post">
                    <div class="mb-3">
                      <label for="Tipo" class="form-label">Tipo</label>
                      <select class="form-select" id="Tipo" value="Tipo" name="Tipo">
                        <option value="Entrata">Entrata</option>
                        <option value="Uscita">Uscita</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="Importo" class="form-label">Importo</label>
                      <input type="number" class="form-control" id="Importo" value="Amount" name="Amount" step="0.01">
                    </div>
                    <div class="mb-3">
                      <label for="Data" class="form-label">Data</label>
                      <input type="date" class="form-control" id="Data" value="Date" name="Date">
                    </div>
                    <button type="submit" class="btn btn-primary">Aggiungi transazione</button>
                  </form>
                </div>
                </div>
                  <div class="col-sm">
                  <div class= "container">
                    Modifica una transazione
                  </div>
                </div>
                <div class="col-sm">
                <div class= "container">
                  Elimina una transazione
                </div>
                </div>
            </div>
          </div>';
  }
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>