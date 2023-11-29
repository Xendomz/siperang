@extends('layouts.app')

@section('title', 'Transaksi')

@push('style')
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Transaksi</h1>
            </div>

            <div class="section-body">
                <div class="invoice-section">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="invoice-title">
                                        <h2>Invoice</h2>
                                        <div class="invoice-number">#{{ $transaksi->kode_transaksi }}</div>
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
                                                @foreach ($transaksi->transaksiBarangs as $barang)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $barang->barang->name }}</td>
                                                        <td class="text-center">{{ $barang->barang->price }}</td>
                                                        <td class="text-center">{{ $barang->qty }}</td>
                                                        <td class="text-right">Rp.
                                                            {{ number_format($barang->barang->price * $barang->qty, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $total += $barang->barang->price * $barang->qty;
                                                    @endphp
                                                @endforeach
                                                @php
                                                    if ($transaksi->diskon) {
                                                        $diskon = ($total * $transaksi->diskon) / 100;
                                                        $total = $total - $diskon;
                                                    }
                                                @endphp
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
                                                <div class="invoice-detail-value invoice-detail-value-lg">Rp.
                                                    {{ number_format($total, 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')

@endpush
