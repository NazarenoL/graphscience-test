    <?php
    //If the page asks for it, include the necessary files for a custom MultiSelect
    if(isset($multiselect)){
        echo '<!-- Initialize the plugin: -->
            <script type="text/javascript">
              $(document).ready(function() {
                $(".multiselect").multiselect({
                    enableFiltering: 1,
                    enableCaseInsensitiveFiltering: true
                    });
              });
            </script>';
    }
    if(isset($schedule)){
        echo '<script type="text/javascript">
                $(".form_datetime").datetimepicker({
                    format: \'yyyy-mm-dd hh:ii\',
                    autoclose: true,
                    todayBtn: false,
                    startDate: "' .$startDate .'",
                    endDate: "' . $endDate .'",

                });
            </script>';
    }
    
    ?>

    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
  </body>
</html>