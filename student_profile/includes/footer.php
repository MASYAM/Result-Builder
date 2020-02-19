
  </div> <!-- end of div id="main" -->           

  <div id="footer"><span style="margin-right: 20px;">Copyright &COPY; <?php echo date("Y", time()); ?>, All Rights Reserved</span></div>
  </body>
</html>
<?php if(isset($db)) { $db->close_connection(); } ?>