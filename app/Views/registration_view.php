<!DOCTYPE html>
<html>

<head>
  <title>Codeigniter 4 CRUD - Edit User Demo</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    .container {
      max-width: 500px;
    }

    .error {
      display: block;
      padding-top: 5px;
      font-size: 14px;
      color: red;
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <form method="post" id="update_user" name="update_user" 
    action="<?= site_url('/submit-registration') ?>">

      <h1>Sign In</h1>
      <?php if(session()->getFlashdata('msg')):?>
          <div class="alert alert-danger"><?= session()->getFlashdata('msg') ?></div>
      <?php endif;?>
      <input type="hidden" name="id" id="id" value="">
      <div class="form-group">
        <label>Name</label>
        <input type="name" name="name" class="form-control" value="">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="">
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" value="">
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-danger btn-block">Login</button>
      </div>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
  <script>
    if ($("#update_user").length > 0) {
      $("#update_user").validate({
        rules: {
          name: {
            required: true
          },
          email: {
            required: true,
            maxlength: 60,
            email: true,
          },
          password: {
            required: true
          },
        },
        messages: {
          name: {
            required: "Name is required.",
          },
          email: {
            required: "Email is required.",
          },
          password: {
            required: "Password is required."
          },
        },
      })
    }
  </script>
</body>

</html>