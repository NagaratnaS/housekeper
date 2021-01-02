<?php
  session_start();
  require("db.php");

  // ================== Clean Request Handler =================== //
  if(isset($_POST['reqSubmit']) && isset($_SESSION['rollnumber'])){
    $rollnumber = $_SESSION['rollnumber'];
    $reqDate = mysqli_real_escape_string($db, $_POST['reqDate']);
    $reqTime = mysqli_real_escape_string($db, $_POST['reqTime']);
    // Format Date Before Submission
    $reqdate = date('Y-m-d', strtotime($reqDate));
    // $reformatted_time = date('H:i:s',strtotime($reqTime));
    $reqid = mt_rand(10000,999999);// To generate the request id. To make it unique I have made it as a large set of numbers

    $req_query = "insert into cleanrequest(request_id,rollnumber,date,cleaningtime) values ('$reqid','$rollnumber','$reqdate','$reqTime')";
    $req_result = mysqli_query($db,$req_query);
    if ($req_result) {
      $_SESSION['req_sent'] = "Cleaning Request is sent for ".$reqdate." ".$reqTime;
    }else {
      $_SESSION['req_failed'] = "Request Can not be sent. Please contact administrator";
    }
    header("Location: request.php");
  }

  // ================== Feedback Handler =================== //
  else {

    $rollnumber = $_SESSION['rollnumber'];
    $feedreqid = mysqli_real_escape_string($db, $_POST['feedReqid']);
    $feedrating = mysqli_real_escape_string($db, $_POST['feedRating']);
    $feedtimein = mysqli_real_escape_string($db, $_POST['feedTimein']);
    $feedtimeout = mysqli_real_escape_string($db, $_POST['feedTimeout']);
    $feedsuggestion = mysqli_real_escape_string($db, $_POST['feedSuggestion']);
    $feedcomplaints = mysqli_real_escape_string($db, $_POST['feedComplaints']);
    $feedbackid = mt_rand(100000,999999); 
    $feed_query = "INSERT into feedback(feedback_id,rollnumber,request_id,rating,timein,timneout) values ('$feedbackid','$rollnumber','$feedreqid','$feedrating','$feedtimein','$feedtimeout')";

    // Submit Feedback
    $feed_result = mysqli_query($db, $feed_query);

    // Increment Rooms Cleaned and req status
    $workerid = mysqli_query($db, "SELECT worker_id from cleanrequest where request_id='$feedreqid'");
    $workerid2 = mysqli_fetch_assoc($workerid);
    $workerid3 = $workerid2['worker_id'];
    mysqli_query($db, "Update housekeeper set rooms_cleaned = rooms_cleaned + 1 where worker_id = '$workerid3'");//inicrementing the number of rooms cleaned
    mysqli_query($db, "Update cleanrequest set req_status = 2 where request_id = '$feedreqid'");

    if ($feed_result) {
      $_SESSION['feed_sent'] = "Feedback is sent for request id - ".$feedreqid;
    }

    $feedid = mysqli_query($db, "SELECT feedback_id from feedback where request_id='$feedreqid'");
    $feedid2 = mysqli_fetch_assoc($feedid);
    $feedid3 = $feedid2['feedback_id'];

    if($feedsuggestion != ""){
      $suggestion_id = mt_rand(100000,999999);
      $suggest_query = "INSERT into suggestions(suggestion_id,feedback_id,rollnumber,suggestion) values ('$suggestion_id','$feedbackid','$rollnumber','$feedsuggestion')";
      $suggest_result = mysqli_query($db, $suggest_query);
    }

    if($feedcomplaints != ""){
      $complaint_id = mt_rand(100000,999999);
      $complaint_query = "INSERT into complaints(complaint_id,feedback_id,rollnumber,complaint) values ('$complaint_id','$feedbackid','$rollnumber','$feedcomplaints')";
      $complaint_result = mysqli_query($db, $complaint_query);
      
      mysqli_query($db, "Update housekeeper set complaints = complaints + 1 where worker_id = '$workerid3'");
    }
    header("Location: feedback.php");
  }
?>