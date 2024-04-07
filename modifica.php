<?php
session_start();

$db = mysqli_connect('localhost', 'root', '', 'capolavoro');

if (!$db) {
    die("Errore di connessione al database.");
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$transactionID = $_GET['id'];

if (isset($_POST['modifica'])) {
    $newTipo = $_POST['newTipo'];
    $newAmount = $_POST['newAmount'];
    $newDate = $_POST['newDate'];

    $query = "UPDATE Transactions SET Tipo='$newTipo', Amount='$newAmount', Date='$newDate' WHERE TransactionID=$transactionID";
    mysqli_query($db, $query);

    header("Location: index.php");
    exit();
}

$query = "SELECT * FROM Transactions WHERE TransactionID = $transactionID";
$result = mysqli_query($db, $query);
$row = mysqli_fetch_array($result);

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <title>Modifica Transazione</title>
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
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <div class="container">
    <h1 class="titolo">Modifica Transazione</h1>
    <form method="post">
      <div class="mb-3">
        <label for="newTipo" class="form-label">Tipo</label>
        <select class="form-select" id="newTipo" name="newTipo">
          <option value="Entrata" <?php if ($row['Tipo'] == 'Entrata') echo 'selected'; ?>>Entrata</option>
          <option value="Uscita" <?php if ($row['Tipo'] == 'Uscita') echo 'selected'; ?>>Uscita</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="newAmount" class="form-label">Importo</label>
        <input type="number" class="form-control" id="newAmount" name="newAmount" value="<?php echo $row['Amount']; ?>" step="0.01">
      </div>
      <div class="mb-3">
        <label for="newDate" class="form-label">Data</label>
        <input type="date" class="form-control" id="newDate" name="newDate" value="<?php echo $row['Date']; ?>">
      </div>
      <button type="submit" class="btn btn-primary" name="modifica">Salva Modifiche</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
