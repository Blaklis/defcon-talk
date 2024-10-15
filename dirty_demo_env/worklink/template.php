<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="style.css" rel="stylesheet">
</head>
<body class="text-center">

<main class="form-signin">
    <form method="POST">
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

        <?php
         global $error;
         if($error) {
            ?>
        <div class="alert alert-danger" role="alert">
            <?=$error?>
        </div>
        <?php
         }

         if($_SESSION['loggedin']) {
             ?>
             <div class="alert alert-primary" role="alert">
                 You're logged in as <?=$_SESSION['username']?> (<?=$_SESSION['role']?>)
             </div>
             <?php
         }
         ?>

        <div class="form-floating">
            <div>Username</div>
            <input type="text" name="username" class="form-control" id="floatingInput" placeholder="username">
        </div>
        <div class="form-floating">
            <div>Password</div>
            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
    </form>
</main>
</body>
</html>