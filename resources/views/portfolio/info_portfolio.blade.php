@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="mb-4">Portfolio</h2>
        @can('crud portfolios')
            <div class="mb-4">
                <button id="addPortfolio" class="btn btn-primary">Add portfolio</button>
            </div>
        @endcan
        <table id="portfolioList" class="table stripe hover row-border noselect">
            <thead>
            <tr>
                <th>Id</th>
                <th>Parent Portfolio</th>
                <th>Portfolio</th>
                <th>Description</th>
                <th>Allocation min</th>
                <th>Allocation max</th>
                <th>Active</th>
            </tr>
            </thead>
        </table>
    </div>

    @include('portfolio.portfolio_modals')

@endsection