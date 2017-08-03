<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <title>HAWK | Create Account</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="<?php echo base_url('assets/icons/font-awesome/css/font-awesome.css')?>" rel="stylesheet">
    <!-- Themify Icons -->
    <link href="<?php echo base_url('assets/icons/themify/themify-icons.css')?>" rel="stylesheet">
    <!-- IonIcons Pack -->
    <link href="<?php echo base_url('assets/icons/ionicons-2.0.1/css/ionicons.min.css')?>" rel="stylesheet">
    <!-- Awesome Bootstrap Checkboxes -->
    <link href="<?php echo base_url('assets/css/awesome-bootstrap-checkbox.css')?>" rel="stylesheet">
    <!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
    <script src="<?php echo base_url('assets/landing/js/skel.min.js')?>"></script>
    <script src="<?php echo base_url('assets/landing/js/init.js')?>"></script>
    <link href="<?php echo base_url('assets/landing/css/font-awesome.min.css')?>" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/landing/css/style.css')?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/landing/css/style-wide.css')?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/landing/css/skel.css')?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/landing/css/style-noscript.css')?>" />
    <link href="<?php echo base_url('assets/css/styles/custom.css')?>" rel="stylesheet" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/system/hawk_logo.png">
    <link href="<?php echo base_url('assets/css/sweetalert.css') ?>" rel="stylesheet">
    <!--[if lte IE 9]><link rel="stylesheet" href="<?php echo base_url('assets/landing/css/ie/v9.css')?>" /><![endif]-->
    <!--[if lte IE 8]><link rel="stylesheet" href="<?php echo base_url('assets/landing/css/ie/v8.css')?>" /><![endif]-->
    <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js')?>"></script>
</head>

<body class="loading">
    <div id="wrapper">
        <div id="bg"></div>
        <div id="main">
            <center>
                <form role="form" id="create-form">
                    <!-- Logo -->
                    <div class="hawk_logo_create"></div>
                    <fieldset>
                        <div class="input-group">
                            <label> <span class="fa fa-m fa-map"></span>
                                <select id="select_type">
                                    <option value="1">Personal Account</option>
                                    <option value="2">Business Account</option>
                                </select>
                            </label>
                        </div>
                        <!-- Personal -->
                        <div id="personal">
                            <div id="spacer"></div>
                            <div class="input-group"> <span class="input-group-addon" id="basic-addon1">
                                    <span class="fa fa-m fa-user"></span> <span class="line">|</span> </span>
                                <input class="form-control" placeholder="First Name" name="first_name" id="first_name" type="text" autofocus aria-describedby="basic-addon1" tabindex="1" required> </div>
                            <div id="spacer"></div>
                            <div class="input-group"> <span class="input-group-addon" id="basic-addon1">
                                    <span class="fa fa-m fa-user"></span> <span class="line">|</span> </span>
                                <input class="form-control" placeholder="Last Name" name="last_name" id="last_name" type="text" autofocus aria-describedby="basic-addon1" tabindex="2" required> </div>
                            <div id="spacer"></div>
                            <div class="input-group"> <span class="input-group-addon" id="basic-addon2">
                                    <span class="fa fa-m fa-phone"></span> <span class="line">|</span> </span>
                                <input class="form-control" placeholder="eg. +254712345678" name="phone_no" id="phone_no" type="tel" aria-describedby="basic-addon2" tabindex="4" required> </div>
                            <div id="spacer"></div>
                            <div class="input-group"> <span class="input-group-addon" id="basic-addon2">
                                    <span class="fa fa-m fa-envelope-o"></span> <span class="line">|</span> </span>
                                <input class="form-control" placeholder="Email" name="email" id="email" type="email" aria-describedby="basic-addon2" tabindex="5" required> </div>
                            <div id="spacer"></div>
                            <div class="input-group"> <span class="input-group-addon" id="basic-addon2">
                                    <span class="fa fa-m fa-key"></span> <span class="line">|</span> </span>
                                <input class="form-control" placeholder="Password" name="password" id="password" type="password" aria-describedby="basic-addon2" tabindex="5" required> </div>
                            <!--<div id="spacer"></div>

                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon2">
                                    <span class="fa fa-m fa-key"></span>
                                    <span class="line">|</span>
                                </span>
                                <input class="form-control" placeholder="Retype Password" name="password2" id="password2" type="password" aria-describedby="basic-addon2" tabindex="5" required>
                            </div>--></div>
                        <!-- Business -->
                        <div id="business">
                            <div id="spacer"></div>
                            <div class="input-group"> <span class="input-group-addon" id="basic-addon2">
                                    <span class="fa fa-m fa-building"></span> <span class="line">|</span> </span>
                                <input class="form-control" placeholder="Company Name" name="company_name" id="company_name" type="text" aria-describedby="basic-addon2" tabindex="3" required> </div>
                            <div id="spacer"></div>
                            <div class="input-group"> <span class="input-group-addon" id="basic-addon2">
                                    <span class="fa fa-m fa-phone"></span> <span class="line">|</span> </span>
                                <input class="form-control" placeholder="eg. +254712345678" name="company_phone_no" id="company_phone_no" type="tel" aria-describedby="basic-addon2" tabindex="4" required> </div>
                            <div id="spacer"></div>
                            <div class="input-group"> <span class="input-group-addon" id="basic-addon2">
                                    <span class="fa fa-m fa-envelope-o"></span> <span class="line">|</span> </span>
                                <input class="form-control" placeholder="Email" name="company_email" id="company_email" type="email" aria-describedby="basic-addon2" tabindex="5" required> </div>
                            <div id="spacer"></div>
                            <div class="input-group"> <span class="input-group-addon" id="basic-addon2">
                                    <span class="fa fa-m fa-key"></span> <span class="line">|</span> </span>
                                <input class="form-control" placeholder="Password" name="password" id="company_password" type="password" aria-describedby="basic-addon2" tabindex="5" required> </div>
                            <!--<div id="spacer"></div>

                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon2">
                                    <span class="fa fa-m fa-key"></span>
                                    <span class="line">|</span>
                                </span>
                                <input class="form-control" placeholder="Retype Password" name="password2" id="password2" type="password" aria-describedby="basic-addon2" tabindex="5" required>
                            </div>--></div>
                        <script>
                            var selected = $('#select_type').val();
                            if (selected == 1) {
                                $('#business').hide();
                            }
                        </script>
                        <div class="input-group">
                            <button name="submit" id="btn-signup" class="btn btn-primary btn-block signup-button"><span>Create Account</span></button>
                        </div> <span id="loginHere">You have an account? <a href="<?php echo site_url('login');?>">Login Here</a></span> </fieldset>
                </form>
            </center>
        </div>
    </div>
    <!-- Core Scripts - Include with every page -->
    <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
    <script>
        $(function () {
            $('#select_type').on('change', function () {
                selected = $('#select_type').val();
                console.log(selected);
                if (selected == 1) {
                    personalAccount();
                }
                else {
                    businessAccount();
                }
            });

            function personalAccount() {
                $('#personal').show();
                $('#business').hide();
            }

            function businessAccount() {
                $('#personal').hide();
                $('#business').show();
            }
            $('#btn-signup').on('click', function () {
                if (selected == 1) {
                    var first_name = $('#first_name').val().trim();
                    var last_name = $('#last_name').val().trim();
                    var email = $('#email').val().trim();
                    var phone_no = $('#phone_no').val().trim();
                    var select_type = $('#select_type').val().trim();
                    var password = $('#password').val().trim();
                    var company_name = null;
                    var company_email = null;
                    var company_phone_no = null;
                    var company_password = null;
                    if (first_name.length == 0 || last_name.length == 0 || email.length == 0 || phone_no.length == 0) {
                        swal({
                            title: "Required"
                            , text: "Fill in the required fields"
                            , type: "info"
                            , confirmButtonText: "ok"
                        });
                        return false;
                    }
                    else {
                        submitForm(first_name, last_name, email, phone_no, select_type, password, company_name, company_email, company_phone_no, company_password);
                    }
                }
                else {
                    var first_name = null;
                    var last_name = null;
                    var email = null;
                    var phone_no = null;
                    var password = null;
                    var company_name = $('#company_name').val().trim();
                    var company_email = $('#company_email').val().trim();
                    var company_phone_no = $('#company_phone_no').val().trim();
                    var select_type = $('#select_type').val().trim();
                    var company_password = $('#company_password').val().trim();
                    if (company_name.length == 0 || company_email.length == 0 || company_phone_no.length == 0 || company_password.length == 0) {
                        swal({
                            title: "Required"
                            , text: "Fill in the required fields"
                            , type: "info"
                            , confirmButtonText: "ok"
                        });
                        return false;
                    }
                    else {
                        submitForm(first_name, last_name, email, phone_no, select_type, password, company_name, company_email, company_phone_no, company_password);
                    }
                }
            });

            function submitForm(first_name, last_name, email, phone_no, select_type, password, company_name, company_email, company_phone_no, company_password) {
                $('.signup-button').html('<i class="fa fa-spinner fa-spin"></i>');
                $('.signup-button').prop('disabled', true);
                $.ajax({
                    method: 'post'
                    , url: "<?= base_url('index.php/login/create_account') ?>"
                    , data: {
                        select_type: select_type
                        , first_name: first_name
                        , last_name: last_name
                        , email: email
                        , phone_no: phone_no
                        , password: password
                        , company_name: company_name
                        , company_email: company_email
                        , company_phone_no: company_phone_no
                        , company_password: company_password
                    }
                    , success: function (response) {
                        //  response = JSON.parse(response);
                        //console.log(response);
                        if (response == 1 ) {
                            window.location.replace("<?= base_url('index.php/login') ?>");
                        }
                        else  {
                            swal({
                                title: "Info"
                                , text: response
                                , type: "info"
                                , confirmButtonText: "ok"
                            });
                            $('#btn-signup').html('Login');
                            $('.signup-button').prop('disabled', false);
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>