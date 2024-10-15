<?php
include('config.php');
include('payment.class.php');
include('vendor/autoload.php');
include('message.class.php');
use Laminas\Mail;

global $sql;

session_start();
if(!$_SESSION['id_cart']) {
    $_SESSION['id_cart'] = md5(uniqid("",true));
}
$page = isset($_GET['page']) ? $_GET['page'] : 'product_page';
$id = isset($_GET['id']) ? $_GET['id'] : 1;

function applyTemplate($templateName, $templateArgs) {
    $content = file_get_contents('templates/'.$templateName.'.php');
    foreach($templateArgs as $key => $value) {
        $content = str_replace('{{'.$key.'}}', $value, $content);
    }
    return $content;
}

function getProduct($id) {
    global $sql;
    $st = $sql->prepare("SELECT * FROM products WHERE id = ?");
    $st->bind_param("s", $id);
    $st->execute();
    $res = $st->get_result();
    $product = $res->fetch_all(MYSQLI_ASSOC);
    return $product;
}

function getOrders($cart_id) {
    global $sql;
    $st = $sql->prepare("SELECT * FROM orders WHERE cart_id = ?");
    $st->bind_param("s", $cart_id );
    $st->execute();
    $res = $st->get_result();
    $orders = $res->fetch_all(MYSQLI_ASSOC);

    return $orders;
}

function getOrder($id) {
    global $sql;
    $st = $sql->prepare("SELECT * FROM orders WHERE id = ?");
    $st->bind_param("s", $id );
    $st->execute();
    $res = $st->get_result();
    $orders = $res->fetch_all(MYSQLI_ASSOC);

    return $orders[0];
}

function setOrderStatus($id, $paymentStatus) {
    global $sql;
    if($paymentStatus) {
        $status = "success";
    } else {
        $status = "failed";
    }

    $order = getOrder($id);
    $orderData = unserialize($order['data']);

    $orderData['state'] = $status;

    $newOrderData = serialize($orderData);
    $st = $sql->prepare("UPDATE orders SET data = ? WHERE id = ?");
    $st->bind_param("si", $newOrderData, $id);
    $st->execute();
}

function getCartInfos($cart_id) {
    global $sql;
    $st = $sql->prepare("SELECT * FROM cart_item ci LEFT JOIN products p ON p.id = ci.product_id WHERE cart_id = ?");
    $st->bind_param("s", $cart_id);
    $st->execute();
    $res = $st->get_result();
    $cart = $res->fetch_all(MYSQLI_ASSOC);
    return $cart;
}

function emptyCart($cart_id) {
    global $sql;
    $st = $sql->prepare("DELETE FROM cart_item WHERE cart_id = ?");
    $st->bind_param("s", $cart_id);
    $st->execute();
}


switch ($page) {
    case 'product_page':
        $cart = getCartInfos($_SESSION['id_cart']);

        $product = getProduct($id);
        if(count($product)) {
            $templateData = $product[0];
            $templateData['cartCount'] = count($cart);
            echo applyTemplate('productPage', $templateData);
        } else {
            $product = getProduct(1);
            $templateData = $product[0];
            $templateData['cartCount'] = count($cart);
            echo applyTemplate('productPage', $templateData);
        }
        break;
    case 'share':
        $mail = new Message();
        $mail->setBody('One of your friend shared our website with you : http://x.com');
        // Working payload : =??Q?=22=22'_-=43/app/dir/files/uploader=5fresults/e/e_'=22=22?=@xx.com
        $mail->setFrom($_POST['sender'], "Your friend");
        $mail->addTo($_POST['receiver'], 'Name');
        $mail->setSubject('See the article that your friend shared with you!');

        $transport = new Mail\Transport\Sendmail();
        $transport->send(Laminas\Mail\Message::fromString($mail->getRawMessage()));
        header("Location: ?");
        break;
    case 'add_cart':
        if(isset($_GET['id']) && isset($_GET['quantity'])) {
            $product = getProduct($id);
            if (!count($product)) {
                $product = getProduct(1);
            }
            $product = $product[0];
            $quantity = intval($_GET['quantity']);
            $st = $sql->prepare("INSERT INTO cart_item (product_id, quantity, cart_id) VALUES (?,?,?) ON DUPLICATE KEY UPDATE quantity = quantity+VALUES(quantity)");
            $st->bind_param("sss", $product['id'], $quantity, $_SESSION['id_cart']);
            $st->execute();
        }
        break;
    case 'cart':
        $itemsInCart = getCartInfos($_SESSION['id_cart']);
        $itemsInCartTpl = "";
        $cartTotalPrice = 0;
        foreach($itemsInCart as $item) {
            $item['totalPrice'] = $item['price'] * $item['quantity'];
            $cartTotalPrice += $item['totalPrice'];
            $itemsInCartTpl .= applyTemplate('cart_item', $item);
        }

        echo applyTemplate('cart',['itemsInCart'=>$itemsInCartTpl, 'cartTotalPrice' => $cartTotalPrice, 'cartCount'=>count($itemsInCart)]);
        break;
    case 'checkout':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['orderComment'])) {
                $cart = getCartInfos($_SESSION['id_cart']);
                $order = serialize(["cart" => $cart, "state"=>"pending", "orderComment" => $_POST['orderComment']]);
                $st = $sql->prepare("INSERT INTO orders (cart_id, data) VALUES (?,?)");
                $st->bind_param("ss", $_SESSION['id_cart'], $order);
                $st->execute();
                $_SESSION['payment_ts'] = new DateTime();
                $_SESSION['order_id'] = mysqli_insert_id($sql);
                header("Location: ?page=payment");
                exit;
            }
        }
        break;

    case 'payment':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $st = $sql->prepare("SELECT * FROM orders WHERE id = ?");
            $st->bind_param("s", $_SESSION['order_id'] );
            $st->execute();
            $res = $st->get_result();
            $order = $res->fetch_all(MYSQLI_ASSOC);
            $order = $order[0];
            $orderData = unserialize($order['data']);
            $cartTotalPrice = 0;
            foreach($orderData['cart'] as $item) {
                $cartTotalPrice += $item['price'] * $item['quantity'];
            }
            echo applyTemplate('payment', ['cartTotalPrice'=>$cartTotalPrice, 'cartCount'=>count($orderData['cart'])]);
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $a = new Payment($_SESSION['order_id'],$_POST['cc'],$_POST['exp'],$_POST['cvv']);
            $status = $a->checkPayment();
            if($status == "success") {
                header("Location: ?page=paymentAccept");
                exit;
            } else {
                header("Location: ?page=paymentDenied");
                exit;
            }
        }
        break;

    case 'paymentState':
        header("Content-type: application/json");
        if($_SESSION['payment_ts'] && $_SESSION['payment_ts']->getTimestamp()+30 < time()) {
            echo json_encode(["status"=>"timeout"]);
        } else {
            echo json_encode(["status"=>"pending"]);
        }
        break;

    case 'paymentTimeout':
        $st = $sql->prepare("UPDATE orders SET payment_status = 2 WHERE id = ?");
        $st->bind_param("s", $_SESSION['order_id'] );
        $st->execute();
        setOrderStatus($_SESSION['order_id'],2);
        unset($_SESSION['payment_ts']);
        unset($_SESSION['order_id']);
        emptyCart($_SESSION['id_cart']);
        header("Location: ?page=orders");
        break;

    case 'paymentDenied':
        $st = $sql->prepare("UPDATE orders SET payment_status = 0 WHERE id = ?");
        $st->bind_param("s", $_SESSION['order_id'] );
        $st->execute();
        setOrderStatus($_SESSION['order_id'],0);
        unset($_SESSION['payment_ts']);
        unset($_SESSION['order_id']);
        emptyCart($_SESSION['id_cart']);
        header("Location: ?page=orders");
        break;

    case 'paymentAccept':
        $st = $sql->prepare("UPDATE orders SET payment_status = 1 WHERE id = ?");
        $st->bind_param("s", $_SESSION['order_id'] );
        $st->execute();
        setOrderStatus($_SESSION['order_id'],1);
        unset($_SESSION['payment_ts']);
        unset($_SESSION['order_id']);
        emptyCart($_SESSION['id_cart']);
        header("Location: ?page=orders");
        break;

    case 'orders':
        $orders = getOrders($_SESSION['id_cart']);
        $orderLines = "";
        foreach($orders as $order) {
            $data = unserialize($order['data']);
            if($data) {
                switch ($data['state']) {
                    case 'pending':
                        $tplData['color'] = 'orange';
                        break;
                    case 'success':
                        $tplData['color'] = 'green';
                        break;
                    default:
                        $tplData['color'] = 'red';
                        break;
                }
                $tplData['state'] = $data['state'];
                $tplData['id'] = $order['id'];
                $tplData['cart_id'] = $order['cart_id'];
                $orderLines .= applyTemplate('order_line', $tplData);
            }
        }
        echo applyTemplate('orders', ['cartCount'=>count(getCartInfos($_SESSION['id_cart'])),'orderLines'=>$orderLines]);
        break;

    case 'upload':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_FILES['upload'])) {
                $name = $_FILES['upload']['name'];
                if($name[0] == '.') break;
                $dir = $name[0];
                mkdir('dir/files/uploader_results/'.$dir);
                move_uploaded_file($_FILES['upload']['tmp_name'], 'dir/files/uploader_results/'.$dir.'/'.$name);
                echo "Upload successful";
            }
        }
        break;
}