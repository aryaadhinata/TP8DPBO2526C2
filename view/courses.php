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
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Courses</h1>
      <a class="btn btn-primary" href="courses_create.php">Add Course</a>
    </div>
    <?php if (($error ?? "") !== ""): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
    <?php endif; ?>
    <?php if (($notice ?? "") !== ""): ?>
      <div class="alert alert-success"><?= htmlspecialchars($notice, ENT_QUOTES, "UTF-8") ?></div>
    <?php endif; ?>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>CODE</th>
            <th>COURSE</th>
            <th>CREDITS</th>
            <th>LECTURER</th>
            <th>ACTIONS</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($courses ?? [] as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row["code"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["name"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars((string) $row["credits"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["lecturer_name"] ?? "Unassigned", ENT_QUOTES, "UTF-8") ?></td>
              <td class="text-nowrap">
                <a class="btn btn-success" href="courses_edit.php?id=<?= urlencode((string) $row["id"]) ?>">Edit</a>
                <form class="d-inline" method="post" action="courses_delete.php"
                  onsubmit="return confirm('Deleting this course will also delete its schedules. Continue?')">
                  <input type="hidden" name="id" value="<?= htmlspecialchars((string) $row["id"], ENT_QUOTES, "UTF-8") ?>">
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($courses)): ?>
            <tr><td colspan="5" class="text-center">No courses found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
  <script src="../view/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
