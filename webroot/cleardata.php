<?php

include('../code/config.php');

$conn = connectToDatabase();


$query = 'TRUNCATE TABLE account';
mysqli_query($conn, $query);
mysqli_close($conn);

?>