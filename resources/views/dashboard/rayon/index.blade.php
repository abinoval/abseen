@extends('layouts.app')

@section('title', 'Rayon | Abseen')

@section('heading')
   <div class="pt-4 pb-2">
      <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
         <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
               <a href="/dashboard">
                  <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                     </path>
                  </svg>
               </a>
            </li>
            <li class="breadcrumb-item"><a href="/dashboard/rayon">Rayon</a></li>
         </ol>
      </nav>
      <div class="d-flex justify-content-between align-items-center w-100 flex-wrap">
         <h1 class="h4 mb-3 mb-lg-0">Data Rayon</h1>
      </div>
   </div>
@endsection

@section('content')
   @if (session()->has('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
         {{ session('success') }}
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
   @endif
   @if (Auth::user()->role == 'Admin')
      <div class="card border-0 shadow mb-4">
         <div class="card-header">
            Tambah Rayon
         </div>
         <form action="/dashboard/rayon" method="POST" class="card-body d-flex align-items-start gap-2">
            @csrf

            <div class="w-100">
               <input type="text" class="form-control @if (!session()->has('status')) @error('name') is-invalid @enderror @endif" autocomplete="off" id="name"
                  name="name"
                  placeholder="Nama Rayon...">
               @if (!session()->has('status'))
                  @error('name')
                     <div class="invalid-feedback">
                        {{ $message }}
                     </div>
                  @enderror
               @endif
            </div>
            <button type="submit" class="btn btn-primary">
               Tambah
            </button>

         </form>
      </div>

      <div id="card-edit" class="card border-0 shadow mb-4 {{ session()->has('status') ? 'd-block' : 'd-none' }}">
         <div class="card-header">
            Edit Rayon
         </div>
         <form action="/dashboard/rayon/{{ session()->has('status') ? session('status')->id : '' }}" id="form-crud"
            method="POST" class="card-body d-flex align-items-start gap-2">
            @csrf
            @method('PUT')
            <div class="w-100" id="input-crud">
               <input type="text" class="form-control @error('name') is-invalid @enderror" autocomplete="off" id="name"
                  name="name"
                  placeholder="Nama Rayon..."
                  value="{{ old('name', session()->has('status') ? session('status')->name : null) }}">
               @error('name')
                  <div class="invalid-feedback">
                     {{ $message }}
                  </div>
               @enderror
            </div>
            <button type="submit" id="btn-crud" class="btn btn-primary">
               Edit
            </button>

         </form>
      </div>
   @endif
   <div class="card border-0 shadow mb-4">
      <div class="card-header">
         <div class="input-group input-group-merge search-bar">
            <span class="input-group-text" id="topbar-addon">
               <svg class="icon icon-xs" x-description="Heroicon name: solid/search"
                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd"
                     d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                     clip-rule="evenodd"></path>
               </svg>
            </span>
            <input type="text" class="form-control" id="topbarInputIconLeft" placeholder="Cari rombel"
               aria-label="Search" aria-describedby="topbar-addon">
         </div>
      </div>
      <div class="card-body">
         <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
               <thead class="thead-light">
                  <tr>
                     <th class="border-0 rounded-start">#</th>
                     <th class="border-0">Nama</th>
                     <th class="border-0">Jumlah Siswa</th>
                     @if (Auth::user()->role == 'Admin')
                        <th class="border-0 rounded-end">Action</th>
                     @endif
                  </tr>
               </thead>
               <tbody>
                  @foreach ($data as $row)
                     <tr>
                        <td class="text-primary fw-bold">
                           {{ ++$i }}
                        </td>
                        <td class="fw-bold">
                           {{ $row->name }}
                        </td>
                        <td class="fw-bold">
                           @if ($row->user->count() != 0)
                              <a href="/dashboard/rayon/{{ $row->id }}/siswa">
                                 {{ $row->user->where('role', 'Siswa')->count() }} Siswa
                              </a>
                           @else
                              0 Siswa
                           @endif
                        </td>
                        @if (Auth::user()->role == 'Admin')
                           <td>
                              <button type="button" data-id="{{ $row->id }}" class="btn btn-warning btn-edit"
                                 href="#">Edit</button>
                              <button type="button" data-id="{{ $row->id }}" data-bs-toggle="modal"
                                 data-bs-target="#modal-notification" class="btn btn-danger btn-delete"
                                 href="#">Hapus</button>
                           </td>
                        @endif
                     </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog"
      aria-labelledby="modal-notification" aria-hidden="true">
      <div class="modal-dialog modal-danger modal-dialog-centered" role="document">
         <div class="modal-content bg-gradient-secondary">
            <button type="button" class="btn-close theme-settings-close fs-6 ms-auto" style="z-index: 5;"
               data-bs-dismiss="modal"
               aria-label="Close"></button>
            <div class="modal-body text-white">
               <div class="py-3 text-center">
                  <span class="modal-icon">
                     <svg class="icon icon-xl text-gray-200" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                           d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z">
                        </path>
                        <path
                           d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z">
                        </path>
                     </svg>
                  </span>
                  <h2 class="h4 modal-title my-3">Important message!</h2>
                  <p>
                     Apakah kamu yakin ingin menghapus rombel ini? Data 50 siswa yang terdaftar ke rombel ini juga akan
                     terhapus!
                  </p>
               </div>
            </div>
            <form class="modal-footer" method="POST" id="form-delete">
               @csrf
               @method('DELETE')
               <button type="submit" class="btn btn-sm btn-white">Tetap Hapus</button>
            </form>
         </div>
      </div>
   </div>
   <div id="loader">
      <div class="lds-ring">
         <div></div>
         <div></div>
         <div></div>
         <div></div>
      </div>
   </div>
   <script>
      document.addEventListener("DOMContentLoaded", function() {

         document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
               document.querySelector('#loader').style.opacity = 1;
               document.querySelector('#loader').style.pointerEvents = 'auto';

               const id = this.dataset.id;
               fetch('/dashboard/rayon/' + id + '/edit', {
                     method: 'GET',
                     headers: {
                        'Content-Type': 'application/json',
                     }
                  })
                  .then(response => response.json())
                  .then(data => {
                     document.querySelector('#input-crud input').value = data.name;
                     document.querySelector('#card-edit').classList.remove('d-none');
                     document.querySelector('#card-edit').classList.add('d-block');
                     document.querySelector('#form-crud').setAttribute('action',
                        '/dashboard/rayon/' + id);
                     document.querySelector('#loader').style.opacity = 0;
                     document.querySelector('#loader').style.pointerEvents = 'none';
                  })
                  .catch((error) => {
                     console.error('Error:', error);
                     document.querySelector('#loader').style.opacity = 0;
                     document.querySelector('#loader').style.pointerEvents = 'none';
                  });
            })
         });

         document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
               document.querySelector('#loader').style.opacity = 1;
               document.querySelector('#loader').style.pointerEvents = 'auto';

               const id = this.dataset.id;
               fetch('/dashboard/rayon/' + id, {
                     method: 'GET',
                     headers: {
                        'Content-Type': 'application/json',
                     }
                  })
                  .then(response => response.json())
                  .then(data => {
                     document.querySelector('#modal-notification p').innerHTML =
                        `Apakah kamu yakin ingin menghapus rayon ini? Data ${data.count} siswa yang terdaftar ke rayon ini juga akan terhapus!`;
                     document.querySelector('#form-delete').setAttribute('action',
                        '/dashboard/rayon/' + data[0].id);
                     document.querySelector('#loader').style.opacity = 0;
                     document.querySelector('#loader').style.pointerEvents = 'none';
                  })
                  .catch((error) => {
                     console.error('Error:', error);
                     document.querySelector('#loader').style.opacity = 0;
                     document.querySelector('#loader').style.pointerEvents = 'none';
                  });
            })
         });

      });
   </script>
@endsection
