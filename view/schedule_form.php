<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../view/assets/css/bootstrap.min.css" rel="stylesheet">
  <?php $formTitle = $formTitle ?? "Schedule"; ?>
  <?php $formAction = $formAction ?? "read.php?entity=schedules"; ?>
  <title><?= htmlspecialchars($formTitle, ENT_QUOTES, "UTF-8") ?></title>
</head>

<body>
  <?php require __DIR__ . "/navbar.php"; ?>
  <?php $values = $values ?? []; ?>
  <main class="container my-4">
    <div class="col-lg-6 mx-auto">
      <h1 class="mb-3"><?= htmlspecialchars($formTitle, ENT_QUOTES, "UTF-8") ?></h1>
      <?php if (($error ?? "") !== ""): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error ?? "", ENT_QUOTES, "UTF-8") ?></div>
      <?php endif; ?>
      <form method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, "UTF-8") ?>">
        <div class="mb-3">
          <label for="course_id" class="form-label">Course</label>
          <select id="course_id" class="form-select" name="course_id" required>
            <option value="">Select a course</option>
            <?php foreach ($courses ?? [] as $course): ?>
              <option value="<?= htmlspecialchars((string) $course["id"], ENT_QUOTES, "UTF-8") ?>"
                <?= (string) ($values["course_id"] ?? "") === (string) $course["id"] ? "selected" : "" ?>>
                <?= htmlspecialchars($course["code"] . " - " . $course["name"], ENT_QUOTES, "UTF-8") ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="day_of_week" class="form-label">Day</label>
          <select id="day_of_week" class="form-select" name="day_of_week" required>
            <option value="">Select a day</option>
            <?php foreach ($days ?? ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"] as $day): ?>
              <option value="<?= htmlspecialchars($day, ENT_QUOTES, "UTF-8") ?>"
                <?= ($values["day_of_week"] ?? "") === $day ? "selected" : "" ?>>
                <?= htmlspecialchars($day, ENT_QUOTES, "UTF-8") ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="start_time" class="form-label">Start time</label>
          <input id="start_time" class="form-control" type="time" name="start_time" step="60"
            value="<?= htmlspecialchars($values["start_time"] ?? "", ENT_QUOTES, "UTF-8") ?>" required>
        </div>
        <div class="mb-3">
          <label for="end_time" class="form-label">End time</label>
          <input id="end_time" class="form-control" type="time" name="end_time" step="60"
            value="<?= htmlspecialchars($values["end_time"] ?? "", ENT_QUOTES, "UTF-8") ?>" required>
        </div>
        <div class="mb-3">
          <label for="room" class="form-label">Room</label>
          <input id="room" class="form-control" type="text" name="room" maxlength="30"
            value="<?= htmlspecialchars($values["room"] ?? "", ENT_QUOTES, "UTF-8") ?>" required>
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
        <a class="btn btn-secondary" href="read.php?entity=schedules">Cancel</a>
      </form>
    </div>
  </main>
  <script src="../view/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
