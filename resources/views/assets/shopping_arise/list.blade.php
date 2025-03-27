@extends('layouts.app',[
    'title' => 'Đề xuất mua sắm phát sinh'
])

@section('content')
    <div x-data="shopping_arise_list">
        <div>
            <div class="card card-body">
                <div>
                    @include('assets.shopping_arise.filters')
                </div>

                <div class="mt-3">
                    @include('assets.shopping_arise.table')
                </div>
            </div>
        </div>

        {{--modal--}}
        @include('assets.shopping_arise.modal_insert')
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_arise/shopping_arise_list.js'
    ])
@endsection
