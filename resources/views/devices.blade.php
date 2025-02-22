@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard Monitoring Gas</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Pendaftaran Perangkat</h3>
              </div>
              <div class="card-body">
                <form action="{{ route('devices.store') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <label for="name" class="form-label">Nama Perangkat</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Pilih Sensor</label>
                    <div>
                      <input type="checkbox" name="sensors[]" value="mq4"> MQ-4
                      <input type="checkbox" name="sensors[]" value="mq6"> MQ-6
                      <input type="checkbox" name="sensors[]" value="mq8"> MQ-8
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary">Generate Token</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Daftar Perangkat Terdaftar</h3>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Nama Perangkat</th>
                      <th>Sensor</th>
                      <th>Token</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($devices as $device)
                    <tr>
                        <td>{{ $device->name }}</td>
                        <td>{{ implode(' | ', (array) $device->sensors) }}</td> <!-- Pastikan sensors dalam bentuk array -->
                        <td>{{ $device->token }}</td>
                    </tr>
                    @endforeach

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
@endsection

@section('js')
<script src="{{ asset('reglogin/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('monitoring/monitoring.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
