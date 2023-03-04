<!DOCTYPE html>
<html>
<head>
  <title>Admin Login Page</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="icon" href="./favicon.png">
  <style>
    body {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f5f5f5;
}

form {
  max-width: 400px;
  padding: 20px;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 5px;
}

form h2 {
  text-align: center;
}

form table {
  width: 100%;
}

form table td {
  padding: 10px;
}

form table input[type="text"],
form table input[type="password"] {
  width: 100%;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ddd;
}

form table input[type="submit"] {
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
}

form table input[type="submit"]:hover {
  background-color: #0069d9;
}

    </style>
</head>
<body>
<?php

  // Include the config.php file to connect to the database
  include_once 'config.php';

  if (isset($_POST['username']) && isset($_POST['password'])) {
  // connect to the database
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $username = $_POST['username'];
  $password = $_POST['password'];

  // Prepare the SELECT statement to fetch the password hash from the database
  $sql = "SELECT password FROM admin WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  // Check if the username exists in the database
  if ($result->num_rows == 1) {
    // Verify the password using password_verify function
    if (password_verify($password, $row['password'])) {
      // Start a session and redirect to the Sites page
      session_start();
      $_SESSION['username'] = $username;
      header("Location: submissions.php");
    } else {
      echo "Invalid username or password";
    }
  } else {
    echo "Invalid username or password";
  }
}
?>
  <form action="admincp.php" method="post" class="form-group">
  <h2>Admin Login</h2>
    <table>
      <tr>
        <td>Username:</td>
        <td><input type="text" name="username" required placeholder="Username"></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type="password" name="password" required placeholder="Password"></td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" name="submit" value="Login"></td>
      </tr>
    </table>
  </form>
</body>
</html>
