<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <form method="post" action="addcategory.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="exampleInputEmail1">Category Name</label>
            <input type="text" name="category" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Ctegory Name">
            <br>
            <label for="exampleInputEmail1">Cover</label>
            <input type="file" name="image" class="form-control" multiple="">
        </div>

        <button name="addcategory" type="submit" class="btn btn-primary">Submit</button>
    </form>


    <?php
    include("../../conn.php");


    if (isset($_POST['addcategory'])) {
        // Escape category input to prevent SQL injection
        $category = mysqli_real_escape_string($conn, $_POST['category']);
    
        // Handle file upload
        $IMAGE = $_FILES['image'];
        $image_location = $_FILES['image']['tmp_name'];
        $image_name = mysqli_real_escape_string($conn, $_FILES['image']['name']); // Escape the filename for safety
    
        // Sanitize filename (remove special characters like apostrophes)
        $image_name = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $image_name);
    
        // Validate file extension (Allow only images like .jpg, .jpeg, .png)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    
        if (in_array($file_extension, $allowed_extensions)) {
            // Define the target directory
            $target_dir = 'images/';
            $image_up = $target_dir . $image_name;
    
            // Check if the directory exists, and create it if necessary
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
    
            // Move uploaded file to the target directory
            if (move_uploaded_file($image_location, $image_up)) {
                // SQL query to insert category and image path
                $sql = "INSERT INTO cattigur (name, image) VALUES ('$category', '$image_up')";
                $result = mysqli_query($conn, $sql);
    
                if ($result) {
                    // Redirect if the insertion was successful
                    header("location: ../../category.php");
                } else {
                    echo "Error: Category Not Inserted - " . mysqli_error($conn);
                }
            } else {
                echo "Error: Failed to upload image.";
            }
        } else {
            echo "Error: Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    }
    
    
    ?>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>