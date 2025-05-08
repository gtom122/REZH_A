<?php
function check_email($email) {
    return preg_match("/^([A-Za-z0-9_\-\.])+@([A-Za-z0-9_\-\.])+\.[A-Za-z]{2,4}$/", $email);
}

$valid = true;

function validate($key, $value) {
    global $valid;
    switch ($key) {
        case "email":
            if (!$value || !check_email($value)) {
                echo "E-mail: $value Hibás!<br>";
                $valid = false;
            } else {
                echo "E-mail: $value Helyes<br>";
            }
            break;
        case "nev":
            if (!$value || strlen($value) < 5 || strlen($value) > 30) {
                echo "Név: $value Hibás!<br>";
                $valid = false;
            } else {
                echo "Név: $value Helyes<br>";
            }
            break;
        case "jelszo":
            if (!$value || strlen($value) < 6 || strlen($value) > 12) {
                echo "Jelszó: $value Hibás!<br>";
                $valid = false;
            } else {
                echo "Jelszó: $value Helyes<br>";
            }
            break;
        case "kor":
            if (!is_numeric($value) || $value < 18 || $value > 100) {
                echo "Kor: $value Hibás!<br>";
                $valid = false;
            } else {
                echo "Kor: $value Helyes<br>";
            }
            break;
        case "nem":
            if (!$value) {
                echo "Nem: nincs kiválasztva Hibás!<br>";
                $valid = false;
            } else {
                echo "Nem: $value Helyes<br>";
            }
            break;
    }
}

foreach ($_POST as $key => $value) {
    validate($key, $value);
}

if ($valid) {
    $conn = new mysqli("localhost", "root", "", "zh");
    if ($conn->connect_error) {
        die("Kapcsolódási hiba: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO regisztracio (email, nev, jelszo, kor, nem) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $_POST["email"], $_POST["nev"], $_POST["jelszo"], $_POST["kor"], $_POST["nem"]);
    $stmt->execute();

    echo "<br>Adatok elmentve.";
    $stmt->close();
    $conn->close();
}
?>

<!-- http://localhost/regisztracio/index.html >>