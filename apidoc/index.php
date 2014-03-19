<?php
include_once('apidoc.php');
$API = new api;

?>
<!DOCTYPE html>
<html>
  <head>
    <title>API Documention</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
    
    <div class="container">
       <h2>API Documention</h2>
       <table class="table table-bordered table-striped">
        <thead>
            <tr><th>File</th><th>Class</th><th>Functions</th></tr>
        </thead>
        <tbody>
            <?php $API->printAlFiles(); ?>
            
        </tbody>
        
       </table>
       
    </div>
    <footer style="text-align: center; padding: 30px 0; margin-top: 70px; border-top: 1px solid #e5e5e5; background-color: #f5f5f5;">
      <div class="container">
        <p style="color: #777;">API Documention v1.0</p>
      </div>
    </footer>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript">
        $("a[href]").click(function() {
          $("html, body").animate({ scrollTop: $($(this).attr('href')).offset().top-20 }, "slow");
          return false;
        });
    </script>
  </body>
</html>