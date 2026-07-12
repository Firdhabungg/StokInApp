@extends('layouts.dashboard')

@section('title', 'Daftar Batch Stok')
@section('page-title', 'Daftar Batch Stok')
@section('page-description', 'Informasi stok barang per batch beserta masa berlaku')

@section('content')
    @livewire('batch')
@endsection
