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
      <div class="alert alert-danger"><?= htmlspecialchars($error ?? "", ENT_QUOTES, "UTF-8") ?></div>
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
            <th>COURSES</th>
            <th>ACTIONS</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($lecturers ?? [] as $row): ?>
            <?php
            $courseCount = (int) ($row["course_count"] ?? 0);
            $deleteMessage = $courseCount > 0
                ? "This lecturer teaches {$courseCount} course(s). The courses will be kept but become unassigned. Continue?"
                : "Delete this lecturer?";
            ?>
            <tr>
              <th><?= htmlspecialchars((string) $row["id"], ENT_QUOTES, "UTF-8") ?></th>
              <td><?= htmlspecialchars($row["name"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["nidn"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["phone"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["join_date"], ENT_QUOTES, "UTF-8") ?></td>
              <td>
                <?php if ($courseCount > 0): ?>
                  <?= $courseCount ?>
                  <small class="text-muted">(<?= htmlspecialchars((string) ($row["course_codes"] ?? ""), ENT_QUOTES, "UTF-8") ?>)</small>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td>
                <a class="btn btn-success" href="update.php?entity=lecturers&amp;id=<?= urlencode((string) $row["id"]) ?>">Edit</a>
                <form class="d-inline" method="post" action="delete.php?entity=lecturers"
                  data-message="<?= htmlspecialchars($deleteMessage, ENT_QUOTES, "UTF-8") ?>"
                  onsubmit="return confirm(this.dataset.message)">
                  <input type="hidden" name="id" value="<?= htmlspecialchars((string) $row["id"], ENT_QUOTES, "UTF-8") ?>">
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($lecturers)): ?>
            <tr><td colspan="7" class="text-center">No lecturers found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
  <script src="../view/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
