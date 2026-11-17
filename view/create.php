<?php
if (!isset($formTitle)) {
  $formTitle = "Create Lecturer";
}

if (!isset($formAction)) {
  $formAction = "";
}

if (!isset($error)) {
  $error = "";
}

if (!isset($values)) {
  $values = [
    "name" => "",
    "nidn" => "",
    "phone" => "",
    "join_date" => "",
  ];
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../view/assets/css/bootstrap.min.css">
  <script src="../view/assets/js/jquery.min.js"></script>
  <script src="../view/assets/js/popper.min.js"></script>
  <script src="../view/assets/js/bootstrap.min.js"></script>
  <title><?= htmlspecialchars($formTitle, ENT_QUOTES, "UTF-8") ?></title>
</head>

<body>
  <?php require __DIR__ . "/navbar.php"; ?>
  <div class="col-lg-6 m-auto">
    <form method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, "UTF-8") ?>">
      <br><br>
      <div class="card">
        <div class="card-header bg-primary">
          <h1 class="text-white text-center"><?= htmlspecialchars($formTitle, ENT_QUOTES, "UTF-8") ?></h1>
        </div><br>
        <?php if ($error !== ""): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
        <?php endif; ?>
        <label>NAME:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($values["name"], ENT_QUOTES, "UTF-8") ?>" class="form-control" required><br>
        <label>NIDN:</label>
        <input type="text" name="nidn" value="<?= htmlspecialchars($values["nidn"], ENT_QUOTES, "UTF-8") ?>" class="form-control" required><br>
        <label>PHONE:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($values["phone"], ENT_QUOTES, "UTF-8") ?>" class="form-control" required><br>
        <label>JOIN DATE:</label>
        <input type="date" name="join_date" value="<?= htmlspecialchars($values["join_date"], ENT_QUOTES, "UTF-8") ?>" class="form-control" required><br>
        <button class="btn btn-success" type="submit">Submit</button><br>
        <a class="btn btn-info" href="read.php?entity=lecturers">Cancel</a><br>
      </div>
    </form>
  </div>
</body>

</html>
