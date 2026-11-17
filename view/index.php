<?php
if (!defined("MVC_REQUEST")) {
    http_response_code(404);
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../bootstrap.min.css" rel="stylesheet">
  <title>Home</title>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Lecturers</a>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">?</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">?</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container my-4">
    <div class="col-1 my-3">
      <a type="button" class="btn btn-primary nav-link active" href="create.php">Add New</a>
    </div>
    <?php if ($error !== ""): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
    <?php endif; ?>
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
        <?php foreach ($lecturers as $row): ?>
          <tr>
            <th><?= htmlspecialchars((string) $row["id"], ENT_QUOTES, "UTF-8") ?></th>
            <td><?= htmlspecialchars($row["name"], ENT_QUOTES, "UTF-8") ?></td>
            <td><?= htmlspecialchars($row["nidn"], ENT_QUOTES, "UTF-8") ?></td>
            <td><?= htmlspecialchars($row["phone"], ENT_QUOTES, "UTF-8") ?></td>
            <td><?= htmlspecialchars($row["join_date"], ENT_QUOTES, "UTF-8") ?></td>
            <td>
              <a class="btn btn-success" href="edit.php?id=<?= urlencode((string) $row["id"]) ?>">Edit</a>
              <a class="btn btn-danger" href="delete.php?id=<?= urlencode((string) $row["id"]) ?>">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <script src="../bootstrap.bundle.min.js"></script>
</body>

</html>
