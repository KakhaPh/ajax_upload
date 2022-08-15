<?php

require 'dbcon.php';

if(isset($_POST['save_reviewer']))
{
    $rank = mysqli_real_escape_string($con, $_POST['rank']);
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $university = mysqli_real_escape_string($con, $_POST['university']);
    
    if($rank == NULL || $fname == NULL || $lname == NULL || $email == NULL || $university == NULL || $_FILES['file']['name'] == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are Required'
        ];
        echo json_encode($res);
        return;
    }

    $uploadedFile = '';
    if(!empty($_FILES["file"]["type"])){
        $fileName = time().'_'.$_FILES['file']['name'];
        $valid_extensions = array("pdf", "doc", "docx");
        $temporary = explode(".", $_FILES["file"]["name"]);
        $file_extension = end($temporary);
        if((($_FILES["file"]["type"] == "application/pdf") || ($_FILES["file"]["type"] == "application/msword") || ($_FILES["file"]["type"] == "application/vnd.ms-office")) && in_array($file_extension, $valid_extensions)){
            $sourcePath = $_FILES['file']['tmp_name'];
            $targetPath = "storage/cv/".$fileName;
            if(move_uploaded_file($sourcePath,$targetPath)){
                $uploadedFile = $fileName;
            }
        }
    }

    $query = "INSERT INTO reviewers (rank,fname,lname,email,university,file) VALUES ('$rank','$fname','$lname','$email','$university','$uploadedFile')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'Your request has been sent'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Something went wrong!'
        ];
        echo json_encode($res);
        return;
    }
}



if(isset($_POST['delete_reviewer']))
{
    $reviewer_id = mysqli_real_escape_string($con, $_POST['reviewer_id']);

    $query = "DELETE FROM reviewers WHERE id='$reviewer_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'reviewer Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'reviewer Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
}



?>