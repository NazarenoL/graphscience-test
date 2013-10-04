<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title;?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo base_url();?>assets/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo base_url();?>assets/css/font-awesome.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo base_url();?>assets/css/social-buttons.css" rel="stylesheet" media="screen">
        <link href="<?php echo base_url();?>assets/css/main.css" rel="stylesheet" media="screen">
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

        <?php
        //If the page asks for it, include the necessary files for a custom MultiSelect
        if(isset($multiselect)){
            printf('        <script type="text/javascript" src="%sassets/js/bootstrap-multiselect.js"></script>
            <link rel="stylesheet" href="%sassets/css/bootstrap-multiselect.css" type="text/css"/>',base_url(),base_url());
        }
        if(isset($schedule)){
            printf('        <script type="text/javascript" src="%sassets/js/bootstrap-datetimepicker.min.js"></script>
            <link rel="stylesheet" href="%sassets/css/datetimepicker.css" type="text/css"/>',base_url(),base_url());
        }
        if(isset($datatable)){
            printf('        <script type="text/javascript" src="%sassets/js/jquery.dataTables.min.js"></script>
            <link href="%sassets/css/jquery.dataTables.css" rel="stylesheet" media="screen" />', base_url(), base_url(), base_url());
        }

        ?>

    </head>
    <body>
