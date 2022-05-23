<?php
session_start();
date_default_timezone_set("Asia/Kolkata"); 
require_once "vendor/config.php";
include_once('connection.php');

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
$site_url = "https://";   
else  
$site_url = "http://";   
$site_url.= $_SERVER['HTTP_HOST'];   
$site_url.= $_SERVER['REQUEST_URI']; 

function validate() {
    if (isset($_SESSION['google_id']) && isset($_SESSION['username']) && isset($_SESSION['email']) && isset($_SESSION['imagepath'])) {
        if (isset($_COOKIE['goole_id'])) {
            if ($_SESSION['google_id'] != $_COOKIE['google_id']) {
                logout();
                header('location: https://sabiduria.in/');
            }
        }
    }
    else {
        if (isset($_COOKIE['google_id']) && isset($_COOKIE['username']) && isset($_COOKIE['email']) && isset($_COOKIE['imagepath'])) {
            $_SESSION['google_id'] = $_COOKIE['google_id'];
            $_SESSION['username'] = $_COOKIE['username'];
            $_SESSION['email'] = $_COOKIE['email'];
            $_SESSION['imagepath'] = $_COOKIE['imagepath'];
        }
    }
    return true;
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

function sendError($error) {
    global $conn;
    $conn = new mysqli('localhost', 'u596252984_articles' , 'Nancy3690#');
    mysqli_select_db($conn, 'u596252984_articles');

    $message = mysqli_real_escape_string($conn, $error);
    $path = mysqli_real_escape_string($conn, urldecode("https://sabiduria.in/index.php"));
    $gotdate = date("Y/m/d h:i:s");
    
    $insert_error = $conn->prepare("INSERT INTO `errorlogs` (`errormessage`, `errorpath`, `gotdate`) VALUES (?, ?, ?)");
    $insert_error->bind_param("sss", $message, $path, $gotdate);
    $insert_error->execute();
    $insert_error->close();
    
    $conn = new mysqli('localhost', 'u596252984_sabiduria' , 'Nancy3690#');
    mysqli_select_db($conn, 'u596252984_agency');


    return true;
}

validate();

if (isset($_SESSION['google_id']) && isset($_SESSION['username']) && isset($_SESSION['email']) && isset($_SESSION['imagepath'])) {
    try {
        $select_data = $conn->prepare("SELECT `google_id`, `firstname`, `lastname`, `email`, `imagepath`, `access_token`,    `refresh_token` FROM `userslist` WHERE `google_id` = ?");
        $select_data->bind_param("s", $_SESSION['google_id']);
        if ($select_data->execute()) { 
        $result_data = $select_data->get_result();
            if ($result_data->num_rows > 0) {
                $row = $result_data->fetch_assoc();
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $username = $firstname . ' ' . $lastname; 
                $email = $row['email'];
                $imagepath = $row['imagepath'];
            }
        else {
            sendError("Unable to get data. Reason:" . $conn->error);
            logout();
        }
        mysqli_free_result($result_data);
    }
    else {
        sendError("Unable to get data. Reason:" . $conn->error);
    }
    }
    catch(exception $e) {
        sendError("Unable to get data(catch). Reason:" . $e->getMessage());
        logout();
    }
} else {
    logout();
}

$varun_heart_count = 0;
$pavan_heart_count = 0;
$anwar_heart_count = 0;
$varun = "varun";
$pavan = "pavan";
$anwar = "anwar";
$love_varun = "no";
$love_pavan = "no";
$love_anwar = "no";

$conn = new mysqli('localhost', 'u596252984_sabiduria' , 'Nancy3690#');
mysqli_select_db($conn, 'u596252984_agency');

try {
    if ($select_data = $conn->prepare("SELECT `email` FROM `love` WHERE `member` = ?")) {
        $select_data->bind_param("s", $varun);
        if ($select_data->execute()) { 
            $result_data = $select_data->get_result();
            if ($result_data->num_rows > 0) {
                $row = mysqli_num_rows($result_data);
                $varun_heart_count = $row;
            }
            else {
            sendError("Unable to get data. Reason:" . $conn->error);
            }
            mysqli_free_result($result_data);
        }
        else {
            sendError("Unable to get data. Reason:" . $conn->error);
        }
    }

    if ($select_data = $conn->prepare("SELECT `email` FROM `love` WHERE `member` = ?")) { 
        $select_data->bind_param("s", $pavan);
        if ($select_data->execute()) { 
            $result_data = $select_data->get_result();
            if ($result_data->num_rows > 0) {
                $row = mysqli_num_rows($result_data);
                $pavan_heart_count = $row;
            }
            else {
            sendError("Unable to get data. Reason:" . $conn->error);
            }
            mysqli_free_result($result_data);
        }
        else {
            sendError("Unable to get data. Reason:" . $conn->error);
        }
    }

    if ($select_data = $conn->prepare("SELECT `email` FROM `love` WHERE `member` = ?")) { 
        $select_data->bind_param("s", $anwar);
        if ($select_data->execute()) { 
            $result_data = $select_data->get_result();
            if ($result_data->num_rows > 0) {
                $row = mysqli_num_rows($result_data);
                $anwar_heart_count = $row;
            }
            else {
            sendError("Unable to get data. Reason:" . $conn->error);
            }
            mysqli_free_result($result_data);
        }
        else {
            sendError("Unable to get data. Reason:" . $conn->error);
        }
    }

    if (isset($_SESSION['email'])) {
        if ($select_data = $conn->prepare("SELECT `email` FROM `love` WHERE `email` = ? AND `member` = ?")) { 
            $select_data->bind_param("ss", $_SESSION['email'], $varun);

            if ($select_data->execute()) { 
                $result_data = $select_data->get_result();
                if ($result_data->num_rows > 0) {
                    $love_varun = 'yes';
                }
                else {
                sendError("Unable to get data. Reason:" . $conn->error);
                }
                mysqli_free_result($result_data);
            }
            else {
                sendError("Unable to get data. Reason:" . $conn->error);
            }
        }

        if ($select_data = $conn->prepare("SELECT `email` FROM `love` WHERE `email` = ?  AND `member` = ?")) { 
            $select_data->bind_param("ss", $_SESSION['email'], $pavan);
            if ($select_data->execute()) { 
                $result_data = $select_data->get_result();
                if ($result_data->num_rows > 0) {
                    $love_pavan = 'yes';
                }
                else {
                sendError("Unable to get data. Reason:" . $conn->error);
                }
                mysqli_free_result($result_data);
            }
            else {
                sendError("Unable to get data. Reason:" . $conn->error);
            }
        }

        if ($select_data = $conn->prepare("SELECT `email` FROM `love` WHERE `email` = ?  AND `member` = ?")) { 
            $select_data->bind_param("ss", $_SESSION['email'], $anwar);
            if ($select_data->execute()) { 
                $result_data = $select_data->get_result();
                if ($result_data->num_rows > 0) {
                    $love_anwar = 'yes';
                }
                else {
                sendError("Unable to get data. Reason:" . $conn->error);
                }
                mysqli_free_result($result_data);
            }
            else {
                sendError("Unable to get data. Reason:" . $conn->error);
            }
        }
    } 
}
catch(exception $e) {
    sendError("Unable to get data(catch). Reason:" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PNRGJH3');</script>
<!-- End Google Tag Manager -->

<title>Sabiduria - Where Expectation Meets Reality</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale = 1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="distribution" content="global">
<meta http-equiv="content-language" content="en-gb">
<meta name="designer" content="Sabiduria">
<meta name="publisher" content="Sabiduria">
<meta name="title" content="Sabiduria - Where Expectation Meets Reality">
<meta name="description" content="">
<meta name="robots" content="index, follow">
<meta name="bingbot" content="index, follow">
<meta name="googlebot" content="index, follow">
<link rel="shortlink" href="https://sabiduria.in/">
<link rel="canonical" href="https://sabiduria.in/">
<link rel="canonical" href="https://sabiduria.in/">
<meta property="og:locale" content="en_US">
<meta property="og:type" content="website">
<meta property="og:title" content="Sabiduria - Where Expectation Meets Reality">
<meta property="og:description" content="">
<meta property="og:image" content="https://sabiduria.in/images/sabiduria-logo.jpg">
<meta property="og:url" content="https://sabiduria.in/">
<meta property="og:site_name" content="Sabiduria">
<meta property="article:published_time" content="2020-08-17T12:01:00+00:00">
<meta property="article:modified_time" content="2021-01-10T12:08:00+00:00">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Sabiduria - Where Expectation Meets Reality">
<meta name="twitter:description" content="">
<meta name="twitter:image" content="https://sabiduria.in/images/sabiduria-logo.jpg">
<meta name="twitter:site" content="@sabiduria">
<meta name="twitter:creator" content="@Sabiduria_in">
<link rel="shortcut icon" href="https://sabiduria.in/images/favicon.ico" type="image/x-icon">
<link rel="apple-touch-icon" href="https://sabiduria.in/images/sabiduria-logo.jpg">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://accounts.google.com/gsi/client"></script>
<script async="" src="https://sabiduria.in/js/sabiduria.min.js"></script>
<!--
<script async="" src="https://sabiduria.in/js/sabiduria.min.js"></script>
-->


<script src="https://kit.fontawesome.com/44e31154b7.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://sabiduria.in/css/sabiduria.min.css">
<!--
<link rel="stylesheet" type="text/css" href="https://sabiduria.in/css/sabiduria.min.css">
-->

<noscript>
<link rel="stylesheet" href="https://sabiduria.in/css/noscript.css">
</noscript>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PNRGJH3"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <div class = "total_page">
        <header class = "header">
            <div class = "header-body">
                <div class = "logo"><img src = "https://sabiduria.in/images/sabiduria-logo.jpg"  itemprop = "logo" alt = "sabiduria logo" title = "sabiduria logo" class = "sabiduria-icon"/></div>
                <div class = "menu-items">
                <ul class = "menu-ul">
                <?php
                    if (isset($_SESSION['google_id']) && isset($_SESSION['username']) && isset($_SESSION['email']) && isset($_SESSION['imagepath'])) { 
                        echo '<li class = "user-profile" id = "user-profile">';
                        echo '<div class = "profile-pic"><img src = '.$_SESSION['imagepath'].' alt = '.$firstname.$lastname.'></img></div>';
                        echo '<div class = "caret-down-div"><i class = "fa fa-caret-down" id = "caret-down-icon"></i></div>';
                        echo '</li>';
                        echo '<div class = "user-popup-panel hide" id = "user-popup-panel">';
                        echo '<div class = "user-details">';
                        echo '<div class = "user-image">';
                        echo '<img src = '.$_SESSION['imagepath'].' alt = '.$firstname.$lastname.'></img>';
                        echo '</div>';
                        echo '<div class = "user-name-email">';
                        echo '<div class = "user-name">';
                        echo '<span><i class = "fa fa-at at-user"></i></span><span>'.$_SESSION["username"].'</span>';
                        echo '</div>';
                        echo '<div class = "user-email">';
                        echo $_SESSION['email'];
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class = "user-popup-footer">';
                        echo '<div class = "logout-button">';
                        echo '<a href = "https://sabiduria.in/logout-agency.php">logout</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '<li class = "write-article-li"><a href="https://sabiduria.in/editor" rel = "dofollow" target = "_blank" class = "write-articles"><i class = "fa fa-edit header-icons"></i>Write</a></li>';
                    }
                    else {
                        echo '<li class = "get-started-li"><a href="#" id = "get-started" class = "get-started orange-bg">Login/Sign Up</a></li>';
                        $_SESSION['referrer'] = urldecode($site_url);
                    }
                    ?>
                </ul>
                </div>
            </div>
        </header>

        <section class = "register-section" id = "register-section">
            <div class = "register-box-outer">
                <div class = "register-box">
                <button type = "button" id = "close-register" class = "close-register"><i class = "fa fa-close"></i></button>
                    <div class = "register-box-inner">
                    <div class = "register-label">Join Or Start Sabiduria With</div>
                    <div class = "register-sub-label"><p>We strongly believe everyone deserves to lead properous and quality life which is only possible by minding your own business. Join sabiduria and we help you to grow in life.</p></div>
                    <div class = "give-love hide-content" id = "give-love"><p>Login to give your love</p></div>
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
                    
        <section class = "banner-section">
            <div class = "banner">
                <div class = "right-panel">
                    <div class = "heading">
                        <p>9 out of 10 business fail because they don't know how to sell.</p>
                    </div>
                    <div class = "sub-heading">
                        <p>We know how to reach your potential customer at right time with right message</p>
                    </div>
                    <div class = "sub-sub-heading">
                        <p>Sabiduria help small business In Marketing For Free Upto 2 Months. We make you good sales, enough to hire us after these 2 Months. Schedule a meeting with us and we show you where you are loosing.</p>
                    </div>
                    <div class = "cta-button-in-banner">
                        <a class = "free-audit" href = "#schedule-meeting">Schedule Meeting For Free Audit</a>
                    </div>
                </div>
                <div class = "left-panel">
                   <img class = "startup-image" alt = "Sabiduria Marketing Agency Helping Small Business" src = "https://sabiduria.in/images/sabiduria-developing-startups-d.png"/> 
                   <img class = "startup-image2" alt = "Sabiduria Marketing Agency Helping Startups" src = "https://sabiduria.in/images/sabiduria-developing-startups-mob.png"/>                 
                </div>
            </div>
        </section>

        <?php include_once('estimation.php'); ?>

        <section class = "clients-section">
            <div class = "clients">
                <div class = "clients-heading-div"><p class = "our-clients-heading">Companies invested in our work</p></div>
                <div class = "clients-images-outer-block">
                    <div class = "clients-images-inner-block">
                        <div class = "clients-images-div">
                            <a href = "https://homexrepair.com/" target = "_blank"><img class = "clients-image homexrepair-image" src = "https://sabiduria.in/images/homexrepair-logo.png"/></a>
                        </div>
                        <div class = "clients-images-div">
                            <a href = "http://manamlg.store/" target = "_blank"><img class = "clients-image manamlg-image" src = "https://sabiduria.in/images/manamlg-logo.jpg"/></a>
                        </div>
                        <div class = "clients-images-div">
                            <a href = "http://djshady.online/" target = "_blank"><img class = "clients-image djshady-image" src = "https://sabiduria.in/images/djshady-logo.png"/></a>
                        </div>
                        <div class = "clients-images-div">
                            <a href = "https://www.facebook.com/Rythu-Agri-Tech-114451743647179/"><img class = "clients-image rythu-gree-houses-image" src = "https://sabiduria.in/images/rythu-green-houses-logo.png"/></a>
                        </div>
                        <div class = "clients-images-div">
                            <a href = "https://www.asmaservices.com/" target = "_blank"><img class = "clients-image asma-image" src = "https://sabiduria.in/images/asma-services-logo.png"/></a>
                        </div>
                        <div class = "clients-images-div">
                            <a href = "http://dkrinfo.tech/" target = "_blank"><img class = "clients-image dkrinfo-image" src = "https://sabiduria.in/images/dkrinfo-logo.png"/></a>
                        </div>
                        <div class = "clients-images-div">    
                            <a href = "#"><img class = "clients-image sbrs-image" src = "https://sabiduria.in/images/sbrs-tax-consultancy-logo.jpg"/></a>
                        </div>
                        <div class = "clients-images-div">
                            <a href = "#"><img class = "clients-image larks-image" src = "https://sabiduria.in/images/larks-mens-tailor-logo.jpg"/></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class = "our-team-section">
            <div class = "our-team">
                <div class = "our-team-heading">Our Team</div>
                <div class = "our-team-content">
                    <div class = "varun-teja-section">
                        <div class = "pubg-image-div">
                            <img class = "varun-teja-pubg-image" src = "https://sabiduria.in/images/marneni-varun-teja-pubg-character.png"/>
                        </div>
                        
                        <div class = "original-image-div varun-teja-div">
                            <img class = "varun-teja-image" src = "https://sabiduria.in/images/marneni-varun-teja-image.jpg"/>
                            <img src = "https://sabiduria.in/images/pubg-frame.png" class = "gold-frame"/>
                            <div class = "profile">
                                <div class = "varun-teja-name">Varun Teja Marneni</div>
                                <div class = "social-media">
                                    <a href = "" class = "varun-teja-linkedin"><i class="fab fa-linkedin"></i></a>
                                    <a href = "" class = "varun-teja-github"><i class="fab fa-github"></i></a>
                                    <button class = "varun-heart heart" id = "varun-heart" data-love-varun = <?php echo $love_varun; ?> data-id = "varun"><i class="fas fa-heart"></i></button>
                                    <span id = "varun-heart-count" class = "heart-count"><?php echo $varun_heart_count; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class = "pavan-mathew-section">
                        <div class = "pubg-image-div">
                            <img class = "varun-teja-pubg-image" src = "https://sabiduria.in/images/pavan-mathew-pubg-character.png"/>
                        </div>
                        
                        <div class = "original-image-div pavan-mathew-div">
                            <img class = "varun-teja-image" src = "https://sabiduria.in/images/pavan-mathew-image.jpg"/>
                            <img src = "https://sabiduria.in/images/pubg-frame.png" class = "gold-frame"/>
                            <div class = "profile">
                                <div class = "varun-teja-name">Pavan Mathew</div>
                                <div class = "social-media">
                                    <a href = "" class = "varun-teja-linkedin"><i class="fab fa-linkedin"></i></a>
                                    <a href = "" class = "varun-teja-github"><i class="fab fa-github"></i></a>
                                    <button class = "pavan-heart heart" id = "pavan-heart" data-love-pavan = <?php echo $love_pavan; ?> data-id = "pavan"><i class="fas fa-heart"></i></button>
                                    <span id = "pavan-heart-count" class = "heart-count"><?php echo $pavan_heart_count; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class = "anwar-pasha-section">
                        <div class = "pubg-image-div">
                            <img class = "varun-teja-pubg-image" src = "https://sabiduria.in/images/mohammad-anwar-pasha-pubg-character.png"/>
                        </div>
                        
                        <div class = "original-image-div anwar-pasha-div">
                            <img class = "varun-teja-image" src = "https://sabiduria.in/images/mohammad-anwar-pasha-image.jpg"/>
                            <img src = "https://sabiduria.in/images/pubg-frame.png" class = "gold-frame"/>
                            <div class = "profile">
                                <div class = "varun-teja-name">Anwar Pasha</div>
                                <div class = "social-media">
                                    <a href = "#" class = "varun-teja-linkedin"><i class="fab fa-linkedin"></i></a>
                                    <a href = "#" class = "varun-teja-github"><i class="fab fa-github"></i></a>
                                    <button class = "anwar-heart heart" id = "anwar-heart" data-love-anwar = <?php echo $love_anwar; ?> data-id = "anwar"><i class="fas fa-heart"></i></button>
                                    <span id = "anwar-heart-count" class = "heart-count"><?php echo $anwar_heart_count; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>

        <section class = "services-section">
            <div class = "services">
                <div class = "services-label"><p>What we do?</p></div>
                <div class = "services-outer-panel">
                    <div class = "ppc-panel services-inner-panel">
                        <div class = "services-top-panel">
                            <img src = "https://sabiduria.in/images/pay-per-click-advertising.jpg" class = "services-top-image">
                        </div>
                        <div class = "services-left-panel services-image-panel">
                            <img src = "https://sabiduria.in/images/pay-per-click-advertising.jpg" class = "services-ppc-image services-left-align-image">
                        </div>
                        <div class = "services-right-panel services-left-content-panel">
                            <div class = "services-ppc-heading services-left-align-heading"><p>Paid Advertising</p></div>
                            <div class = "services-ppc-content services-left-align-content">
                                <div><p>It takes a bunch of years to get to page 1 on search engines and the hard truth is you cannot defeat megacorp's deep-rooted in your niche directly. The other way of getting customers and building your brand is through paid advertising. It gives you good results in a shorter time. But due to the heavy competition, your PPC campaigns in an expert hand will make you the most of every penny you spend. We play with good strategies and make your conversion rate increase.<br/><br/></p>
                                    <p>As we don't concentrate on leads instead we concentrate on customers you are in the safe hands. What are you waiting for? Schedule a meeting to talk with an expert.</p></div>
                                <div class = "hide-content"><a href = "#">Know More <i class="fas fa-hand-point-right"></i></a></div>
                            </div>
                            <div class = "services-ppc-button services-left-align-button"><a href = "#schedule-meeting">Schedule Meeting</a></div>
                        </div>
                    </div>

                    <div class = "marketing-automation-panel services-inner-panel">
                        <div class = "services-top-panel">
                            <img src = "https://sabiduria.in/images/marketing-automation.jpg" class = "services-top-image">
                        </div>
                        <div class = "services-left-panel services-right-content-panel">
                            <div class = "services-marketing-automation-heading services-left-align-heading"><p>Marketing Automation</p></div>
                            <div class = "services-marketing-automation-content services-left-align-content">
                                <div><p>Marketing Automation makes life easy. While everything is automated in this world then why do you manage your digital work manually and lose conversions? With marketing automation, you can reduce the customer conversion time and watch the conversion rate increase.<br/><br/></p>
                                
                                <p>With us you can automate your work with the tools like Hubspot, Mailchimp, Zapier, Sprout social, Convertkit, ActiveCampaign, Stripe, Klaviyo, Mailgun, Constant Contact, Zoho, Omnisend, Autopilot, and many other tools.</p></div>
                                <div class = "hide-content"><a href = "#">Know More <i class="fas fa-hand-point-right"></i></a></div>
                            </div>
                            <div class = "services-marketing-automation-button services-left-align-button"><a href = "#schedule-meeting">Schedule Meeting</a></div>
                        </div>
                        <div class = "services-right-panel services-image-panel">
                            <img src = "https://sabiduria.in/images/marketing-automation.jpg" class = "services-marketing-automation-image services-right-align-image">
                        </div>
                    </div>

                    <div class = "dynamic-marketing-panel services-inner-panel">
                        <div class = "services-top-panel">
                            <img src = "https://sabiduria.in/images/dynamic-marketing.jpg" class = "services-top-image">
                        </div>
                        <div class = "services-left-panel services-image-panel">
                            <img src = "https://sabiduria.in/images/dynamic-marketing.jpg" class = "services-dynamic-marketing-image services-left-align-image">
                        </div>
                        <div class = "services-right-panel services-left-content-panel">
                            <div class = "services-dynamic-marketing-heading services-left-align-heading"><p>Dynamic Marketing</p></div>
                            <div class = "services-dynamic-marketing-content services-left-align-content">
                                <div><p>Travel to the future before the world does.<br/><br/></p>
                                <p>With dynamic marketing, we will customize and personalize your website, ads, and your digital appearance and deliver to the right user. For example, you are handling an event management company and you provide services like photography, DJ for marriages, concerts, and birthday parties. We show your ad and landing page dedicated to 'Photographers for wedding party' to the customers who are only searching for photographers for their wedding party.<br/><br/></p>
                                <p>With dynamic marketing, you can personalize your business and will have a good bonding with the customers, get cold leads, and get more customer conversion rates. Schedule a meeting with an expert to implement dynamic marketing into your business.</p></div>
                                <div class = "hide-content"><a href = "#">Know More <i class="fas fa-hand-point-right"></i></a></div>
                            </div>
                            <div class = "services-dynamic-marketing-button services-left-align-button"><a href = "#schedule-meeting">Schedule Meeting</a></div>
                        </div>
                    </div>

                    <div class = "rap-panel services-inner-panel">
                        <div class = "services-top-panel">
                            <img src = "https://sabiduria.in/images/research-analysis-planning.jpg" class = "services-top-image">
                        </div>
                        <div class = "services-left-panel services-right-content-panel">
                            <div class = "services-rap-heading services-left-align-heading"><p>Research, Analysis & Planning</p></div>
                            <div class = "services-rap-content services-left-align-content">
                                <div><p>If you are getting a lot of traffic but this traffic is not converting to customers then you are losing your money because of the small issues.<br/><br/></p>
                                <p>With research, analysis & planning we analyze your traffic sources like paid, SEO, social, email, etc and we give you a detailed analysis of where you are losing? How can you improve your conversion rate? Planning, Strategies, etc.</p></div>
                                <div class = "hide-content"><a href = "#">Know More <i class="fas fa-hand-point-right"></i></a></div>
                            </div>
                            <div class = "services-rap-button services-left-align-button"><a href = "#schedule-meeting">Schedule Meeting</a></div>
                        </div>
                        <div class = "services-right-panel services-image-panel">
                            <img src = "https://sabiduria.in/images/research-analysis-planning.jpg" class = "services-rap-image services-right-align-image">
                        </div>
                    </div>

                </div>
            </div> 
        </section>

        <section class = "calendly-section" id = "schedule-meeting">
            <!-- Calendly inline widget begin -->
            <div class="calendly-inline-widget" data-url="https://calendly.com/sabiduria/schedule-meeting-and-audit" style="position: relative;min-width:320px;height:750px;"></div>
            <script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>
            <!-- Calendly inline widget end -->
        </section>

        <?php include_once('footer.php'); ?>
    </div>
</body>
</html>
