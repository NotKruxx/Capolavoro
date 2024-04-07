<?php

session_start();

if (isset($_SESSION['id'])) {
    include 'db.php';
    $id = $_SESSION['id'];
    $data = $_POST['Date'];
    $tipo = $_POST['Tipo'];
    $quantita = $_POST['Amount'];
    $query = "INSERT INTO Transactions (UserID, Tipo, Amount, Date) VALUES ('$id', '$tipo', '$quantita', '$data')";
    $result = mysqli_query($db, $query);
    header("Location: index.php");
} else {
    echo "Non hai effettuato l'accesso.";
    header("Refresh: 2; url=login.php");
}

?>