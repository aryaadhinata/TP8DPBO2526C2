<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../view/assets/css/bootstrap.min.css" rel="stylesheet">
  <title>Schedules</title>
</head>

<body>
  <?php require __DIR__ . "/navbar.php"; ?>
  <main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Schedules</h1>
      <a class="btn btn-primary" href="create.php?entity=schedules">Add Schedule</a>
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
            <th>COURSE CODE</th>
            <th>COURSE</th>
            <th>LECTURER</th>
            <th>DAY</th>
            <th>TIME</th>
            <th>ROOM</th>
            <th>ACTIONS</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($schedules ?? [] as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row["course_code"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["course_name"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["lecturer_name"] ?? "Unassigned", ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["day_of_week"], ENT_QUOTES, "UTF-8") ?></td>
              <td>
                <?= htmlspecialchars(substr($row["start_time"], 0, 5), ENT_QUOTES, "UTF-8") ?>
                -
                <?= htmlspecialchars(substr($row["end_time"], 0, 5), ENT_QUOTES, "UTF-8") ?>
              </td>
              <td><?= htmlspecialchars($row["room"], ENT_QUOTES, "UTF-8") ?></td>
              <td class="text-nowrap">
                <a class="btn btn-success" href="update.php?entity=schedules&amp;id=<?= urlencode((string) $row["id"]) ?>">Edit</a>
                <form class="d-inline" method="post" action="delete.php?entity=schedules">
                  <input type="hidden" name="id" value="<?= htmlspecialchars((string) $row["id"], ENT_QUOTES, "UTF-8") ?>">
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($schedules)): ?>
            <tr><td colspan="7" class="text-center">No schedules found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
  <script src="../view/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
