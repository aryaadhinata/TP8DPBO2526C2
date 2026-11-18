<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../view/assets/css/bootstrap.min.css" rel="stylesheet">
  <title>Home</title>
</head>

<body>
  <main class="container py-5">
    <div class="text-center mb-5">
      <h1>Academic Management</h1>
      <p class="text-muted">Choose a section to continue.</p>
    </div>
    <div class="row justify-content-center g-4">
      <div class="col-md-4">
        <a class="btn btn-primary btn-lg w-100 py-4" href="../controller/read.php?entity=lecturers">Lecturers</a>
      </div>
      <div class="col-md-4">
        <a class="btn btn-success btn-lg w-100 py-4" href="../controller/read.php?entity=courses">Courses</a>
      </div>
      <div class="col-md-4">
        <a class="btn btn-info btn-lg w-100 py-4" href="../controller/read.php?entity=schedules">Schedules</a>
      </div>
    </div>
  </main>
  <script src="../view/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
