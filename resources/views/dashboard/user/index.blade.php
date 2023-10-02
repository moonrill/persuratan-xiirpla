@extends('layouts.app')
@section('title', 'Manajemen User')
@section('content')
    <div class="row">
        <div class="col d-flex justify-content-between mb-2">
            <a class="btn btn-primary" href="{{url('/dashboard')}}"><i class="bi-arrow-left-circle"></i>
                Kembali</a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#tambah-user-modal"><i
                    class="bi bi-person-fill-add"></i> Tambah
            </button>
            <!-- Tambah User Modal -->
            <div class="modal fade" id="tambah-user-modal" tabindex="-1"
                 aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah User</h1>
                        </div>
                        <div class="modal-body">
                            <form id="tambah-user-form">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input placeholder="Username" type="text" class="form-control mb-3" name="username"
                                           required/>
                                    <label>Password</label>
                                    <input placeholder="Password" type="text" name="password" class="form-control mb-3"
                                           required autocomplete="off">
                                    <label>Role</label>
                                    <select name="role" class="form-select mb-3" required>
                                        <option selected value="operator">Operator</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                    @csrf
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" form="tambah-user-form">Tambah</button>
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
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user as $u)
                            <tr idUser="{{$u->id}}">
                                <td class="col-1">{{$u->id}}</td>
                                <td>{{$u->username}}</td>
                                <td class="text-capitalize">{{$u->role}}</td>
                                <td class="col-2">
                                    <!-- Button trigger edit modal -->
                                    <button type="button" class="editBtn btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#edit-modal-{{$u->id}}" idUser="{{$u->id}}">
                                        Edit
                                    </button>
                                    <button class="hapusBtn btn btn-danger">Hapus</button>
                                </td>
                            </tr>
                            <!-- Edit User Modal -->
                            <div class="modal fade" id="edit-modal-{{$u->id}}" tabindex="-1"
                                 aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
                                        </div>
                                        <div class="modal-body">
                                            <form id="edit-user-form-{{$u->id}}">
                                                <div class="form-group">
                                                    <label>Username</label>
                                                    <input placeholder="Username" type="text" class="form-control mb-3"
                                                           name="username"
                                                           value="{{$u->username}}"
                                                           required/>
                                                    <label>Role</label>
                                                    <select name="role" class="form-select mb-3" required>
                                                        <option @if($u->role == 'operator') selected
                                                                @endif value="operator">Operator
                                                        </option>
                                                        <option @if($u->role == 'admin') selected @endif value="admin">
                                                            Admin
                                                        </option>
                                                    </select>
                                                    @csrf
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary edit-btn"
                                                    form="edit-user-form-{{$u->id}}">
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
        $('#tambah-user-form').on('submit', function (e) {
            e.preventDefault();
            let data = new FormData(e.target);
            axios.post('/dashboard/user/tambah', Object.fromEntries(data))
                .then(() => {
                    $('#tambah-user-modal').css('display', 'none')
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
            let idUser = $(this).attr('idUser');
            $(`#edit-user-form-${idUser}`).on('submit', function (e) {
                e.preventDefault();
                let data = new FormData(e.target);
                axios.post(`/dashboard/user/${idUser}/edit`, Object.fromEntries(data))
                    .then(() => {
                        $(`#edit-modal-${idUser}`).css('display', 'none')
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
            let idUser = $(this).closest('tr').attr('idUser');
            swal.fire({
                title: "Apakah anda ingin menghapus data ini?",
                showCancelButton: true,
                confirmButtonText: 'Setuju',
                cancelButtonText: `Batal`,
                confirmButtonColor: 'red'
            }).then((result) => {
                if (result.isConfirmed) {
                    //dilakukan proses hapus
                    axios.delete(`/dashboard/user/${idUser}/delete`).then(function (response) {
                        console.log(response);
                        if (response.data.success) {
                            swal.fire('Berhasil di hapus!', '', 'success').then(function () {
                                //Refresh Halaman
                                location.reload();
                            });
                        } else {
                            swal.fire('Gagal di hapus!', '', 'warning').then(function () {
                                //Refresh Halaman
                                location.reload();
                            });
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
