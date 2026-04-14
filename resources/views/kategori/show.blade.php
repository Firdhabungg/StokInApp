@extends('layouts.dashboard')

@section('title', 'Detail Kategori')

@section('content')
    <livewire:kategori-detail :kategoriId="$kategoriId" />
@endsection
