<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri&family=Cairo:wght@200&family=Poppins:wght@100;200;300&family=Tajawal:wght@300&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update | تعديل منتج</title>
    <link rel="stylesheet" href="index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php
    include('../../conn.php');
    $ID = $_GET['id'];
    $up = mysqli_query($conn, "select * from salles where id =$ID");
    $data = mysqli_fetch_array($up);

    ?>
    <form action="editproductsubmit.php" method="post" enctype="multipart/form-data">
        <h2>تعديل المنتجات</h2>
        <input type="text" name='id' value='<?php echo $data['id'] ?>' style='display:none;'>
        <br>
        <label>Name:</label>
        <input class="form-control" type="text" name='name' value='<?php echo $data['name'] ?>'>
        <br>
        <label>price:</label>
        <input class="form-control" type="text" name='price' value='<?php echo $data['price'] ?>'>
        <br>
        <label>Cattigory:</label>
        <select name="cattigur" class="form-control">
            <?php

            $sql = "SELECT * FROM cattigur";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {

            ?>

                <option value="<?php echo $row['id']    ?>"><?php echo $row['name']    ?></option>

            <?php
            }

            ?>
        </select>

        <br>

        <label>Brand:</label>
        <select name="brand" class="form-control">
            <?php

            $sql = "SELECT * FROM brand";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {

            ?>

                <option value="<?php echo $row['id']    ?>"><?php echo $row['name']    ?></option>

            <?php
            }

            ?>
        </select>
        <br>
        <label>Product Image:</label>
        <input class="form-control" type="file" id="file" name='image[]' multiple style='display:none;'>
        <br>
        <div class="input-group input-group-sm mb-3"></div>
        <label class="input-group-text" for="file"> تحديث صورة المنتج</label>
        
        </div>
        <br>
        <button class="btn btn-primary" name='update' type='submit'>تعديل المنتج</button>
        <br><br>
        <a href="products.php" class="link-danger link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">عرض كل المنتجات</a>
    </form>
    </div>
    <br>
    <h5>Developer By AhmedYosef</h5>
</body>

</html>