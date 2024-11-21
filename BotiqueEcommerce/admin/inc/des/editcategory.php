<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <form method="post" action="editcategorysubmit.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="exampleInputEmail1">Category Name</label>
            <?php
            include("../../conn.php");
            $id = $_GET['id'];
            $sql = "SELECT * FROM cattigur WHERE id=$id";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);

            ?>
            <input type="text" name='id' value='<?php echo $row['id'] ?>' style='display:none;'>
            <input type="text" name="category" value="<?php echo $row['name'] ?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Ctegory Name">
            <br>
            <input type="file" id="file" name='image'>

            <label for="text"> تحديث صورة المنتج</label>
            <br>
            <br>
            <button name="editcategory" type="submit" class="btn btn-primary">Submit</button>

        </div>

    </form>



</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>