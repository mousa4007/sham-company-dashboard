<div>


    <div class="card">
        <div class="card-content">
 <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
            <li class="breadcrumb-item active" aria-current="page">تخصيص أسعار المنتجات</li>
        </ol>
    </nav>
        </div>
    </div>


    <div class="row" id="table-striped">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="d-flex align-items-baseline">
                        <h3 class="card-title me-5">  تخصيص أسعار المنتجات  </h3>
                    </div>
                </div>

                <div class="card-content">
                    <!-- table striped -->
                    <div class="table-responsive p-2">
                        <table class="table table-responsive-sm mb-0">
                            <thead>
                                <tr class="table-primary">
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الوصف</th>
                                    <th>الصورة</th>
                                    <th>عمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->description }}</td>
                                        <td><img src="{{ $category->image_url }}" width="50"></td>

                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#updateProductDiscount"
                                                wire:click='edit({{ $category->id }})'>
                                                تعديل
                                            </button>
                                            @include('livewire.discounts.product_discount_group_modal')
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="my-3">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





</div>
