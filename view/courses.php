<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../view/assets/css/bootstrap.min.css" rel="stylesheet">
  <title>Courses</title>
</head>

<body>
  <?php require __DIR__ . "/navbar.php"; ?>
  <main class="container my-4">
    <h1 class="mb-3">Courses</h1>
    <?php if (($error ?? "") !== ""): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
    <?php endif; ?>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>CODE</th>
            <th>COURSE</th>
            <th>CREDITS</th>
            <th>LECTURER</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($courses ?? [] as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row["code"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["name"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars((string) $row["credits"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["lecturer_name"] ?? "Unassigned", ENT_QUOTES, "UTF-8") ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($courses)): ?>
            <tr><td colspan="4" class="text-center">No courses found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
  <script src="../view/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
