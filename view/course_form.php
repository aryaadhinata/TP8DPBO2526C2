<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../view/assets/css/bootstrap.min.css" rel="stylesheet">
  <title><?= htmlspecialchars($formTitle ?? "Course form", ENT_QUOTES, "UTF-8") ?></title>
</head>

<body>
  <?php
    $values = array_merge([
      "code" => "",
      "name" => "",
      "credits" => "",
      "lecturer_id" => "",
    ], $values ?? []);
    $lecturers = $lecturers ?? [];
    $error = $error ?? "";
    $formTitle = $formTitle ?? "Course form";
    $formAction = $formAction ?? "read.php?entity=courses";
  ?>
  <?php require __DIR__ . "/navbar.php"; ?>
  <main class="container my-4">
    <div class="col-lg-6 mx-auto">
      <h1 class="mb-3"><?= htmlspecialchars($formTitle ?? "Course form", ENT_QUOTES, "UTF-8") ?></h1>
      <?php if (($error ?? "") !== ""): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
      <?php endif; ?>
      <form method="post" action="<?= htmlspecialchars($formAction ?? "read.php?entity=courses", ENT_QUOTES, "UTF-8") ?>">
        <div class="mb-3">
          <label for="code" class="form-label">Course code</label>
          <input id="code" class="form-control" type="text" name="code" maxlength="10"
            value="<?= htmlspecialchars($values["code"], ENT_QUOTES, "UTF-8") ?>" required>
        </div>
        <div class="mb-3">
          <label for="name" class="form-label">Course name</label>
          <input id="name" class="form-control" type="text" name="name" maxlength="100"
            value="<?= htmlspecialchars($values["name"], ENT_QUOTES, "UTF-8") ?>" required>
        </div>
        <div class="mb-3">
          <label for="credits" class="form-label">Credits</label>
          <input id="credits" class="form-control" type="number" name="credits" min="1" max="255"
            value="<?= htmlspecialchars((string) $values["credits"], ENT_QUOTES, "UTF-8") ?>" required>
        </div>
        <div class="mb-3">
          <label for="lecturer_id" class="form-label">Lecturer</label>
          <select id="lecturer_id" class="form-select" name="lecturer_id">
            <option value="">Unassigned</option>
            <?php foreach ($lecturers ?? [] as $lecturer): ?>
              <option value="<?= htmlspecialchars((string) $lecturer["id"], ENT_QUOTES, "UTF-8") ?>"
                <?= (string) ($values["lecturer_id"] ?? "") === (string) $lecturer["id"] ? "selected" : "" ?>>
                <?= htmlspecialchars($lecturer["name"], ENT_QUOTES, "UTF-8") ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
        <a class="btn btn-secondary" href="read.php?entity=courses">Cancel</a>
      </form>
    </div>
  </main>
  <script src="../view/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
