

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor List</title>
    <link rel="stylesheet" href="./assets/css/donorlist.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="p-3 mb-2 bg-primary-subtle text-primary-emphasis">
<?php

session_start();

if(isset($_SESSION['donors'])) {
    $donors = $_SESSION['donors'];

    echo "<h2>Matching Donors</h2>";
    echo "<table class = \" tb table table-hover table-striped table-responsive \">";
    echo "<tr>";
    echo "<th scope=\"col\">Name</th>";
    echo "<th scope=\"col\">Blood Group</th>";
    echo "<th scope=\"col\">Age</th>";
    echo "<th scope=\"col\">Email</th>";
    echo "<th scope=\"col\">Phone</th>";
    echo "<th scope=\"col\">Gender</th>";
    echo "<th scope=\"col\">Weight</th>";
    echo "<th scope=\"col\">State</th>";
    echo "<th scope=\"col\">District</th>";
 
    echo "</tr>";
    function calculateAge($dob) {
        $dob = new DateTime($dob);
        $now = new DateTime();
        $interval = $now->diff($dob);
        return $interval->y;
    }

    foreach($donors as $donor) {


        echo "<tr>";
        echo "<td>" . $donor["name"] . "</td>";
        echo "<td>" . $donor["bloodgroup"] . "</td>";
        echo "<td>" . calculateAge($donor["birthdate"]) . "</td>";
        echo "<td>" . $donor["email"] . "</td>";
        echo "<td>" . $donor["phone"] . "</td>";
        echo "<td>" . $donor["gender"] . "</td>";
        echo "<td>" . $donor["weight(kg)"] . "</td>";
        echo "<td>" . $donor["state"] . "</td>";
        echo "<td>" . $donor["district"] . "</td>";

        echo "</tr>";
    }
    echo "</table>";

    unset($_SESSION['donors']);
} else {
    echo "<p>No donor data found.</p>";
}


?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
