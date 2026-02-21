@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Welcome, {{ Auth::user()->name }}! 👨‍🍳</h1>
            
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row mt-4">
                <div class="col-md-3 mb-4">
                    <div class="card text-center h-100" style="border-top: 4px solid #667eea;">
                        <div class="card-body">
                            <i class="bi bi-tags" style="font-size: 2rem; color: #667eea;"></i>
                            <h5 class="card-title mt-3">Categories</h5>
                            <p class="card-text text-muted">Manage food categories</p>
                            <a href="{{ route('categories.index') }}" class="btn btn-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none;">
                                View All
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card text-center h-100" style="border-top: 4px solid #764ba2;">
                        <div class="card-body">
                            <i class="bi bi-box-seam" style="font-size: 2rem; color: #764ba2;"></i>
                            <h5 class="card-title mt-3">Ingredients</h5>
                            <p class="card-text text-muted">Manage inventory stock</p>
                            <a href="{{ route('ingredients.index') }}" class="btn btn-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none;">
                                View All
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card text-center h-100" style="border-top: 4px solid #667eea;">
                        <div class="card-body">
                            <i class="bi bi-cup-hot" style="font-size: 2rem; color: #667eea;"></i>
                            <h5 class="card-title mt-3">Menu Items</h5>
                            <p class="card-text text-muted">Create and manage menu</p>
                            <a href="{{ route('menu-items.index') }}" class="btn btn-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none;">
                                View All
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card text-center h-100" style="border-top: 4px solid #764ba2;">
                        <div class="card-body">
                            <i class="bi bi-cart-check" style="font-size: 2rem; color: #764ba2;"></i>
                            <h5 class="card-title mt-3">Orders</h5>
                            <p class="card-text text-muted">Track and manage orders</p>
                            <a href="{{ route('orders.index') }}" class="btn btn-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none;">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <h5 class="mb-0">Quick Stats</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">System is ready to use. Start by managing your restaurant inventory!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
