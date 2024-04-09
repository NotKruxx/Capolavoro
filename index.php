<?php
session_start();

$db = mysqli_connect('localhost', 'root', '', 'capolavoro');

if (!$db) {
    die("Errore di connessione al database.");
}

if (isset($_POST['modifica'])) {
    $transactionID = $_POST['transactionID'];
    $newTipo = $_POST['newTipo'];
    $newAmount = $_POST['newAmount'];
    $newDate = $_POST['newDate'];

    $query = "UPDATE Transactions SET Tipo='$newTipo', Amount='$newAmount', Date='$newDate' WHERE TransactionID=$transactionID";
    mysqli_query($db, $query);
}

if (isset($_POST['elimina'])) {
    $transactionID = $_POST['transactionID'];

    $query = "DELETE FROM Transactions WHERE TransactionID=$transactionID";
    mysqli_query($db, $query);
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <title>Homepage</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <script>
    document.getElementById('Importo').onchange = function () {
        if (this.value < 0) {
            alert('Devi inserire un importo maggiore o uguale a 0!');
            this.value = 0;
        }
    };
</script>
  
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      <?php

      $id = $_SESSION['id'];
      $query = "SELECT YEAR(Date) AS Anno, ";
      $query .= "SUM(CASE WHEN Tipo = 'Entrata' THEN Amount ELSE -Amount END) AS Totale ";
      $query .= "FROM Transactions WHERE UserID = $id GROUP BY YEAR(Date)";
      $result = mysqli_query($db, $query);

      ?>

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Anno', 'Totale'],
          <?php
          while ($row = mysqli_fetch_array($result)) {
            echo "['" . $row['Anno'] . "', " . $row['Totale'] . "],";
          }
          ?>

        ]);

        var options = {
          title: 'Andamento delle transazioni nel tempo',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
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
          <?php if (!isset($_SESSION['id'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="create.php">Crea un account!</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <form method="post">
                <button type="submit" class="btn btn-link nav-link" name="logout">Logout</button>
              </form>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  
  <?php
  if (!isset($_SESSION['id'])) {
    echo '<div class="container">
            <h1 class="titolo">Benvenuto <hr> </h1>
            <p class="frase"> Qui potrai gestire e visionare in modo semplice e veloce le tue spese o guadagni. </p>
            <p class="frase"> Cosa aspetti? <a class="link" href="create.php"> Crea un account </a> e inizia subito! </p>
          </div>';
  } else {
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
                  <th scope='col'>Azioni</th> <!-- Aggiunta della colonna per le azioni -->
                </tr>
              </thead>
              <tbody>";

      while ($row = mysqli_fetch_array($result)) {
        echo "<tr>
                <td>" . $row['Tipo'] . "</td>
                <td>" . $row['Amount'] . "</td>
                <td>" . $row['Date'] . "</td>
                <td>
                  <a href='modifica.php?id=" . $row['TransactionID'] . "' class='btn btn-primary btn-sm'>Modifica</a> <!-- Bottone per la modifica -->
                  <form method='post'>
                    <input type='hidden' name='transactionID' value='" . $row['TransactionID'] . "'>
                    <button type='submit' name='elimina' class='btn btn-danger btn-sm'>Elimina</button> <!-- Bottone per l'eliminazione -->
                  </form>
                </td>
              </tr>";
      }

      echo "</tbody></table>";
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
                    <input type="number" class="form-control" id="Importo" value="Amount" name="Amount" step="0.01" min="0" required>
                    </div>
                  <div class="mb-3">
                    <label for="Data" class="form-label">Data</label>
                    <input type="date" class="form-control" id="Data" value="Date" name="Date">
                  </div>
                  <button type="submit" class="btn btn-primary">Aggiungi transazione</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        </div>';

      echo ' <div class="container">
              <h1 class="titolo">Grafico delle transazioni <hr> </h1>
              <center>
      <div id="curve_chart" style="width: 900px; height: 500px"></div>
      </center>
      </div>';
  }



  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
