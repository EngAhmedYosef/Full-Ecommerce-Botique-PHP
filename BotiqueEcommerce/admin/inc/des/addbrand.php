<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <form method="post" action="addbrand.php">
        <div class="form-group">
            <label for="exampleInputEmail1">Brand Name</label>
            <input type="text" name="brand" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Ctegory Name">
        </div>

        <button name="addbrand" type="submit" class="btn btn-primary">Submit</button>
    </form>


    <?php
    include("../../conn.php");


    if (isset($_POST['addbrand'])) {
        $brand = mysqli_real_escape_string($conn,$_POST['brand']);
        $sql = "INSERT INTO brand (name) VALUES ('$brand')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            header("location: ../../brand.php");
        } else {
            echo "Error Brand Not Inserted";
        }
    }
    ?>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>