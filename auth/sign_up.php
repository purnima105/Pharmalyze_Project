<?php
require_once "../config/conn.php";

$name = $email = $password = $cpassword = $role = "";
$nameErr = $emailErr = $roleErr = $passwordErr = $cpasswordErr = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // NAME
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = trim($_POST["name"]);
  }

  // EMAIL
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $emailErr = "Invalid email format";
  } else {
    $email = strtolower(trim($_POST["email"]));
  }

  // ROLE
  if (empty($_POST["role"])) {
    $roleErr = "Please select a role";
  } else {
    $role = trim($_POST["role"]);
  }

  // PASSWORD
  if (empty($_POST["password"])) {
    $passwordErr = "Password is required";
  } elseif (strlen($_POST["password"]) < 6) {
    $passwordErr = "Password must be at least 6 characters";
  }

  // CONFIRM PASSWORD
  if (empty($_POST["cpassword"])) {
    $cpasswordErr = "Confirm your password";
  } elseif ($_POST["password"] !== $_POST["cpassword"]) {
    $cpasswordErr = "Passwords do not match";
  }

  // ✔ INSERT IF NO ERRORS
  if (empty($nameErr) && empty($emailErr) && empty($roleErr) && empty($passwordErr) && empty($cpasswordErr)) {

    // check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $emailErr = "Email already registered";
    } else {

      $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

      $stmt = $conn->prepare(
        "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)"
      );
      $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

      if ($stmt->execute()) {
        $successMsg = "Registration successful. You can now login.";
        header("refresh:3;url=sign_in.php");
      } else {
        $emailErr = "Something went wrong. Try again.";
      }

      $stmt->close();
    }
    $check->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign up | Pharmalyze</title>
  <link rel="stylesheet" href="../css/auth.css">
  <style>
    form {
      padding: 30px 40px;
      margin-top: 2rem;
    }

    h1 {
      font-size: 30px;
      margin-bottom: 22px;
    }

    label {
      margin-top: 8px;
    }

    #button {
      margin-top: 16px;
    }
  </style>
</head>

<body>
  <form action="" method="post">
    <h1>Sign Up</h1>

    <?php if (!empty($successMsg)): ?>
      <p style="
      color: green;
      text-align: center;
      margin-bottom: 15px;
      font-weight: bold;
  ">
        <?php echo htmlspecialchars($successMsg); ?>
      </p>
    <?php endif; ?>


    <label for="name">Name <span>*</span> </label>
    <input type="text" id="name" name="name" />
    <small class="error"><?php echo $nameErr; ?></small>

    <label for="email">Email<span>*</span></label>
    <input type="text" id="email" name="email" />
    <small class="error"><?php echo $emailErr; ?></small>

    <label for="role">Role<span>*</span></label>
    <select name="role" id="role">
      <option value="">-- Select a Role --</option>
      <option value="pharmacist">Pharmacist</option>
      <option value="supplier">Supplier</option>
    </select>
    <small class="error"><?php echo $roleErr; ?></small>


    <label for="password">Password<span>*</span></label>
    <input type="password" id="password" name="password" />
    <small class="error"><?php echo $passwordErr; ?></small>

    <label for="cpassword">Confirm Password<span>*</span></label>
    <input type="password" id="cpassword" name="cpassword" />
    <small class="error"><?php echo $cpasswordErr; ?></small>

    <input id="button" type="submit" value="Sign Up">

    <div class="link-text">
      Already have an account? <a href="sign_in">Sign in</a>
    </div>
  </form>
</body>

</html>