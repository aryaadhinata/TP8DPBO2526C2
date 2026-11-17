<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../view/assets/css/bootstrap.min.css" rel="stylesheet">
  <title>Lecturers</title>
</head>

<body>
  <?php require __DIR__ . "/navbar.php"; ?>
  <main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Lecturers</h1>
      <a class="btn btn-primary" href="create.php?entity=lecturers">Add Lecturer</a>
    </div>
    <?php if (($error ?? "") !== ""): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
    <?php endif; ?>
    <?php if (($notice ?? "") !== ""): ?>
      <div class="alert alert-success"><?= htmlspecialchars($notice ?? "", ENT_QUOTES, "UTF-8") ?></div>
    <?php endif; ?>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>NIDN</th>
            <th>PHONE</th>
            <th>JOIN DATE</th>
            <th>ACTIONS</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($lecturers ?? [] as $row): ?>
            <tr>
              <th><?= htmlspecialchars((string) $row["id"], ENT_QUOTES, "UTF-8") ?></th>
              <td><?= htmlspecialchars($row["name"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["nidn"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["phone"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["join_date"], ENT_QUOTES, "UTF-8") ?></td>
              <td>
                <a class="btn btn-success" href="update.php?entity=lecturers&amp;id=<?= urlencode((string) $row["id"]) ?>">Edit</a>
                <form class="d-inline" method="post" action="delete.php?entity=lecturers">
                  <input type="hidden" name="id" value="<?= htmlspecialchars((string) $row["id"], ENT_QUOTES, "UTF-8") ?>">
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($lecturers)): ?>
            <tr><td colspan="6" class="text-center">No lecturers found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
  <script src="../view/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
