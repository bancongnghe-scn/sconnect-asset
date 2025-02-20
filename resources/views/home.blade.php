@extends('layouts.app',[
    'title' => 'Trang chủ'
])

@section('content')
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="procedure/shopping.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="procedure/asset.jpg" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
            <i class="bi bi-chevron-left color-sc" style="font-size: 2rem" aria-hidden="true"></i>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
            <i class="bi bi-chevron-right color-sc" style="font-size: 2rem" aria-hidden="true"></i>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
@endsection

