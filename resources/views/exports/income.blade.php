<html>

<head>
    <title>
        Invoice
    </title>
</head>

<body>
    <div class="title" style="padding-bottom: 13px">
        <div style="text-align: center;text-transform: uppercase;font-size: 15px">
            {{ $setting->shop_name }}
        </div>
        <div style="text-align: center">
            Alamat: {{ $setting->address }}
        </div>
        <div style="text-align: center">
            Telp: {{ $setting->phone }}
        </div>
    </div>
    <table style="width: 100%">
        <thead>
            <tr style="background-color: #e6e6e7;">
                <th scope="col">Date</th>
                <th scope="col">Invoice</th>
                <th scope="col">Cashier</th>
                <th scope="col">Customer</th>
                <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($income as $order)
            <tr>
                <td>{{ $order->created_at }}</td>
                <td>{{ $order->invoice_number }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->customer->name }}</td>
                <td class="text-end">{{ $order->grand_total }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-end fw-bold" style="background-color: #e6e6e7;">TOTAL</td>
                <td class="text-end fw-bold" style="background-color: #e6e6e7;">{{ $total_income }}</td>
            </tr>
        </tbody>
    </table>
</body>



</html>