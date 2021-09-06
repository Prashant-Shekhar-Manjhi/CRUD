<?php
    
    
    $username = "localhost";
    $servername = "root";
    $password = "";
    $database = "Note";

    $insert = false;
    $delete = false;
    $update = false;
    $connection = mysqli_connect($username,$servername,$password,$database);

    if(!$connection){
        die("sorry we failed to connect".mysqli_connect_error());
    }
    if(isset($_GET['delete'])){
        $sno = $_GET['delete'];
        $sqlDelete = "DELETE FROM `note` WHERE `id`= $sno";
        $result = mysqli_query($connection,$sqlDelete);
        if($result){
            $delete = true;
        }
    }


    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if(isset($_POST['snoEdit'])){
            $sno = $_POST['snoEdit'];
            $title = $_POST["titleEdit"];
            $desc = $_POST["descriptionEdit"];

            $sqlUpdate = "UPDATE `note` SET `title` = '$title', `description` = '$desc' WHERE `note`.`id` = '$sno'";
            $result = mysqli_query($connection,$sqlUpdate);
            $update = true;
            
        }
        else{
            $title = $_POST["title"];
            $desc = $_POST["desc"];

            $sqlInsert = "INSERT INTO `note` (`id`, `title`, `description`, `tStamp`) 
                    VALUES (NULL, '$title', '$desc', current_timestamp())";
            $result = mysqli_query($connection,$sqlInsert);

            if($result){
                $insert = true;
            }
            else{
                echo "error.....". mysqli_error($connection);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">


    <title>iNote</title>
</head>

<body>

    <div class="modal" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="POST">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="mb-3">
                            <label for="titleEdit" class="form-label">Note Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit">
                        </div>
                        <div class="mb-3">
                            <label for="descriptionEdit" class="form-label">Note Description</label>
                            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit"
                                rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary">Update Note</button>
                    </form>

                </div>

            </div>
        </div>
    </div>


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><b><i>iNotes</i></b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <?php
        if($insert == true){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Success!</strong> Your note has been successfully inserted.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } 
        if($delete == true){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Success!</strong> Your note has been successfully deleted.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }

        if($update == true){
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Success!</strong> Your note has been successfully updated.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
        
        

    ?>

    <div class="container my-4">
        <h5>Add a note to iNote</h5>
        <form action="index.php" method="POST">
            <div class="mb-3">
                <label for="titleText" class="form-label">Note Title</label>
                <input type="text" class="form-control" id="titleText" name="title">
            </div>
            <div class="mb-3">
                <label for="desc" class="form-label">Note Description</label>
                <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Add Note</button>
        </form>

    </div>

    <div class="container my-5">
        <?php
            $sqlFetchData = "SELECT * FROM `note`";
            $resultFetchData = mysqli_query($connection,$sqlFetchData);
            $i = 1;
            while($row = mysqli_fetch_assoc($resultFetchData)){
                
            }
        ?>
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">S.no</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Time</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sqlFetchData = "SELECT * FROM `note`";
                $resultFetchData = mysqli_query($connection,$sqlFetchData);
                $i = 1;
                while($row = mysqli_fetch_assoc($resultFetchData)){
                    echo "<tr>
                            <th scope='row'>". $i ."</th>
                            <td>". $row['title'] ."</td>
                            <td>". $row['description'] ."</td>
                            <td>". $row['tStamp'] ."</td>
                            <td> <button class='edit btn btn-sm btn-success' id=".$row['id'].">Edit</button> <button class='delete btn btn-sm btn-success' id=d".$row['id'].">Delete</button> </td>
                        </tr>";
                    $i++;
                }
            ?>

            </tbody>
        </table>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"
        integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"
        integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous">
    </script>

    <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
    </script>

    <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
        element.addEventListener("click", (e) => {
            tr = e.target.parentNode.parentNode;
            title = tr.getElementsByTagName("td")[0].innerText;
            description = tr.getElementsByTagName("td")[1].innerText;
            descriptionEdit.value = description;
            titleEdit.value = title;
            snoEdit.value = e.target.id;
            $('#editModal').modal('toggle');

        })
    })
    </script>


    <script>
    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
        element.addEventListener("click", (e) => {
            sno = e.target.id.substr(1, );

            if (confirm("Are you sure you want to delete this note!")) {
                console.log("yes");
                window.location = `index.php?delete=${sno}`;
            } else {
                console.log("no");
            }
        })
    })
    </script>
</body>

</html>