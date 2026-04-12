@extends('layouts.dashboard')

@section('title', 'Daftar Batch Stok')
@section('page-title', 'Kelola Batch Stok')
@section('page-description', 'Informasi stok barang per batch beserta masa berlaku')

@section('content')
    @livewire('batch')
@endsection
