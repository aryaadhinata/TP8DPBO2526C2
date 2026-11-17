<!DOCTYPE html>
<html>

<head>
  <title>Ubah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../bootstrap.min.css">
  <script src="../jquery.min.js"></script>
  <script src="../popper.min.js"></script>
  <script src="../bootstrap.min.js"></script>
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
  <div class="col-lg-6 m-auto">
    <form method="post">
      <br><br>
      <div class="card">
        <div class="card-header bg-warning">
          <h1 class="text-white text-center">Update Lecturer</h1>
        </div><br>
        <?php if ($error !== ""): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
        <?php endif; ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars((string) $lecturer["id"], ENT_QUOTES, "UTF-8") ?>" class="form-control"><br>
        <label>NAME:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($lecturer["name"], ENT_QUOTES, "UTF-8") ?>" class="form-control" required><br>
        <label>NIDN:</label>
        <input type="text" name="nidn" value="<?= htmlspecialchars($lecturer["nidn"], ENT_QUOTES, "UTF-8") ?>" class="form-control" required><br>
        <label>PHONE:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($lecturer["phone"], ENT_QUOTES, "UTF-8") ?>" class="form-control" required><br>
        <label>JOIN DATE:</label>
        <input type="date" name="join_date" value="<?= htmlspecialchars($lecturer["join_date"], ENT_QUOTES, "UTF-8") ?>" class="form-control" required><br>
        <button class="btn btn-success" type="submit" name="submit">Submit</button><br>
        <a class="btn btn-info" href="index.php">Cancel</a><br>
      </div>
    </form>
  </div>
</body>

</html>
