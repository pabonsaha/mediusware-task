@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{route('product.index')}}" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="product_title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        <option class="" value="">Select Variant</option>
                        @foreach($variants as $data)
                            <optgroup label="{{$data->title}}" data-max-options="2">
                                @foreach ($data->variants_name as $item)
                                    <option value="{{$item->variant}}">{{$item->variant}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                        
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                    @error('price_from')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('price_to')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    {{-- <tr>
                        <td>1</td>
                        <td>T-Shirt <br> Created at : 25-Aug-2020</td>
                        <td>Quality product in low cost</td>
                        <td>
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">

                                <dt class="col-sm-3 pb-0">
                                    SM/ Red/ V-Nick
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">Price : {{ number_format(200,2) }}</dt>
                                        <dd class="col-sm-8 pb-0">InStock : {{ number_format(50,2) }}</dd>
                                    </dl>
                                </dd>
                            </dl>
                            <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr> --}}
                    @foreach ($products as $product)
                        <tr>
                            <td>{{$loop->iteration }}</td>
                            <td>{{$product->title}} <br> Created at : {{Carbon\Carbon::parse($product->created_at)->format('d-M-yy')}}</td>
                            <td>Quality product in low cost</td>
                            <td>
                                <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant{{$product->id}}">
                                    @foreach($product->product_variants_price as $variants_price)
                                        @if($variants_price->product_variant_one_relation||$variants_price->product_variant_two_relation||$variants_price->product_variant_three_relation)
                                            <dt class="col-sm-3 pb-0">
                                                {{optional($variants_price->product_variant_one_relation)->variant}}
                                                / {{optional($variants_price->product_variant_two_relation)->variant}}
                                                / {{optional($variants_price->product_variant_three_relation)->variant}}
                                            </dt>
                                            <dd class="col-sm-9">
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-4 pb-0">Price : {{ number_format($variants_price->price,2) }}</dt>
                                                    <dd class="col-sm-8 pb-0">InStock : {{ number_format($variants_price->stock,2) }}</dd>
                                                </dl>
                                            </dd>
                                        @endif
                                    @endforeach
                                    
                                </dl>
                                
                                <button onclick="$('#variant{{$product->id}}').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} out of {{$products->total()}}</p>
                </div>
                <div class="col-md-6">
                    {!! $products->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
