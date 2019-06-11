@extends('admin.home')

@section('content')
<div class="px-2">
    <div class="row">
        <div class="col-md-3">
            <info-component></info-component>
        </div>
        <div class="col-md-9">
            <router-view></router-view>
        </div>
    </div>
</div>
@endsection
