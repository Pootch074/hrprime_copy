@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
@vite('resources/assets/js/dashboards-analytics.js')
@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

@section('content')

<!-- ================= DASHBOARD TITLE CARD ================= -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card text-center p-4">
      <h4 class="fw-bold mb-1">Human Resource and Planning and Performance Management Section (HRPPMS)</h4>
      <p class="text-muted mb-0">
        Overview of workforce statistics, headcount distribution, and employment status
      </p>
    </div>
  </div>
</div>

<!-- ================= FILTER ================= -->
<div class="row mb-4">
  <div class="col-12 d-flex align-items-center gap-2 justify-content-end">
    <select id="filter_type" class="form-select w-auto">
      <option value="month">Month</option>
      <option value="quarter">Quarter</option>
      <option value="year" selected>Year</option>
    </select>

    <input type="month" id="monthInput" class="form-control d-none">
    <select id="quarterInput" class="form-select d-none w-auto">
      <option value="1">Q1</option>
      <option value="2">Q2</option>
      <option value="3">Q3</option>
      <option value="4">Q4</option>
    </select>
    <input type="number" id="yearInput" class="form-control w-auto" value="{{ date('Y') }}" min="2000" max="2100">

    <button id="applyFilter" class="btn btn-primary">Apply</button>
  </div>
</div>

<!-- ================= KPI CARDS ================= -->
<div class="row g-4 mb-4">

  <div class="col-xl col-md-4 col-sm-6">
    <div class="card text-center h-100">
      <div class="card-header"><h6>Overall Employees</h6></div>
      <div class="card-body" id="overallEmployeesCard"><h3>{{ $overallEmployees }}</h3></div>
    </div>
  </div>

  <div class="col-xl col-md-4 col-sm-6">
    <div class="card text-center h-100">
      <div class="card-header"><h6>Vacancy</h6></div>
      <div class="card-body"><h3>—</h3></div>
    </div>
  </div>

  <div class="col-xl col-md-4 col-sm-6">
    <div class="card text-center h-100">
      <div class="card-header"><h6>Attrition Rate</h6></div>
      <div class="card-body"><h3>—</h3></div>
    </div>
  </div>

  <div class="col-xl col-md-4 col-sm-6">
    <div class="card text-center h-100">
      <div class="card-header"><h6>Active Employees</h6></div>
      <div class="card-body" id="activeEmployeesCard"><h3>{{ $activeEmployees }}</h3></div>
    </div>
  </div>

  <div class="col-xl col-md-4 col-sm-6">
    <div class="card text-center h-100">
      <div class="card-header"><h6>Average Age</h6></div>
      <div class="card-body" id="averageAgeCard"><h3>{{ $averageAge }}</h3></div>
    </div>
  </div>

</div>

<!-- ================= ROW 1 ================= -->
<div class="row g-4 mb-4">

  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><h6>Headcount by Gender</h6></div>
      <div class="card-body"><div id="genderChart"></div></div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><h6>Headcount by Age Group</h6></div>
      <div class="card-body"><div id="ageChart"></div></div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><h6>Headcount by Division</h6></div>
      <div class="card-body"><div id="divisionChart"></div></div>
    </div>
  </div>

</div>

<!-- ================= ROW 2 ================= -->
<div class="row g-4 mb-4">

  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><h6>Headcount by Office Location</h6></div>
      <div class="card-body"><div id="locationChart"></div></div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><h6>Percentage by Employment Status</h6></div>
      <div class="card-body"><div id="contractChart"></div></div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><h6>Headcount by Employment Status</h6></div>
      <div class="card-body"><div id="employmentStatusChart"></div></div>
    </div>
  </div>

</div>

<!-- ================= INLINE JS ================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {

  // ===== FILTER TOGGLE =====
  const filterType = document.getElementById('filter_type');
  filterType.addEventListener('change', function () {
    document.getElementById('monthInput').classList.add('d-none');
    document.getElementById('quarterInput').classList.add('d-none');
    document.getElementById('yearInput').classList.add('d-none');
    if(this.value === 'month') document.getElementById('monthInput').classList.remove('d-none');
    if(this.value === 'quarter') document.getElementById('quarterInput').classList.remove('d-none');
    if(this.value === 'year') document.getElementById('yearInput').classList.remove('d-none');
  });

  // ===== INIT CHARTS =====
  let genderChart = new ApexCharts(document.querySelector("#genderChart"), {
    chart: { type: 'pie', height: 280 },
    series: [{{ $male }}, {{ $female }}],
    labels: ['Male','Female'],
    colors: ['#1d4bb2','#ac1109']
  }); genderChart.render();

  let divisionChart = new ApexCharts(document.querySelector("#divisionChart"), {
    chart: { type: 'bar', height: 280 },
    series: [{ name: "Employees", data: @json(array_values($divisions)) }],
    xaxis: { categories: @json(array_keys($divisions)) },
    colors: ['#1d4bb2']
  }); divisionChart.render();

  let locationChart = new ApexCharts(document.querySelector("#locationChart"), {
    chart: { type: 'bar', height: 280 },
    series: [{ name: "Employees", data: @json(array_values($office_locations)) }],
    xaxis: { categories: @json(array_keys($office_locations)) },
    colors: ['#1d4bb2']
  }); locationChart.render();

  let contractChart = new ApexCharts(document.querySelector("#contractChart"), {
    chart: { type: 'donut', height: 350 },
    series: @json(array_values($employment_status)),
    labels: @json(array_keys($employment_status)),
    colors: ['#1d4bb2','#b21810ff','#ffcc00','#00897b','#6a1b9a'],
    legend: { position: 'bottom' }
  }); contractChart.render();

  let ageChart = new ApexCharts(document.querySelector("#ageChart"), {
    chart: { type: 'bar', height: 280, stacked: true },
    series: [
      { name: 'Male', data: @json($maleAgeCounts) },
      { name: 'Female', data: @json($femaleAgeCounts) }
    ],
    xaxis: { categories: @json(array_keys($ageGroups)) },
    colors: ['#1d4bb2','#ac1109'],
    plotOptions: { bar: { horizontal: false } },
    legend: { position: 'top' },
    dataLabels: { enabled: true }
  }); ageChart.render();

  let employmentStatusChart = new ApexCharts(document.querySelector("#employmentStatusChart"), {
    chart: { type: 'bar', height: 350, stacked: true },
    plotOptions: { bar: { horizontal: true } },
    series: [
      { name: 'Male', data: @json($malePerStatus) },
      { name: 'Female', data: @json($femalePerStatus) }
    ],
    xaxis: { categories: @json($statuses) },
    colors: ['#1d4bb2','#ac1109'],
    legend: { position: 'top' },
    dataLabels: { enabled: true }
  }); employmentStatusChart.render();

  // ===== APPLY FILTER AJAX =====
  document.getElementById('applyFilter').addEventListener('click', function() {
    let type = filterType.value;
    let year = document.getElementById('yearInput').value;
    let month = document.getElementById('monthInput').value;
    let quarter = document.getElementById('quarterInput').value;

    fetch("{{ route('dashboard-analytics') }}?filter_type="+type+"&year="+year+"&month="+month+"&quarter="+quarter, {
      headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => res.json())
    .then(data => {
      // Update KPI cards
      document.querySelector('#overallEmployeesCard h3').innerText = data.overallEmployees;
      document.querySelector('#activeEmployeesCard h3').innerText = data.activeEmployees;
      document.querySelector('#averageAgeCard h3').innerText = data.averageAge;

      // Update charts
      genderChart.updateSeries([data.male,data.female]);
      divisionChart.updateSeries([{ data: Object.values(data.divisions) }]);
      divisionChart.updateOptions({ xaxis: { categories: Object.keys(data.divisions) }});
      locationChart.updateSeries([{ data: Object.values(data.office_locations) }]);
      locationChart.updateOptions({ xaxis: { categories: Object.keys(data.office_locations) }});
      contractChart.updateSeries(Object.values(data.employment_status));
      contractChart.updateOptions({ labels: Object.keys(data.employment_status) });
      ageChart.updateSeries([
        { data: data.maleAgeCounts },
        { data: data.femaleAgeCounts }
      ]);
      ageChart.updateOptions({ xaxis: { categories: data.ageGroups }});
      employmentStatusChart.updateSeries([
        { data: data.malePerStatus },
        { data: data.femalePerStatus }
      ]);
      employmentStatusChart.updateOptions({ xaxis: { categories: data.statuses }});
    });
  });

});
</script>

@endsection
