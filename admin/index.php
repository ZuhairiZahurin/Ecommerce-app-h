<?php 
session_start(); // start the PHP session

// if the admin_id session variable is not set, redirect to the login page
if (!isset($_SESSION['admin_id'])) {
  header("location:login.php");
}

include "./templates/top.php"; // include the top template

?>

<?php include "./templates/navbar.php"; ?> <!----- include the navbar template---->

<div class="container-fluid">
  <div class="row">
    
    <?php include "./templates/sidebar.php"; ?> <!--- include the sidebar template----->

      <!-- <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas> -->

      <h2><center>Admin Details</center></h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="admin_list">
            <tr>
              <td>1,001</td>
              <td>Lorem</td>
              <td>ipsum</td>
              <td>dolor</td>
              <td>sit</td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<?php include "./templates/footer.php"; ?> <!----include the footer template--->

<script type="text/javascript" src="./js/admin.js"></script> <!---include the admin JavaScript file--->
