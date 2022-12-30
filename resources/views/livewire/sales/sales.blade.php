<div>
    <div class="card">
        <div class="card-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">المبيعات</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="my-3">
                    <a href="#" class="btn btn-success btn-sm btn-excel" wire:click='export'><i
                            class="bi-file-earmark-spreadsheet-fill"></i> </a>

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
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="table-striped">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="d-flex align-items-baseline">
                        <h3 class="card-title me-5"> المنتجات</h3>
                        <div class="mb-1">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" aria-describedby="button-addon2"
                                    wire:model='searchTerm'>
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">بحث</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-content">
                    <!-- table striped -->
                    <div class="table-responsive p-2">
                        <table class="table table-bordered mb-0">
                            <thead class="table-primary ">
                                <th><input wire:model='checked' type="checkbox" class="form-check-input"></th>
                                <th>#</th>
                                <th>اسم المنتج</th>
                                <th>القسم</th>
                                <th>عدد المبيعات</th>
                                <th>مبلغ المبيعات</th>
                                <th> عدد المرتجعات </th>
                                <th> مبلغ المرتجعات </th>
                            </thead>
                            <tbody>
                                @forelse ($sales as $sale)
                                <tr>
                                    <td><input wire:model.lazy='selectedRows' value="{{ $sale->id }}" type="checkbox"
                                            class="form-check-input">
                                    </td>
                                    <td>{{ $sale->products->id }}</td>
                                    <td>
                                        {{ $sale->products->name }}
                                    </td>
                                    <td>
                                        {{ $sale->products->category->name }}
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $sale->count_sell }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $sale->sum_price}}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ count($sale->products->return->where('status', 'accepted')->where('created_at','>=',$from)->where('created_at','<=',$to)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ count($sale->products->return->where('status', 'accepted')->where('created_at','>=',$from)->where('created_at','<=',$to))*$sale->price
                                            }}
                                        </span>
                                    </td>

                                </tr>
                                @empty
                                @include('components.not-found')
                                @endforelse
                            </tbody>
                        </table>
                        <div class="my-3">
                            {{ $sales->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
