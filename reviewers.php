<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>

    <title>PHP CRUD using jquery ajax without page reload</title>

    <style>
    .reviewer_h1 {
        font-size: 20px;
        font-weight: bold;
    }

    .reviewer_hr {
        width: 20%;
        margin-top: 5px;
        background-color: #2f6db2;
        padding: 1.5px;
    }

    .reviewer_h3 {
        font-size: 17px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    </style>
</head>
<body>

<!-- Add reviewer -->
<div class="modal fade" id="reviewerAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="saveReviewer" enctype="multipart/form-data">
            <div class="modal-body">
                <h1 class="reviewer_h1">Become Reviewer Form</h1>
                <hr class="reviewer_hr">
                <h3 class="reviewer_h3">Personal Information</h3>

                <div id="errorMessage" class="alert alert-warning d-none"></div>

                <div class="mb-3">
                    <label for="">Title</label>
                    <select id="title" name="rank"  class="form-control" >
                        <option value=""></option>
                        <option value="Dr.">Dr.</option>
                        <option value="Prof.">Prof.</option>
                        <option value="Prof. Dr.">Prof. Dr.</option>
                        <option value="Mr.">Mr.</option>
                        <option value="Mrs.">Mrs.</option>
                        <option value="Miss">Miss</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="">First name</label>
                    <input type="text" name="fname" class="form-control" />
                </div>

                <div class="mb-3">
                    <label for="">Last name</label>
                    <input type="text" name="lname" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="">Email</label>
                    <input type="text" name="email" class="form-control" />
                </div>

                <div class="mb-3">
                    <label for="">Organization / University</label>
                    <input type="text" name="university" class="form-control" />
                </div>

                <div class="mb-3">
                    <label for="">Upload CV</label>
                    <input type="file" name="file" id="file" class="form-control" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#reviewerAddModal">
                            Add reviewer
                        </button>
                    </h4>
                </div>
                <div class="card-body">

                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>rank</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>University</th>
                                <th>CV</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require 'dbcon.php';

                            $query = "SELECT * FROM reviewers";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                foreach($query_run as $reviewer)
                                {
                                    ?>
                                    <tr>
                                        <td><?= $reviewer['id'] ?></td>
                                        <td><?= $reviewer['rank'] ?></td>
                                        <td><?= $reviewer['fname'] ?></td>
                                        <td><?= $reviewer['lname'] ?></td>
                                        <td><?= $reviewer['email'] ?></td>
                                        <td><?= $reviewer['university'] ?></td>
                                        <td><?= $reviewer['file'] ?></td>
                                        <td>
                                            <button type="button" value="<?=$reviewer['id'];?>" class="deleteReviewer btn btn-danger btn-sm">Delete</button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <script>
        $(document).on('submit', '#saveReviewer', function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("save_reviewer", true);

            $.ajax({
                type: "POST",
                url: "code.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    
                    var res = jQuery.parseJSON(response);
                    if(res.status == 422) {
                        $('#errorMessage').removeClass('d-none');
                        $('#errorMessage').text(res.message);

                    }else if(res.status == 200){

                        $('#errorMessage').addClass('d-none');
                        $('#reviewerAddModal').modal('hide');
                        $('#saveReviewer')[0].reset();

                        alertify.set('notifier','position', 'top-right');
                        alertify.success(res.message);

                        $('#myTable').load(location.href + " #myTable");

                    }else if(res.status == 500) {
                        alert(res.message);
                    }
                }
            });

        });

        $(document).on('click', '.deleteReviewer', function (e) {
            e.preventDefault();

            if(confirm('Are you sure you want to delete this data?'))
            {
                var reviewer_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "code.php",
                    data: {
                        'delete_reviewer': true,
                        'reviewer_id': reviewer_id
                    },
                    success: function (response) {

                        var res = jQuery.parseJSON(response);
                        if(res.status == 500) {

                            alert(res.message);
                        }else{
                            alertify.set('notifier','position', 'top-right');
                            alertify.success(res.message);

                            $('#myTable').load(location.href + " #myTable");
                        }
                    }
                });
            }

            //file type validation
            $("#file").change(function() {
                var file = this.files[0];
                var uploadedFileType = file.type;
                var match = ['application/pdf', 'application/msword', 'application/vnd.ms-office'];
                if(!((uploadedFileType==match[0]) || (uploadedFileType==match[1]) || (uploadedFileType==match[2]))){
                    alert('Please select a valid file (PDF/DOC/DOCX).');
                    $("#file").val('');
                    return false;
                }
            });
        });

    </script>

</body>
</html>