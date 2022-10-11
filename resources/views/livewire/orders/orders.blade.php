<div>

    <div class="card">
        <div class="card-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">الطلبات</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="buttons m-3">
                    @include('components.datepicker')
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="table-striped">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="d-flex align-items-baseline">
                        <h3 class="card-title me-5"> الطلبات</h3>
                        <div class="mb-1">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" aria-label="Recipient's username"
                                    aria-describedby="button-addon2" wire:model='searchTerm'>
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">بحث</button>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="card-content">
                    <!-- table striped -->
                    <div class="table-responsive p-2">
                        <table class="table mb-0">
                            <thead>
                           
                                <th>#</th>
                                <th>اسم المنتج</th>
                                <th>السعر</th>
                                <th>القسم</th>
                                <th>المستخدم</th>
                                <th>التاريخ</th>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>

                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->product_name }}</td>
                                        <td>{{ $order->price }}</td>
                                        <td>{{ \App\Models\Category::find($order->product_id)->name }}</td>
                                        <td>{{ \App\Models\AppUser::find($order->app_user_id)->name }}</td>
                                        <td>{{ $order->created_at }}</td>
                                    </tr>
                                @empty
                                    @include('components.not-found')
                                @endforelse
                            </tbody>
                        </table>
                        <div class="my-3">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>