<?php
session_start();  //must be called before using any session fx
session_unset();  //unset all session variables (clears $_SESSION array)
session_destroy(); //Destroy the session entirely (remove session data)

//Redirect to the login page after logging out
header("Location: ../login.php");
exit();