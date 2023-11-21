<div class="invoice">
    <div class="invoice-print">
        <div class="row">
            <div class="col-lg-12">
                <div class="invoice-title">
                    <h2>Invoice</h2>
                    <div class="invoice-number">#{{ $kode_transaksi }}</div>
                </div>
                <hr>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="section-title">Order Summary</div>
                <p class="section-lead">All items here cannot be deleted.</p>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-md">
                        <tbody>
                            <tr>
                                <th data-width="40" style="width: 40px;">#</th>
                                <th>Item</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-right">Totals</th>
                            </tr>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($filtered_barangs as $barang)
                            <tr>
                                <td>1</td>
                                <td>{{ $barang['barang']->name }}</td>
                                <td class="text-center">{{ $barang['barang']->price }}</td>
                                <td class="text-center">{{ $barang['qty'] }}</td>
                                <td class="text-right">Rp. {{ number_format($barang['barang']->price * $barang['qty'], 0, ',', '.') }}</td>
                            </tr>
                            @php
                                $total += $barang['barang']->price * $barang['qty'];
                                if ($diskon) {
                                    $diskon = ($total * $diskon)/100;
                                    $total = $total - $diskon;
                                }
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-8">

                    </div>
                    <div class="col-lg-4 text-right">
                        <hr class="mt-2 mb-2">
                        <div class="invoice-detail-item">
                            <div class="invoice-detail-name">Total</div>
                            <div class="invoice-detail-value invoice-detail-value-lg">Rp. {{ number_format($total, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
