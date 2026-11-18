<?php
if (!defined("MVC_REQUEST")) {
    http_response_code(404);
    exit;
}

$formTitle = isset($formTitle) && is_string($formTitle) ? $formTitle : "Lecturer";
$formAction = isset($formAction) && is_string($formAction) ? $formAction : "create.php?entity=lecturers";
$error = isset($error) && is_string($error) ? $error : "";
$defaults = ["name" => "", "nidn" => "", "phone" => "", "join_date" => ""];
$values = array_merge($defaults, isset($values) && is_array($values) ? $values : []);
foreach ($defaults as $field => $default) {
    $values[$field] = is_scalar($values[$field]) ? (string) $values[$field] : $default;
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../view/assets/css/bootstrap.min.css" rel="stylesheet">
  <title><?= htmlspecialchars($formTitle, ENT_QUOTES, "UTF-8") ?></title>
</head>

<body>
  <?php require __DIR__ . "/navbar.php"; ?>
  <main class="container my-4">
    <div class="col-lg-6 mx-auto">
      <h1 class="mb-3"><?= htmlspecialchars($formTitle, ENT_QUOTES, "UTF-8") ?></h1>
      <?php if ($error !== ""): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
      <?php endif; ?>
      <form method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, "UTF-8") ?>">
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input id="name" class="form-control" type="text" name="name"
            value="<?= htmlspecialchars($values["name"], ENT_QUOTES, "UTF-8") ?>" required>
        </div>
        <div class="mb-3">
          <label for="nidn" class="form-label">NIDN</label>
          <input id="nidn" class="form-control" type="text" name="nidn"
            value="<?= htmlspecialchars($values["nidn"], ENT_QUOTES, "UTF-8") ?>" required>
        </div>
        <div class="mb-3">
          <label for="phone" class="form-label">Phone</label>
          <input id="phone" class="form-control" type="text" name="phone"
            value="<?= htmlspecialchars($values["phone"], ENT_QUOTES, "UTF-8") ?>" required>
        </div>
        <div class="mb-3">
          <label for="join_date" class="form-label">Join date</label>
          <input id="join_date" class="form-control" type="date" name="join_date"
            value="<?= htmlspecialchars($values["join_date"], ENT_QUOTES, "UTF-8") ?>" required>
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
        <a class="btn btn-secondary" href="read.php?entity=lecturers">Cancel</a>
      </form>
    </div>
  </main>
  <script src="../view/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
