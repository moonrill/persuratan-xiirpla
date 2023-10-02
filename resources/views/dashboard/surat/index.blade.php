@php use Illuminate\Support\Facades\Storage;use Illuminate\Support\Facades\URL; @endphp
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
                            <form id="tambah-surat-form" enctype="multipart/form-data">
                                <div class="form-group">
                                    @auth
                                        <input type="hidden" name="id_user" class="d-none" value="{{ Auth::user()["id"] }}">
                                    @endauth
                                    <label>Jenis Surat</label>
                                    <select name="id_jenis_surat" id="jenisSurat" class="form-select mb-3">
                                        <option selected value="">Pilih jenis surat</option>
                                        @foreach($jenis_surat as $js)
                                            <option value="{{$js->id}}">{{$js->jenis_surat}}</option>
                                        @endforeach
                                    </select>
                                    <label>Tanggal Surat</label>
                                    <input type="datetime-local" name="tanggal_surat" id="tanggalSurat" class="form-control mb-3">
                                    <label>Ringkasan</label>
                                    <textarea name="ringkasan" class="form-control mb-3" rows="7"
                                              placeholder="Tulis ringkasan surat disini..."
                                              style="resize: none"></textarea>
                                    <label class="d-block">File : </label>
                                    <div class="row d-flex align-items-center">
                                        <div class="col-3">
                                            <label for="fileUpload" class="btn p-1 w-100 btn-outline-success form-control">Upload
                                                File</label>
                                            <input type="file" accept=".pdf" name="file" id="fileUpload" class="d-none">
                                        </div>
                                        <div class="col p-0">
                                            <p class="fileName m-0 d-inline-block"></p>
                                        </div>
                                    </div>
                                    @csrf
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="clearText()" data-bs-dismiss="modal">
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
                                <td class="col-1">{{$no++}}</td>
                                <td class="col-1">{{$s->jenis->jenis_surat}}</td>
                                <td class="col-1">{{$s->user->username}}</td>
                                <td class="col-2">{{$s->tanggal_surat}}</td>
                                <td>{{$s->ringkasan}}</td>
                                <td class="col-1">
                                    @if($s->file)
                                        <a class="btn btn-primary" href="{{url("dashboard/surat?path=$s->file", ['download'])}}">Download</a>
                                    @else
                                        <p>No File</p>
                                    @endif
                                </td>
                                <td class="col-2">
                                    <!-- Button trigger edit modal -->
                                    <button type="button" class="editBtn btn btn-warning" data-bs-toggle="modal"
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
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Surat</h1>
                                        </div>
                                        <div class="modal-body">
                                            <form id="edit-surat-form-{{$s->id}}">
                                                <div class="form-group">
                                                    @auth
                                                        <input type="hidden" name="id_user" class="d-none" value="{{ Auth::user()["id"] }}">
                                                    @endauth
                                                    <label>Jenis Surat</label>
                                                    <select name="id_jenis_surat" id="jenisSurat" class="form-select mb-3">
                                                        @foreach($jenis_surat as $js)
                                                            <option value="{{$js->id}}" @if($js->id === $s->id_jenis_surat) selected
                                                                @endif>{{$js->jenis_surat}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label>Tanggal Surat</label>
                                                    <input type="datetime-local" name="tanggal_surat" id="tanggalSurat" class="form-control mb-3"
                                                           value="{{$s->tanggal_surat}}">
                                                    <label>Ringkasan</label>
                                                    <textarea name="ringkasan" class="form-control mb-3" rows="7"
                                                              placeholder="Tulis ringkasan surat disini..."
                                                              style="resize: none">{{$s->ringkasan}}
                                                    </textarea>
                                                    <label class="d-block">File : </label>
                                                    <div class="row d-flex align-items-center">
                                                        <div class="col-3">
                                                            {{-- TODO: Fix can't upload file --}}
                                                            <label for="fileUpload" class="btn p-1 w-100 btn-outline-success form-control">Upload
                                                                File</label>
                                                            <input type="file" name="file" id="fileUpload" class="d-none">
                                                        </div>
                                                        <div class="col p-0">
                                                            <p class="fileName m-0 d-inline-block"></p>
                                                        </div>
                                                    </div>
                                                    @csrf
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" onclick="clearText()" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary edit-btn"
                                                    form="edit-surat-form-{{$s->id}}">
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
    <script>
        function clearText() {
            $(`.fileName`).text('');
            $('#fileUpload').val('');
        }
    </script>
    <script type="module">
        $('.table').DataTable();

        $('#fileUpload').on('change', function () {
            const fileName = $(this).val().replace(/.*(\/|\\)/, '');
            $(`.fileName`).text(fileName);
        })

        /*-------------------------- TAMBAH SURAT -------------------------- */
        $('#tambah-surat-form').on('submit', function (e) {
            e.preventDefault();
            let data = new FormData(e.target);
            console.log(Object.fromEntries(data))
            axios.post('/dashboard/surat', data, {
                'Content-Type': 'multipart/form-data'
            })
                .then((res) => {
                    $('#tambah-surat-modal').css('display', 'none')
                    swal.fire('Berhasil tambah data!', '', 'success').then(function () {
                        location.reload();
                    })
                })
                .catch((err) => {
                    swal.fire('Gagal tambah data!', '', 'warning');
                    console.log(err)
                });
        })

        /*-------------------------- EDIT SURAT -------------------------- */
        $('.editBtn').on('click', function (e) {
            $('#fileUpload').trigger('change');

            e.preventDefault();
            let idSurat = $(this).attr('idSurat');
            $(`#edit-surat-form-${idSurat}`).on('submit', function (e) {
                e.preventDefault();
                let data = new FormData(this);
                console.log(Object.fromEntries(data));
                axios.post(`/dashboard/surat/${idSurat}`, data)
                    .then((res) => {
                        console.log(res);
                        // $(`#edit-modal-${idJS}`).css('display', 'none')
                        // swal.fire('Berhasil edit data!', '', 'success').then(function () {
                        //     location.reload();
                        // })
                    })
                    .catch((err) => {
                        console.log(err)
                        swal.fire('Gagal tambah data!', '', 'warning');
                    })
            })
        })

        /*-------------------------- HAPUS USER -------------------------- */
        $('.table').on('click', '.hapusBtn', function () {
            let idSurat = $(this).closest('tr').attr('idSurat');
            swal.fire({
                title: "Apakah anda ingin menghapus data ini?",
                showCancelButton: true,
                confirmButtonText: 'Setuju',
                cancelButtonText: `Batal`,
                confirmButtonColor: 'red'
            }).then((result) => {
                if (result.isConfirmed) {
                    //dilakukan proses hapus
                    axios.delete(`/dashboard/surat/${idSurat}`)
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
