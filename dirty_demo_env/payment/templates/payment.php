<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Super cool shop</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="#!">Super good shop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="?">Home</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="?page=orders">Orders</a></li>            </ul>
                <button class="btn btn-outline-dark" onclick="location='?page=cart'">
                    <i class="bi-cart-fill me-1"></i>
                    Cart
                    <span class="badge bg-dark text-white ms-1 rounded-pill">{{cartCount}}</span>
                </button>
        </div>
    </div>
</nav>
<!-- Product section-->
<section class="h-100 h-custom" style="background-color: #eeeeee;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12">
                <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-lg-8">
                                <div class="p-5">
                                    <div class="d-flex justify-content-between align-items-center mb-5">
                                        <h1 class="fw-bold mb-0">Payment system</h1>
                                    </div>

                                    <form method="POST" action="?page=payment">
                                        <h5 class="text-uppercase mb-3">Credit card payment system</h5>

                                        <div class="mb-5">
                                            <div data-mdb-input-init class="form-outline">
                                                <label class="form-label" for="form3Examplea2">Credit card number</label>
                                                <input type="text" name="cc" id="form3Examplea2" class="form-control form-control-lg"></input>
                                            </div>
                                            <hr class="my-4">

                                            <div data-mdb-input-init class="form-outline">
                                                <label class="form-label" for="form3Examplea2">Expiration date</label>
                                                <input type="text" name="exp" id="form3Examplea2" class="form-control form-control-lg"></input>
                                            </div>
                                            <hr class="my-4">

                                            <div data-mdb-input-init class="form-outline">
                                                <label class="form-label" for="form3Examplea2">CVV</label>
                                                <input type="text" name="cvv" id="form3Examplea2" class="form-control form-control-lg"></input>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <div class="d-flex justify-content-between mb-5">
                                            <h5 class="text-uppercase">Total price to pay</h5>
                                            <h5>$ {{cartTotalPrice}}</h5>
                                        </div>

                                        <button  type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-block btn-lg"
                                                 data-mdb-ripple-color="dark">Pay</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Footer-->
<footer class="py-5 bg-dark">
    <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Another French Shop Selling Croissants 2024</p></div>
</footer>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script>
    setInterval(function(){
        fetch('?page=paymentState').then(function(f){f.json().then(function(e){
            if((e.status == "timeout")) {
                location = '?page=paymentTimeout';
            }
        })})
    },3000)
</script>

</body>
</html>
