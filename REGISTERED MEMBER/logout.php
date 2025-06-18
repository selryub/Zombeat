<?php
session_start();
session_unset();
session_destroy();



header("Location: ../REGISTERED MEMBER/login.php");
exit();