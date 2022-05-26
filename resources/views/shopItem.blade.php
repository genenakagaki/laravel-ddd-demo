<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">


    <title>Laravel</title>
</head>
<body>
<h1>Online Shop: Shop item page</h1>

<pre>{{json_encode($data, JSON_PRETTY_PRINT)}}</pre>

<label for="" class="form-label">数量</label>
<input type="number" class="form-control" id="countInput">

<p>請求情報</p>
<pre id="billing"></pre>

<button id="purchaseButton">Buy Item</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const countInput = document.getElementById('countInput');
    const purchaseButton = document.getElementById('purchaseButton');
    const billingDiv = document.getElementById('billing');
    countInput.addEventListener('change', getBillingInfo);
    purchaseButton.addEventListener('click', handlePurchase);

    function getBillingInfo(event) {
        axios.get('/shop/{{$data->shopItemId}}/billing/' + event.target.value)
            .then(function (response) {
                // handle success
                console.log(response);
                billingDiv.innerHTML = JSON.stringify(response.data, null, 4);
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });
    }

    function handlePurchase() {

        axios.post('/shop/purchase', {
            shopItemId: {{$data->shopItemId}},
            itemPurchaseCount: countInput.value,
            buyerId: 1002,
            buyerPaymentAmount: JSON.parse(billingDiv.innerHTML).billedAmountToBuyer
        })
            .then(function (response) {
                // handle success
                console.log(response);
                location.reload();
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });
    }

</script>
</body>
</html>
