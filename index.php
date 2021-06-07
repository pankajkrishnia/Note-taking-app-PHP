<?php 
require '_dbconnect.php';
$insert = false;
$update = false;
$delete = false;

if(isset($_GET['delete'])){
    $sno = $_GET['delete'];
    $sql = "DELETE FROM `notes` WHERE `notes`.`sno` = $sno";
    $result = mysqli_query($conn,$sql);
    if($result){
        $delete = true;
    }
    else{
        echo "The note is not deleted successfully due to this error: ". mysqli_error($conn);
    }
}
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST["snoEdit"])){
        $title = $_POST["titleEdit"];
        $description = $_POST["descriptionEdit"];
        $sno = $_POST["snoEdit"];

        $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$description' WHERE `notes`.`sno` =$sno";
        $result = mysqli_query($conn,$sql);

        if($result){
            $update = true;
        }
        else{
            echo "The note is not updated successfully due to this error: ". mysqli_error($conn);
        }
    }
    else{
        $title = $_POST["title"];
        $description = $_POST["description"];

        $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
        $result = mysqli_query($conn,$sql);

        if($result){
            $insert = true;
        }
        else{
            echo "The note is not added successfully due to this error: ". mysqli_error($conn);
        }  
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <title>iNotes</title>
</head>

<body>


    <!-- Edit Modal -->

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit your Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal form -->
                <form action="/crud/index.php" method="post">
                    <div class="modal-body">
                        <div class="container my-3">
                            <input type="hidden" name="snoEdit" id="snoEdit">
                            <div class="mb-3">
                                <label for="titleEdit" class="form-label">Title</label>
                                <input type="text" class="form-control" id="titleEdit" name="titleEdit"
                                    aria-describedby="emailHelp">
                            </div>
                            <div class="mb-3">
                                <label for="descriptionEdit" class="form-label">Description</label>
                                <textarea class="form-control" id="descriptionEdit" name="descriptionEdit"
                                    rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-block mx-3">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- nav bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">iNotes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>

                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <?php
        if($insert){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success! </strong> Your note has been added successfully.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
    ?>
    <?php
        if($delete){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success! </strong> Your note has been deleted successfully.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
    ?>
    <?php
        if($update){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success! </strong> Your note has been updated successfully.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
    ?>


    <!-- form -->
    <div class="container my-3">
        <h2 class="mb-3">Add your Note</h2>
        <form action="/crud/index.php" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Note Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Note Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>


    <!-- display data and update delete using php -->
    <div class="container my-5">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $sql = "SELECT * FROM `notes`";
                $result = mysqli_query($conn,$sql);
                $num = mysqli_num_rows($result);
                if($num>0){
                    $sno = 0;
                    while($row = mysqli_fetch_assoc($result)){
                        $sno += 1;
                        echo "<tr>
                        <th scope='row'>". $sno. "</th>
                        <td>". $row['title']. "</td>
                        <td>". $row['description']. "</td>
                        <td> <button class = 'edit btn btn-sm btn-primary' id=".$row['sno']." data-bs-toggle='modal' data-bs-target='#editModal'>Edit</button>
                        <button class = 'delete btn btn-sm btn-primary' id=d".$row['sno'].">Delete</button> </td>   
                        </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <hr>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
        </script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous">
        </script>
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>
    <script>
        edits = document.getElementsByClassName("edit");
        Array.from(edits).forEach(element => {
            element.addEventListener("click", () => {
                tr = element.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                description = tr.getElementsByTagName("td")[1].innerText;
                titleEdit.value = title;
                descriptionEdit.value = description;
                snoEdit.value = element.id;
            });
        });

        deletes = document.getElementsByClassName("delete");
        Array.from(deletes).forEach(element => {
            element.addEventListener("click", () => {
                sno = element.id.substr(1,);
                if (confirm("Are you sure you want to delete this note")) {
                    window.location = `/crud/index.php?delete=${sno}`;
                }
            });
        });
    </script>

</body>

</html>