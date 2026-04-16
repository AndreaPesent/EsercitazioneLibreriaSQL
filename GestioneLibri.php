<?php
$conn = mysqli_connect("localhost", "root", "", "pesenti_biblioteca");
if (!$conn) die("Errore connessione");
if(isset($_POST['salva_libro'])){
    $titolo = $_POST['titolo'];
    $autore = $_POST['autore'];

    mysqli_query($conn, "INSERT INTO libri (titolo, id_autore)
                         VALUES ('$titolo', $autore)");
}
if(isset($_POST['salva_prestito'])){
    $libro = $_POST['libro'];
    $utente = $_POST['utente'];

    mysqli_query($conn, "INSERT INTO prestiti (id_libro, id_utente, data_prestito)
                         VALUES ($libro, $utente, CURDATE())");
}
if(isset($_GET['restituisci'])){
    $id = $_GET['restituisci'];

    mysqli_query($conn, "UPDATE prestiti
                        SET data_restituzione = CURDATE()
                        WHERE id = $id");
}
?>
<h2>Inserisci Libro</h2>
<form method="post">
    Titolo: <input type="text" name="titolo">
    Autore:
    <select name="autore">
        <?php
        $res = mysqli_query($conn, "SELECT * FROM autori");
        while($row = mysqli_fetch_assoc($res)){
            echo "<option value='".$row['id']."'>".$row['nome']."</option>";
        }
        ?>
    </select>
    <button name="salva_libro">Salva</button>
</form>
<hr>
<h2>Inserisci Prestito</h2>
<form method="post">
Libro:
<select name="libro">
<?php
$res = mysqli_query($conn, "SELECT * FROM libri");
while($row = mysqli_fetch_assoc($res)){
    echo "<option value='".$row['id']."'>".$row['titolo']."</option>";
}
?>
</select>
Utente:
<select name="utente">
<?php
$res = mysqli_query($conn, "SELECT * FROM utenti");
while($row = mysqli_fetch_assoc($res)){
    echo "<option value='".$row['id']."'>".$row['nome']."</option>";
}
?>
</select>
<button name="salva_prestito">Registra</button>
</form>
<hr>
<h2>Prestiti per utente</h2>
<form method="get">
Utente:
<select name="utente">
<?php
$res = mysqli_query($conn, "SELECT * FROM utenti");
while($row = mysqli_fetch_assoc($res)){
    echo "<option value='".$row['id']."'>".$row['nome']."</option>";
}
?>
</select>
<button>Visualizza</button>
</form>
<?php
if(isset($_GET['utente']))
    {
    $utente = $_GET['utente'];
    $res = mysqli_query($conn, "
        SELECT prestiti.id, libri.titolo, data_restituzione
        FROM prestiti
        JOIN libri ON prestiti.id_libro = libri.id
        WHERE id_utente = $utente
    ");
    echo "<h3>Libri:</h3>";
    while($row = mysqli_fetch_assoc($res))
        {
        echo $row['titolo'];
        if($row['data_restituzione'] == NULL){
            echo " <a href='?restituisci=".$row['id']."'>Restituisci</a>";
        } else 
        {
            echo " (restituito)";
        }
        echo "<br>";
    }
}
?>