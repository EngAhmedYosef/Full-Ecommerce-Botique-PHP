<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Form</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- NicEdit JS for TextArea Editor -->
    <script src="//js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
    <script type="text/javascript">
        bkLib.onDomLoaded(nicEditors.allTextAreas);
    </script>

    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Arial', sans-serif;
        }
        .form-container {
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .submit-btn {
            background-color: #4e73df;
            color: white;
        }
        .submit-btn:hover {
            background-color: #2e59d9;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2>Add New Product</h2>
        <form method="post" action="inc/func/do_add_pro.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="elname">Product Name:</label>
                <input type="text" name="elname" class="form-control" id="elname" required>
            </div>

            <div class="form-group">
                <label for="decr">Description:</label>
                <textarea name="decr" class="form-control" style="width: 100%; height: 150px;"></textarea>
            </div>

            <div class="form-group">
                <label for="image">Cover Image:</label>
                <input type="file" name="image[]" class="form-control" multiple required>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" name="price" class="form-control" id="price" required>
            </div>


            <div class="form-group">
                <label for="brand">Brand:</label>
                <select name="brand" class="form-control" id="brand" required>
                    <?php
                    $sql = "SELECT * FROM brand";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="cat">Category:</label>
                <select name="cat" class="form-control" id="cat" required>
                    <?php
                    $sql = "SELECT * FROM cattigur";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="sold">Sold Percentage:</label>
                <input type="number" name="sold" class="form-control" id="sale">
            </div>

            <div class="form-group">
                <label for="count">Stock Count:</label>
                <input type="text" name="count" class="form-control" id="count" required>
            </div>

            <button type="submit" name="submit" value="go" class="btn btn-primary btn-block submit-btn">Add Product</button>
        </form>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
