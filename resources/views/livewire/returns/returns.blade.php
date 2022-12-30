<div>

    <div class="card">
        <div class="card-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">المرتجعات</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="buttons m-3">
                    <a href="#" class="btn btn-success btn-sm m-0 mx-1" wire:click='accept'>موافقة</a>
                    <a href="#" class="btn btn-danger btn-sm m-0 mx-1" wire:click='reject'>رفض</a>
                    <a href="#" class="btn btn-success btn-sm m-0 mx-1" wire:click='export'><i
                            class="bi-file-earmark-spreadsheet-fill"></i> </a>
                    <span>|</span>
                    @include('components.datepicker')

                    <div class="d-inline-flex">
                        <input style="width: 100px" type="text" class="form-control" aria-label="Recipient's username"
                            aria-describedby="button-addon2" wire:model='searchTerm' placeholder="بحث">
                    </div>
                    <span>&nbsp;|</span>

                    <div class="d-inline-flex align-items-center">
                        <h6 class="px-2"> 🤵🏻‍♂️</h6>
                        <fieldset class="form-group paginate-select" style="width: 75px;">
                            <select wire:model='app_user_id' class="form-select" id="basicSelect">
                                <option value="">الكل</option>
                                @foreach ($app_users as $app_user)
                                <option value="{{ $app_user->id }}">{{ $app_user->name }}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>

                    <div class="d-inline-flex align-items-center">
                        <h6 class="px-2">الحالة</h6>
                        <fieldset class="form-group paginate-select" style="width: 100px;">
                            <select wire:model='processingStatus' class="form-select" id="basicSelect">
                                <option value="">الكل</option>
                                <option value="ignored"> 🟢</option>
                                <option value="accepted">🔵</option>
                                <option value="rejected">🔴</option>
                            </select>
                        </fieldset>
                    </div>

                    <div class="d-inline-flex align-items-center" style="width: 150px;">
                        <h6 class="px-2"> المنتج</h6>
                        <fieldset class="form-group paginate-select" style="width: 200px;">
                            <select wire:model='search_product_id' class="form-select" id="basicSelect">
                                <option value="">الكل</option>
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.returns.delete_returns')

    <div class="row" id="table-striped" wire:loading>
        <div class="col-12">
            <div class="card p-2">
                <div class=" d-inline-flex card-header justify-content-between">
                    <div class=" d-inline-flex align-items-baseline">
                        <h3 class="card-title me-5"> المرتجعات </h3>
                        <div class=" d-inline-flex mb-1">
                            {{-- <div class="d-flex align-items-center">
                                <h6 class="px-2">الحالة</h6>
                                <fieldset class="form-group paginate-select" style="width: 100px;">
                                    <select wire:model='processingStatus' class="form-select" id="basicSelect">
                                        <option value="">الكل</option>
                                        <option value="ignored">🟢</option>
                                        <option value="accepted">🔵</option>
                                        <option value="rejected">🔴</option>
                                    </select>
                                </fieldset>
                            </div>

                            <div class="d-flex align-items-center">
                                <h6 class="px-2"> المنتج</h6>
                                <fieldset class="form-group paginate-select" style="width: 200px;">
                                    <select wire:model='search_product_id' class="form-select" id="basicSelect">
                                        <option value="">الكل</option>
                                        @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div> --}}

                            {{-- <div class="d-flex align-items-center">
                                <h6 class="px-2"> 🤵🏻‍♂️</h6>
                                <fieldset class="form-group paginate-select" style="width: 200px;">
                                    <select wire:model='app_user_id' class="form-select" id="basicSelect">
                                        <option value="">الكل</option>
                                        @foreach ($app_users as $app_user)
                                        <option value="{{ $app_user->id }}">{{ $app_user->name }}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div> --}}
                        </div>
                    </div>

                </div>
                @include('livewire.returns.edit_return')

                <div class="card-content">
                    <!-- table striped -->
                    <div class="table-bordered">
                        <table class="table mb-0" id="example">
                            <thead>
                                <th>
                                    <input wire:model="checked" type="checkbox"
                                        class="form-check-input form-check-primary">
                                </th>
                                <th>#</th>
                                <th>المرتجع</th>
                                <th>🤵🏻‍♂️</th>
                                <th>🟥</th>
                                <th>🟦</th>
                                <th>المنتج</th>
                                <th>📩</th>
                                <th>الحالة</th>
                                <th>⏱</th>
                                <th>تاريخ الشراء</th>
                                {{-- <th>عمليات</th> --}}


                            </thead>
                            <tbody>
                                @dump($selectedRows)

                                {{-- @dump($processingStatus) --}}
                                @forelse ($returns as $return)
                                <tr>
                                    <td>
                                        <input wire:model.lazy="selectedRows" value="{{ $return->id }}" type="checkbox"
                                            class="form-check-input form-check-primary">
                                    </td>
                                    <td>{{ $return->id }}</td>
                                    <td>{{ $return->return }}</td>
                                    <td>{{ $return->user->name }}</td>
                                    <td>{{ count($app_users->find($return->app_user_id)->returns) }}</td>
                                    <td>{{ count($app_users->find($return->app_user_id)->orders) }}</td>
                                    <td>{{ $return->product->name }}</td>
                                    <td>{{ $return->reason }}</td>
                                    <td>
                                        @if ($return->status == 'ignored')
                                        <span>🟢</span>
                                        @elseif ($return->status == 'rejected')
                                        <span>🔴</span>
                                        @elseif ($return->status == 'accepted')
                                        <span> 🔵 </span>
                                        @endif
                                    </td>

                                    <td>{{
                                        \Carbon\Carbon::parse($return->created_at)->diff(\Carbon\Carbon::parse($orders->where('id',$return->order_id)->first()->created_at))->format('%H:%I')
                                        }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($return->created_at)->isoFormat('Y-M-D h a') }}
                                    </td>
                                </tr>
                                @empty
                                @include('components.not-found')
                                @endforelse
                            </tbody>
                        </table>
                        {{ $returns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
