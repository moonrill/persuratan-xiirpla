@extends('layouts.app')
@section('title', 'Manajemen Surat')
@section('content')
    <div class="row">
        <div class="col d-flex justify-content-between mb-2">
            <a class="btn btn-primary" href="{{url('/dashboard')}}"><i class="bi-arrow-left-circle"></i>
                Kembali</a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#tambah-surat-modal"><i
                    class="bi bi-envelope-plus"></i> Tambah
            </button>
            <!-- Tambah Surat Modal -->
            <div class="modal fade" id="tambah-surat-modal" tabindex="-1"
                 aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Surat</h1>
                        </div>
                        <div class="modal-body">
                            <form id="tambah-surat-form">
                                <div class="form-group">
                                    <label>Jenis Surat</label>
                                    <select name="id_jenis_surat" id="jenisSurat" class="form-select mb-3">
                                        <option selected value="">Pilih jenis surat</option>
                                        @foreach($jenis_surat as $js)
                                            <option value="{{$js->id}}">{{$js->jenis_surat}}</option>
                                        @endforeach
                                    </select>
                                    <label>Tanggal Surat</label>
                                    <input type="date" name="tanggal_surat" id="tanggalSurat" class="form-control mb-3">
                                    <label>Ringkasan</label>
                                    <textarea name="ringkasan" class="form-control" rows="7"
                                              placeholder="Tulis ringkasan surat disini..."
                                              style="resize: none"></textarea>
                                    <label>File</label>
                                    <label for="fileUpload" class="btn w-auto btn-outline-success form-control">Upload
                                        File</label>
                                    <input type="file" accept=".pdf" name="file" id="fileUpload" class="d-none">
                                    @csrf
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" form="tambah-surat-form">Tambah</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center ">
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hovered DataTable">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Surat</th>
                            <th>User</th>
                            <th>Tanggal Surat</th>
                            <th>Ringkasan</th>
                            <th>File</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 1;
                        ?>
                        @foreach($surat as $s)
                            <tr idSurat="{{$s->id}}">
                                <td class="col-1">{{$no}}</td>
                                <td>{{$s->jenis->jenis_surat}}</td>
                                <td>{{$s->user->username}}</td>
                                <td>{{$s->tanggal_surat}}</td>
                                <td>{{$s->ringkasan}}</td>
                                <td class="col-1">
                                    @if($s->file)
                                        <a class="btn btn-primary">Download</a>
                                    @else
                                        <p>No File</p>
                                    @endif
                                </td>
                                <td class="col-2">
                                    <!-- Button trigger edit modal -->
                                    <button type="button" class="editBtn btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#edit-modal-{{$s->id}}" idSurat="{{$s->id}}">
                                        Edit
                                    </button>
                                    <button class="hapusBtn btn btn-danger">Hapus</button>
                                </td>
                            </tr>
                            <!-- Edit User Modal -->
                            <div class="modal fade" id="edit-modal-{{$s->id}}" tabindex="-1"
                                 aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Jenis Surat</h1>
                                        </div>
                                        <div class="modal-body">
                                            <form id="edit-js-form-{{$s->id}}">
                                                <div class="form-group">

                                                    @csrf
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary edit-btn"
                                                    form="edit-js-form-{{$s->id}}">
                                                Edit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('footer')
    <script type="module">
        $('.table').DataTable();
        /*-------------------------- TAMBAH USER -------------------------- */
        $('#tambah-surat-form').on('submit', function (e) {
            e.preventDefault();
            let data = new FormData(e.target);
            axios.post('/dashboard/surat/tambah', Object.fromEntries(data))
                .then(() => {
                    $('#tambah-surat-modal').css('display', 'none')
                    swal.fire('Berhasil tambah data!', '', 'success').then(function () {
                        location.reload();
                    })
                })
                .catch(() => {
                    swal.fire('Gagal tambah data!', '', 'warning');
                });
        })

        /*-------------------------- EDIT USER -------------------------- */
        $('.editBtn').on('click', function (e) {
            e.preventDefault();
            let idJS = $(this).attr('idJS');
            $(`#edit-js-form-${idJS}`).on('submit', function (e) {
                e.preventDefault();
                let data = Object.fromEntries(new FormData(e.target));
                data['id'] = idJS;
                axios.post(`/dashboard/surat/jenis/${idJS}/edit`, data)
                    .then(() => {
                        $(`#edit-modal-${idJS}`).css('display', 'none')
                        swal.fire('Berhasil edit data!', '', 'success').then(function () {
                            location.reload();
                        })
                    })
                    .catch(() => {
                        swal.fire('Gagal tambah data!', '', 'warning');
                    })
            })
        })

        /*-------------------------- HAPUS USER -------------------------- */
        $('.table').on('click', '.hapusBtn', function () {
            let idJS = $(this).closest('tr').attr('idJS');
            swal.fire({
                title: "Apakah anda ingin menghapus data ini?",
                showCancelButton: true,
                confirmButtonText: 'Setuju',
                cancelButtonText: `Batal`,
                confirmButtonColor: 'red'
            }).then((result) => {
                if (result.isConfirmed) {
                    //dilakukan proses hapus
                    axios.delete(`/dashboard/surat/jenis/${idJS}/delete`)
                        .then(function (response) {
                            console.log(response);
                            if (response.data.success) {
                                swal.fire('Berhasil di hapus!', '', 'success').then(function () {
                                    //Refresh Halaman
                                    location.reload();
                                });
                            } else {
                                swal.fire('Gagal di hapus!', '', 'warning');
                            }
                        }).catch(function (error) {
                        swal.fire('Data gagal di hapus!', '', 'error').then(function () {
                            //Refresh Halaman
                            location.reload();
                        });
                    });
                }
            });
        })
    </script>
@endsection
