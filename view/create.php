<!DOCTYPE html>
<html>

<head>
  <title>Create</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../view/assets/css/bootstrap.min.css">
  <script src="../view/assets/js/jquery.min.js"></script>
  <script src="../view/assets/js/popper.min.js"></script>
  <script src="../view/assets/js/bootstrap.min.js"></script>
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
    <?php
      if (!isset($error)) {
        $error = "";
      }

      if (!isset($values)) {
        $values = [
          "name" => "",
          "nidn" => "",
          "phone" => "",
          "join_date" => "",
        ];
      }
    ?>
    <form method="post">
      <br><br>
      <div class="card">
        <div class="card-header bg-primary">
          <h1 class="text-white text-center">Create Lecturer</h1>
        </div><br>
        <?php if ($error !== ""): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
        <?php endif; ?>
        <label>NAME:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($values["name"], ENT_QUOTES, "UTF-8") ?>" class="form-control" required><br>
        <label>NIDN:</label>
        <input type="text" name="nidn" value="<?= htmlspecialchars($values["nidn"], ENT_QUOTES, "UTF-8") ?>" class="form-control" required><br>
        <label>PHONE:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($values["phone"], ENT_QUOTES, "UTF-8") ?>" class="form-control" required><br>
        <label>JOIN DATE:</label>
        <input type="date" name="join_date" value="<?= htmlspecialchars($values["join_date"], ENT_QUOTES, "UTF-8") ?>" class="form-control" required><br>
        <button class="btn btn-success" type="submit" name="submit">Submit</button><br>
        <a class="btn btn-info" href="index.php">Cancel</a><br>
      </div>
    </form>
  </div>
</body>

</html>
