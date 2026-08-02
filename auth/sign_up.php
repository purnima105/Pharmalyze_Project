<?php
require_once __DIR__ . "/../config/conn.php";

$name = $email = $password = $cpassword = $role_id = "";
$nameErr = $emailErr = $roleErr = $passwordErr = $cpasswordErr = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = trim($_POST["name"]);
    }

    // Email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    } else {
        $email = strtolower(trim($_POST["email"]));
    }

    // Role
    if (empty($_POST["role_id"])) {
        $roleErr = "Please select a role";
    } else {
        $role_id = (int)$_POST["role_id"];
    }

    // Password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } elseif (strlen($_POST["password"]) < 6) {
        $passwordErr = "Password must be at least 6 characters";
    }

    // Confirm Password
    if (empty($_POST["cpassword"])) {
        $cpasswordErr = "Confirm your password";
    } elseif ($_POST["password"] !== $_POST["cpassword"]) {
        $cpasswordErr = "Passwords do not match";
    }

    if (
        empty($nameErr) &&
        empty($emailErr) &&
        empty($roleErr) &&
        empty($passwordErr) &&
        empty($cpasswordErr)
    ) {

        // Check email
        $check = $conn->prepare("SELECT id FROM users WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {

            $emailErr = "Email already registered";

        } else {

            $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                INSERT INTO users
                (name,email,password,role_id)
                VALUES (?,?,?,?)
            ");

            $stmt->bind_param(
                "sssi",
                $name,
                $email,
                $hashedPassword,
                $role_id
            );

            if ($stmt->execute()) {

                $successMsg = "Registration successful. Redirecting to login...";
                header("refresh:3;url=sign_in.php");

            } else {

                $emailErr = "Something went wrong.";
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
  <link rel="stylesheet" href="../auth.css">
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
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name); ?>"/>
    <small class="error"><?php echo $nameErr; ?></small>

    <label for="email">Email<span>*</span></label>
    <input type="text" id="email" name="email"  value="<?= htmlspecialchars($email); ?>" />
    <small class="error"><?php echo $emailErr; ?></small>

    <label for="role_id">Role<span>*</span></label>

<select name="role_id" id="role_id">

    <option value="">-- Select Role --</option>

    <?php
    $roles = $conn->query("
        SELECT id, role_name
        FROM roles
        WHERE status='active'
        AND role_name <> 'Admin'
        ORDER BY role_name
    ");

    while ($row = $roles->fetch_assoc()) :
    ?>

        <option
            value="<?= $row['id']; ?>"
            <?= ($role_id == $row['id']) ? "selected" : ""; ?>>
            <?= htmlspecialchars($row['role_name']); ?>
        </option>

    <?php endwhile; ?>

</select>

<small class="error"><?= $roleErr; ?></small>


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