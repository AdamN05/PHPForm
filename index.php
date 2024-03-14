<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Form</title>
   <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
   <div class="main">
      <h1><b>PHP FORM</b></h1>

      <div class="form">
         <form method="POST" action="" enctype="multipart/form-data">
            <div class="Fnamn">
               <label for="förN">First name:</label><br>
               <input type="text" id="förN" name="förN"><br>
            </div>

            <div class="Enamn">
               <label for="efterN">Last name:</label><br>
               <input type="text" id="efterN" name="efterN">
            </div>

            <div class="Bdate">
               <label for="födsel">Birth date:</label><br>
               <input type="text" id="födsel" name="födsel">
            </div>

            <div class="file">
               <label for="file">Upload Image:</label><br>
               <input type="file" id="file" name="file" accept="image/*">
            </div>

            <input type="submit" name="submit" value="Submit">
         </form>
      </div>
   </div>

   <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hämtar form data
    $firstName = isset($_POST['förN']) ? $_POST['förN'] : '';
    $lastName = isset($_POST['efterN']) ? $_POST['efterN'] : '';
    $birthDate = isset($_POST['födsel']) ? $_POST['födsel'] : '';

    // kollar om en bild laddades upp
    $uploadedFilePath = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

        // kollar om filen har en allowed extension
        if (in_array(strtolower($fileExtension), $allowedExtensions)) {
            // Define the directory to save the uploaded image
            $uploadDirectory = 'uploads/';

            // Skapar upload directory
            if (!file_exists($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }

            // Gör en unik id så det inte blir krångel i filerna
            $uploadedFilePath = $uploadDirectory . uniqid() . '.' . $fileExtension;

            // Flyttar filen till uploads
            if (!move_uploaded_file($fileTmpName, $uploadedFilePath)) {
                echo "Error uploading file.";
                $uploadedFilePath = ''; // Clear uploaded file path if an error occurred
            }
        } else {
            echo "Invalid file format. Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    } elseif (isset($_FILES['file'])) {
        echo "Error uploading file: " . $_FILES['file']['error'];
    }

    // visar submitted content
    echo "<div class='main'>";
    echo "<h2>Submitted Content:</h2>";
    echo "<p>First Name: $firstName</p>";
    echo "<p>Last Name: $lastName</p>";
    echo "<p>Birth Date: $birthDate</p>";
    
    // Sparar data till data.txt
    $data = "Name: $firstName $lastName, Date of Birth: $birthDate" . PHP_EOL;
    $file = 'data.txt';
    file_put_contents($file, $data, FILE_APPEND);
    
    // visar bilden i submitted content
    if ($uploadedFilePath) {
        echo "<p>Uploaded Image:</p>";
        echo "<img src='$uploadedFilePath' alt='Uploaded Image' style='max-width: 160px ; height: auto;'>";
    }
    
    echo "</div>";
}
?>
</body>
</html>