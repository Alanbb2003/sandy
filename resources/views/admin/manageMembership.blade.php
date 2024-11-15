@extends('layouts.appAdmin')

@section('content')
<div class="container">
    <h4>Manage Membership</h4>
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">Add New Member</button>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="membershipTable">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Tanggal Daftar</th>
                        <th>Total Points</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                    <tr>
                        <td>{{ $member->user->firstName }} {{ $member->user->lastName }}</td>
                        <td>{{$member->tanggalDaftar}}</td>
                        <td>{{ number_format($member->total_points, 0, ',', '.') }}</td>
                        <td>
                            <button class="btn btn-info view-points-btn" data-member="{{ json_encode($member) }}" data-bs-toggle="modal" data-bs-target="#pointHistoryModal">View Point History</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">Add New Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.membershipAdd') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="userSelect" class="form-label">Select User</label>
                        <select id="userSelect" class="form-select" name="userSelect" required>
                            <option value="">Choose a user...</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->firstName }} {{ $user->lastName }},{{$user->email}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggalLahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggalLahir" name="tanggalLahir" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Member</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Point History Modal -->
<div class="modal fade" id="pointHistoryModal" tabindex="-1" aria-labelledby="pointHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pointHistoryModalLabel">Point History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="pointHistoryTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Points Earned</th>
                            <th>Transaction Type</th>
                        </tr>
                    </thead>
                    <tbody id="pointHistoryBody">
                        <!-- Point history will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $('#membershipTable').DataTable();

    // Show point history in modal
    $('.view-points-btn').on('click', function() {
        const member = JSON.parse($(this).attr('data-member'));
        const pointHistoryBody = $('#pointHistoryBody');
        pointHistoryBody.empty();

        member.points.forEach(point => {
            pointHistoryBody.append(`
                <tr>
                    <td>${new Date(point.tanggalPemberianPoin).toLocaleDateString('id-ID')}</td>
                    <td>${point.jumlahPoin}</td>
                    <td>${point.tipeTransaksi}</td>
                </tr>
            `);
        });
    });
});
</script>
@endsection