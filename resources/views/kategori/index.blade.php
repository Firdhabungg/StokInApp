@extends('layouts.dashboard')

@section('title', 'Kategori Barang')
@section('page-title', 'Kelola Kategori')
@section('page-description', 'Kelola kategori barang untuk toko Anda')

@section('content')
    @livewire('kategori-index')
@endsection
