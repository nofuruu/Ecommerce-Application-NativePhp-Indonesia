<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <style>
    #dslogo {
      margin-left: 100px;
    }
    body {
      background: rgb(30,63,87);
      background: linear-gradient(90deg, rgba(30,63,87,1) 10%, rgba(8,33,51,1) 47%, rgba(46,45,45,1) 93%);
    }
    .container {
      margin-top: 150px;
    }
    .login-card {
      max-width: 500px;
      margin: 50px auto;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      background-color: white;
    }
    .btn-submit {
      width: 100%;
      margin-top: 10px;
      margin-bottom: 10px;
      background-color: orange;
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      border: none;
      font-weight: bold;
      text-transform: uppercase;
      position: relative;
      overflow: hidden;
      transition: background-color 0.3s, box-shadow 0.3s;
    }
    .btn-submit:hover {
      background-color: #ff6f61;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    input {
      margin: 10px;
      width: 100%;
    }
    form {
      margin-top: 0;
    }
    #logo {
      margin-left: 100px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="login-card">
      <div class="navbar-brand">
        <img src="../../public/resource/logoB.png" alt="logo" id="logo">
      </div>

      <form id="loginForm" method="POST" action="../function/proses-login.php">
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username anda" required>
          <label for="username">Username</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password anda" required>
          <label for="password">Password</label>
        </div>
        <button type="submit" class="btn btn-submit" id="btn-submit">Login</button>        <p class="text-center mt-3">Belum memiliki akun? <a href="../forms/register.php" class="link">Register</a></p>
        <p class="text-center mt-3"><a href="../../home.php" class="link">Lanjutkan tanpa akun</a></p>
      </form> 
    </div>
  </div>

  <!-- Modal for Error Notification -->
  <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="errorModalLabel">Login Error</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Username or password is incorrect. Please try again.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

  <script>
    // Check if there is an error in the URL to trigger the modal
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
      const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
      errorModal.show();
    }
  </script>

</body>
</html>
