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
    <h1 class="mb-3">Schedules</h1>
    <?php if (($error ?? "") !== ""): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
    <?php endif; ?>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>COURSE CODE</th>
            <th>COURSE</th>
            <th>DAY</th>
            <th>TIME</th>
            <th>ROOM</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($schedules ?? [] as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row["course_code"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["course_name"], ENT_QUOTES, "UTF-8") ?></td>
              <td><?= htmlspecialchars($row["day_of_week"], ENT_QUOTES, "UTF-8") ?></td>
              <td>
                <?= htmlspecialchars($row["start_time"], ENT_QUOTES, "UTF-8") ?>
                -
                <?= htmlspecialchars($row["end_time"], ENT_QUOTES, "UTF-8") ?>
              </td>
              <td><?= htmlspecialchars($row["room"], ENT_QUOTES, "UTF-8") ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($schedules)): ?>
            <tr><td colspan="5" class="text-center">No schedules found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
  <script src="../view/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
