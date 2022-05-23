<?php
 session_start();
date_default_timezone_set("Asia/Kolkata"); 
include_once('../connection.php');
 require_once "../config.php";

 if (isset($_SESSION['google_id']) && isset($_SESSION['username']) && isset($_SESSION['email']) && isset($_SESSION['imagepath'])) {
    header('location: https://sabiduria.in/blogger/');
 }
 else {
    if (isset($_COOKIE['google_id']) && isset($_COOKIE['username']) && isset($_COOKIE['email']) && isset($_COOKIE['imagepath'])) {
        $_SESSION['google_id'] = $_COOKIE['google_id'];
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['email'] = $_COOKIE['email'];
        $_SESSION['imagepath'] = $_COOKIE['imagepath'];
        header('location: https://sabiduria.in/blogger/');
    }
    else {
       logout();
    }
 }  

function logout() {
    global $client;
        
    setcookie("google_id", "", time()-3600, "/");
    setcookie("username", "", time()-3600, "/"); 
    setcookie("email", "", time()-3600, "/"); 
    setcookie("imagepath", "", time()-3600, "/"); 
    
    try {
        if (isset($_SESSION['google_id']) || isset($_SESSION['username']) || isset($_SESSION['email']) || isset($_SESSION['imagepath'])) {
        unset($_SESSION['google_id']);
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        unset($_SESSION['imagepath']);
    
        $client->revokeToken();
        session_destroy();
        }
    }
    catch(exception $e) {
    
    }
}
?>

<html>
    <head>
        <title>Sabiduria Articles - Login</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale = 1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<meta name="title" content="Sabiduria Articles - Login">
<meta name="author" content="Varun Teja">
<meta name="keywords" content="sabiduria, developebusiness, blogs, articles">
<meta name="description" content="Sabiduria Blogger helps in developing business and personal branding with quality blogs and helps the people to improves their knowledge.">
<link rel="prefetch" href="https://sabiduria.in/login/">
<link rel="prerender" href="https://www.sabiduria.in/login/">

<meta property="og:title" content="Sabiduria Articles - Login">
<meta property="og:type" content="website">
<meta property="og:url" content="https://sabiduria.in/login">
<meta property="og:site_name" content="sabiduria">
<meta property="og:description" content="Sabiduria Blogger helps in developing business and personal branding with quality blogs and helps the people to improves their knowledge.">
<meta property="og:image" content="https://res.cloudinary.com/djshady-online/image/upload/v1567446842/business/sabiduriaIcon_ctsn1m.jpg">
<meta property="og:image:type" content="image/jpg">
<meta property="og:image:alt" content="sabiduria">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@sabiduria">
<meta name="twitter:creator" content="@sabiduria">
<meta name="twitter:domain" content="sabiduria.in">
<meta name="twitter:url" content="https://sabiduria.in/login">
<meta name="twitter:title" content="Sabiduria Articles - Login">
<meta name="twitter:description" content="Sabiduria Blogger helps in developing business and personal branding with quality blogs and helps the people to improves their knowledge.">
<meta name="twitter:image:src" content="https://res.cloudinary.com/djshady-online/image/upload/v1567446842/business/sabiduriaIcon_ctsn1m.jpg">
<meta name="twitter:image" content="https://res.cloudinary.com/djshady-online/image/upload/v1567446842/business/sabiduriaIcon_ctsn1m.jpg">

<meta itemprop="image" content="https://res.cloudinary.com/djshady-online/image/upload/v1567446842/business/sabiduriaIcon_ctsn1m.jpg">

        <link rel = "stylesheet" type = "text/css" href = "https://sabiduria.in/css/login.min.css"/> 
    </head>
    <body>
        <section class = "register-section">
            <div class = "register-box-outer">
                <div class = "register-box">
                    <div class = "register-box-inner">
                    <div class = "register-label">Join Or Start Sabiduria With</div>
                    <div class = "register-sub-label"><p>We strongly believe everyone deserves to lead properous and quality life which is only possible by minding your own business. Join sabiduria and we help you to grow in life.</p></div>
                    <div class = "register-with-social-media">
                        <?php echo '<a href = "'.$client->createAuthUrl().'" class = "joining-options join-with-google">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48" class="abcRioButtonSvg"><g><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path><path fill="none" d="M0 0h48v48H0z"></path></g></svg>
                    <span class = "company-name">Google</span>
                    </a>'; 
                    ?>
                    </div>
                    <div class = "register-footer-label"><p>By joining sabiduria, You agree to our <a href = "#">Terms And Conditions</a> and that you have read <a href="#">Data Use Policy</a>, including our <a href = "#">Cookie Use.</a></p></div>
                </div>
                </div>
            </div>
        </section>
    </body>
</html>

        