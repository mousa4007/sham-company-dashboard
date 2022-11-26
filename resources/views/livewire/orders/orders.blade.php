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
                <div class="my-3">
                    @include('components.datepicker')
                    <div class="d-inline-flex align-items-center" style="width: 150px;">
                        <h6 class="px-2"> المنتج</h6>
                        <fieldset class="form-group paginate-select" style="width: 200px;">
                            <select wire:model='product_id' class="form-select" id="basicSelect">
                                <option value="">الكل</option>
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="d-inline-flex align-items-center" style="width: 150px;">
                        <h6 class="px-2"> الأقسام</h6>
                        <fieldset class="form-group paginate-select" style="width: 200px;">
                            <select wire:model='category_id' class="form-select" id="basicSelect">
                                <option value="">الكل</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="d-inline-flex align-items-center" style="width: 150px;">
                        <h6 class="px-2"> المستخدم</h6>
                        <fieldset class="form-group paginate-select" style="width: 200px;">
                            <select wire:model='app_user_id' class="form-select" id="basicSelect" style="width: 100px">
                                <option value="">الكل</option>
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="d-inline-flex align-items-center" style="margin-right: 50px;">
                        <div class="input-group ">
                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" aria-label="Recipient's username"
                                aria-describedby="button-addon2" wire:model='$userIdSearchTerm' placeholder="رقم الطلب">
                            <button class="btn btn-outline-secondary" type="button" id="button-addon2">بحث</button>
                        </div>
                    </div>
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
                                    aria-describedby="button-addon2" wire:model='searchTerm' placeholder="رقم الطلب">
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
                                <th>المستخدم</th>
                                <th>القسم</th>
                                <th>اسم المنتج</th>
                                <th>السعر</th>
                                <th>مربح الوكيل</th>
                                <th>التاريخ</th>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        {{-- @dump(\App\Models\Category::all()); --}}
                                        <td>{{ $order->id }}</td>
                                        <td>{{ \App\Models\AppUser::find($order->app_user_id)  != null ? \App\Models\AppUser::find($order->app_user_id)->name : $order->app_user_id }}</td>
                                        <td>{{ \App\Models\Product::find($order->product_id)->category->name != null ? \App\Models\Product::find($order->product_id)->category->name  :$order->product_id }}</td>
                                        <td>{{ $order->product_name }}</td>
                                        <td>{{ $order->price }}</td>
                                        <td>{{ $order->profit }}</td>
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
