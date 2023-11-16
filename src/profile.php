<?php

session_start();

// If the logout button is clicked
if (isset($_POST['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other page after logout
    header("Location: index.php");
    exit();
}

?>

<head>

     <title>Login with </title>

     <link

        href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"

        rel = "stylesheet">
  <style>
    .hidden-text {
      display: none;
    }
  </style>
  </head>

  <body>     

  <?php if($_SESSION['id']) {?>

        <div class = "container">

           <div class = "jumbotron">

              <h1>Hello <?php echo $_SESSION['name']; ?></h1>

              <p>Welcome to awikwok</p>
                  <form method="post" action="">
                      <input type="submit" name="logout" value="Logout">
                  </form>

           </div>

              <ul class = "nav nav-list">

                 <h4> ID</h4>
                 <button onclick="revealText('hiddenText1')">Click to reveal text</button>
                 <li><p id="hiddenText1" class="hidden-text"><?php echo  $_SESSION['id']; ?></p></li>

                 <h4> fullname</h4>

                 <li><?php echo $_SESSION['name']; ?></li>

                 <h4> Email</h4>
                 <button onclick="revealText('hiddenText2')">Click to reveal text</button>
                 <li><p id="hiddenText2" class="hidden-text"><?php echo $_SESSION['email']; ?></p></li>

                 <h4>Your Access Code</h4>

                 <textarea style="width: 1000px; height: 100px;" readonly><?php echo $_SESSION['access_code']; ?></textarea>

                 <h4>Your Access Token</h4>

                 <textarea style="width: 1000px; height: 100px;" readonly><?php echo $_SESSION['access_token']; ?></textarea>


              </ul>

          </div>

<?php }
else{
  echo "minimal login dulu lah";
} ?>

  </body>
<script>
  function revealText(elementId) {
    var hiddenText = document.getElementById(elementId);
    hiddenText.style.display = "block";
  }
</script>
</html>