<html moznomarginboxes mozdisallowselectionprint>

<head>
    <title>
        Invoice
    </title>
    <style>
        body {
            font-family: monospace;
            /* Monospace font for a receipt look */
            margin: 20px;
        }

        .receipt {
            width: 300px;
            /* Adjust width as needed */
            border: 1px dashed #ccc;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 10px;
        }

        .items table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: medium;
        }

        .items th,
        .items td {
            padding: 8px;
            text-align: left;
            text-wrap: nowrap;
            overflow: hidden;
            white-space: nowrap;
        }

        .items th {
            background-color: #f0f0f0;
        }

        .items {
            margin-bottom: 20px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals {
            margin-bottom: 10px;
            border-top: 1px dashed #ccc;
            padding-top: 10px;
        }

        .footer {
            text-align: center;
            font-size: smaller;
        }

        .dashed-line {
            border-top: 1px dashed #ccc;
            margin: 10px 0;
        }

        .bold {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }
    </style>
    <script>
        window.print();
        window.onafterprint = function() {
            setTimeout(function() {
                window.close();
            }, 1000);
        }
    </script>
</head>

<body>
    <div class="receipt">
        <div class="header">
            <p>{{ $setting->shop_name }}</p>
            <p>{{ $setting->address }}</p>
            <p>Telp: {{ $setting->phone }}</p>
        </div>
        <div class="dashed-line"></div>
        <div class="info">
            <p>Nota: {{$order->invoice_number}}</p>
            <p>Kasir: {{$order->user->name}}</p>
            <p>Tgl. {{$order->created_at}}</p>
            <p>{{$order->customer->name}}</p>
        </div>
        <div class="dashed-line"></div>
        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th class="bold">Item</th>
                        <th class="bold">Qty</th>
                        <th class="bold">Harga</th>
                        <th class="bold">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderDetails as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ $item->product->formated_price }}</td>
                        <td class="text-right">{{ $item->formated_price }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- <div class="dashed-line"></div> -->
        <div class="totals">
            <div class="item">
                <span class="bold">Sub Total</span>
                <span class="text-right">{{$order->formated_sub_total}}</span>
            </div>
            <div class="item">
                <span>Diskon</span>
                <span class="text-right">{{$order->formated_discount}}</span>
            </div>
            <div class="item">
                <span>PPN ({{ $setting->tax }}%)</span>
                <span class="text-right">{{$order->tax }}</span>
            </div>
            <div class="item">
                <span class="bold">Grand Total</span>
                <span class="text-right">{{$order->grand_total}}</span>
            </div>
            <div class="item">
                <span>BAYAR</span>
                <span class="text-right">{{$order->cash}}</span>
            </div>
            <div class="item">
                <span>KEMBALI</span>
                <span class="text-right">{{$order->change}}</span>
            </div>
        </div>
        <div class="dashed-line"></div>
        <div class="footer">
            <p>* Terima kasih atas kunjungan anda *</p>
        </div>
    </div>
</body>

</html>