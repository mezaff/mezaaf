@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>All Products</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">All Products</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    {{-- <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="" name="name" id="search-input"
                                tabindex="2" value="" aria-required="true" required="" autocomplete="off">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                        <div class="box-content-search">
                            <ul id="box-content-search"></ul>
                        </div>
                    </form> --}}
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.product.add') }}"><i class="icon-plus"></i>Add
                    new</a>
            </div>
            <div class="table-responsive">
                @if (Session::has('status'))
                <p class="alert alert-success">{{ Session::get('status') }}</p>
                @endif
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Sale Price</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Brand</th>
                            <th class="text-center">Featured</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td class="pname">
                                <div class="image">
                                    <img src="{{ asset('uploads/products/thumbnails') }}/{{ $product->image }}"
                                        alt="{{ $product->name }}" class="image">
                                </div>
                                <div class="name">
                                    <a href="#" class="body-title-2">{{ $product->name }}</a>
                                    <div class="text-tiny mt-3">{{ $product->slug }}</div>
                                </div>
                            </td>
                            <td class="text-right">{{ formatRupiah($product->regular_price) }}</td>
                            <td class="text-right">{{ formatRupiah($product->sale_price) }}</td>
                            <td>{{ $product->SKU }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->brand->name }}</td>
                            <td>{{ $product->featured == 0 ? 'No' : 'Yes' }}</td>
                            <td>{{ $product->stock_status }}</td>
                            <td class="text-center">{{ $product->quantity }}</td>
                            <td class="text-center">
                                <div class="list-icon-function">
                                    {{-- <a href="#" target="_blank">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </a> --}}
                                    <a href="{{ route('admin.product.edit', ['id' => $product->id]) }}">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>
                                    </a>
                                    <form action="{{ route('admin.product.delete', ['id' => $product->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="item text-danger delete">
                                            <i class="icon-trash-2"></i>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () { 
$('.delete').on('click', function (e) { 
    e.preventDefault();
    const form = $(this).closest('form');
    swal({
        title: "Are you sure?",
        text: "You want to delete this data?",
        type: "warning",
        buttons: ["No", "Yes"],
        confirmButtonColor: "#dc3545"
    }).then(function(result){
if (result) {
    form.submit();
}
    });
 });
 });


 //Search
//  $(function () {
//       $('#search-input').on('keyup', function () {
//         var searchQuery = $(this).val();
//         if (searchQuery.length > 0) {
//           $.ajax({
//             type: 'GET',
//             url: '{{ route('admin.search') }}',
//             data: { query: searchQuery },
//             dataType: 'json',
//             success: function (data) {
//               $('#box-content-search').html('');
//               $.each(data, function (index, item) {
//                 var url = "{{ route('admin.product.edit', ['id' => 'product_id_placeholder']) }}";
//                 var link = url.replace('product_id_placeholder', item.id);
  
//                 $('#box-content-search').append(`
//                   <li>
//                     <ul>
//                       <li class="product-item gap14 mb-10">
//                         <div class="image no-bg">
//                           <a href="${link}" class="body-text">
//                             <img src="{{ asset('uploads/products/thumbnails') }}/${item.image}" alt="${item.name}">
//                           </a>
//                         </div>
//                         <div class="flex items-center justify-between gap20 flex-grow">
//                           <div class="name">
//                             <a href="${link}" class="body-text">${item.name}</a>
//                           </div>
//                         </div>
//                       </li>
//                       <li class="mb-10">
//                         <div class="divider"></div>
//                       </li>
//                     </ul>
//                   </li>
//                 `);
//               });
//             },
//             error: function () {
//               console.error('Error fetching search results.');
//             },
//           });
//         } else {
//           $('#box-content-search').html('');
//         }
//       });
//     });
</script>
@endpush