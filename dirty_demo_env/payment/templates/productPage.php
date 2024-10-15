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
<section class="py-5" style="background-color:#eeeeee;">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" src="assets/{{image}}" alt="..." /></div>
            <div class="col-md-6">
                <div class="small mb-1">SKU: {{sku}}</div>
                <h1 class="display-5 fw-bolder">{{name}}</h1>
                <div class="fs-5 mb-5">
                    <span>${{price}}</span>
                </div>
                <p class="lead">{{description}}!</p>
                <div class="d-flex">
                    <input class="form-control text-center me-3" id="inputQuantity" type="num" value="1" style="max-width: 3rem" />
                    <button class="btn btn-outline-dark flex-shrink-0" id="addtocart" data-id="{{id}}" type="button">
                        <i class="bi-cart-fill me-1"></i>
                        Add to cart
                    </button>
                </div>
                <hr/>
                <div class="small mb-1">If you like our products, share our site with a friend!</div>
                <form method="POST" action="?page=share">
                    <input class="form-control text-center me-3" name="sender" value="" placeholder="Your email">
                    <input class="form-control text-center me-3" name="receiver" value="" placeholder="Your friend's email">
                    <input class="btn btn-outline-dark flex-shrink-0" type="submit" value="Share now">
                </form>
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
    addtocart.addEventListener('click', function (e) {
        id = addtocart.dataset.id
        quantity = inputQuantity.value
        fetch('index.php?page=add_cart&id=' + encodeURIComponent(id) + '&quantity=' + encodeURIComponent(quantity))
        // Not even shameful
        setTimeout(function () {
            location.reload()
        }, 1000);
    })
</script>
</body>
</html>
