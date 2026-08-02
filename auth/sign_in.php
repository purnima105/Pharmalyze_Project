<?php
session_start();
require_once __DIR__ . "/../config/conn.php";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  if (empty($email) || empty($password)) {
    $error = "All fields are required";
  } else {

    // include role in query
    $stmt = $conn->prepare("
SELECT
    u.id,
    u.email,
    u.password,
    u.status,
    r.role_name
FROM users u
JOIN roles r
    ON u.role_id = r.id
WHERE u.email = ?
");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {

      if (password_verify($password, $user['password'])) {

        // store session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $user['role_name'];
        

        // role-based redirect
        if ($user['role_name'] === 'Admin') {
          header("Location: ../layouts/admin");

        }
        
        elseif ($user['role_name'] === 'Pharmacist') {
          // Set a success message
          $_SESSION['success'] = "Login successfully!";
          $user_id = $user['id'];

          $check = $conn->prepare("
          SELECT id as pharmacy_id
          FROM pharmacies
          WHERE user_id=?
          ");
          
          $check->bind_param("i", $user_id);
          $check->execute();

          $result = $check->get_result();

          if ($result->num_rows == 0) {
            header("Location: ../layouts/pharmacist/add_pharmacy");
          } else {
            header("Location: ../layouts/pharmacist/dashboard");
          }
 }
         elseif ($user['role_name'] === 'Supplier') {
          // Set a success message
          $_SESSION['success'] = "Login successfully!";
          header("Location: ../layouts/supplier");
        } else {
          $error = "Unauthorized role";
        }
        exit();

      } else {
        $error = "Invalid email or password";
      }

    } else {
      $error = "Invalid email or password";
    }

    $stmt->close();
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In</title>
  <link rel="stylesheet" href="../auth.css">
</head>

<body>
  <form method="post" action="">
    <h1>Sign In</h1>
    <label for="email">Email <span>*</span></label>
    <input type="email" name="email" id="email" />

    <label for="password">Password <span>*</span></label>
    <input type="password" name="password" id="password" />
    <small><?php echo $error; ?></small>

    <div class="forgot-link">
      <a href="forgot_password.php">Forgot Password?</a>
    </div>

    <input id="button" type="submit" value="Sign In" />

    <div class="link-text">
      <p>Don't have an account? </p>
      <a href="sign_up"> Create one</a>
    </div>
  </form>
</body>

</html>