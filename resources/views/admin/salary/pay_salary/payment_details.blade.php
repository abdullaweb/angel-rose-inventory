@extends('admin.admin_master')
@section('admin')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Begin Page Content -->
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Salary Sheet</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-light"><i
                                    class="bx bx-home-alt text-light"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Salary Sheet</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>



        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card">
                    @php
                        $date = Carbon\Carbon::now()->format('Y');
                    @endphp
                    <h4 class="text-center my-3">Salary Sheet - {{ $date }} </h4>
                    <div class="card-body">
                        <div class="employee-info mb-5">
                            <h5 class="text-muted">Employee Name: {{ $employee->name }}</h5>
                            <h6 class="text-muted">Designation: {{ $employee->designation }}</h6>
                            <p class="text-muted mb-0">Joining Date:
                                {{ date('d-M, Y', strtotime($employee->joining_date)) }}
                            </p>
                            <p class="text-muted mb-0"><strong>Basic Salary: {{ $employee->salary }}</strong></p>
                            <p class="text-muted mb-0">Phone: {{ $employee->phone }}</p>
                            @php
                                $advanced_amount = App\Models\Advanced::where('employee_id', $employee->id)->first();
                            @endphp
                        </div>

                        @foreach ($payment_salary as $item)
                            <table id="datatable" class="table table-bordered dt-responsive nowrap mt-5"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <thead>
                                        <tr class="text-center">
                                            <th colspan="12">
                                                <h4>Month Of
                                                    <strong style="font-weight: 400;">{{ $item->paid_month }},
                                                        {{ $item->paid_year }}</strong>
                                                </h4>
                                            </th>
                                        </tr>
                                        <tr class="text-center">
                                            <th>Sl</th>
                                            <th>Month</th>
                                            <th>Year</th>
                                            <th>Salary</th>
                                            <th>OT Hour</th>
                                            <th>OT Amount</th>
                                            <th>Bonus</th>
                                            <th>Payment Date</th>
                                            <th>Note</th>
                                            <th>Paid Amount</th>
                                            <th>Due Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    @php
                                        $paymentDetails = App\Models\PaySalaryDetail::where('employee_id', $employee->id)
                                            ->where('paid_month', $item->paid_month)
                                            ->where('paid_year', date('Y'))
                                            ->get();
                                    @endphp
                                    <tr class="text-center">
                                        <td>1</td>
                                        <td style="vertical-align: middle;" rowspan="{{ count($paymentDetails) + 1 }}">
                                            {{ $item->paid_month }}</td>
                                        <td style="vertical-align: middle;" rowspan="{{ count($paymentDetails) + 1 }}">
                                            {{ date('Y') }}</td>
                                        <td style="vertical-align: middle;" rowspan="{{ count($paymentDetails) + 1 }}">
                                            {{ $employee->salary }}</td>
                                        @php
                                            $overtimes = App\Models\Overtime::where('employee_id', $employee->id)
                                                ->where('month', $item->paid_month)
                                                ->where('year', date('Y'))
                                                ->first();
                                            $bonus = App\Models\Bonus::where('employee_id', $employee->id)
                                                ->where('month', $item->paid_month)
                                                ->where('year', date('Y'))
                                                ->first();
                                            $total = '0';
                                            $paid_total = '0';
                                            $total += $employee->salary;
                                        @endphp

                                        <!-- overtime -->
                                        @if ($overtimes == null)
                                            <td>0</td>
                                            <td>0</td>
                                            @php
                                                $total += 0;
                                            @endphp
                                        @else
                                            <td>{{ $overtimes->ot_hour }}</td>
                                            <td>{{ number_format($overtimes->ot_amount) }}/-</td>
                                            @php
                                                $total += $overtimes->ot_amount;
                                            @endphp
                                        @endif

                                        <!-- bonus -->
                                        @if ($bonus == null)
                                            <td>0</td>
                                            @php
                                                $total += 0;
                                            @endphp
                                        @else
                                            <td>{{ number_format($bonus->bonus_amount) }}/-</td>
                                            @php
                                                $total += $bonus->bonus_amount;
                                            @endphp
                                        @endif

                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>{{ number_format($total) }}/-</td>
                                    </tr>

                                    @foreach ($paymentDetails as $key => $details)
                                        <tr class="text-center">
                                            <td>{{ $key + 2 }}</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>{{ $details->paid_date }}</td>
                                            <td>{{ $details->note }}</td>
                                            <td>{{ number_format($details->paid_amount) }}/-</td>

                                            <td>
                                                @if ($details->paid_type == 'Advanced')
                                                    {{-- {{ $total }} --}}
                                                    {{ $total - $paid_total }}
                                                @else
                                                    @php
                                                        $paid_total += $details->paid_amount;
                                                    @endphp
                                                    {{ number_format($total - $paid_total) }}/-
                                            </td>
                                    @endif

                                    </tr>
                        @endforeach
                        </tbody>

                        </table>
                        @endforeach
                        {{-- <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <thead>
                                    <tr class="text-center">
                                        <th colspan="12">
                                            <h2></h2>
                                        </th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>Sl</th>
                                        <th rowspan="3">Month</th>
                                        <th>Year</th>
                                        <th>salary</th>
                                        <th>OT Hour</th>
                                        <th>OT Amount</th>
                                        <th>Bonus</th>
                                        <th>Payment Type</th>
                                        <th>Payment Date</th>
                                        <th>Voucher No</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                    </tr>
                                </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td>1</td>
                                    <td>August</td>
                                    <td>2023</td>
                                    <td>{{ $employee->salary }}</td>
                                    @php
                                        $overtimes = App\Models\Overtime::where('employee_id', $employee->id)
                                            ->where('month', date('F', strtotime('-1 month')))
                                            ->where('year', date('Y'))
                                            ->first();
                                        $bonus = App\Models\Bonus::where('employee_id', $employee->id)
                                            ->where('month', date('F', strtotime('-1 month')))
                                            ->where('year', date('Y'))
                                            ->first();
                                        $total = '0';
                                        $paid_total = '0';
                                        $total += $employee->salary;
                                    @endphp

                                    <!-- overtime -->
                                    @if ($overtimes == null)
                                        <td>0</td>
                                        <td>0</td>
                                        @php
                                            $total += 0;
                                        @endphp
                                    @else
                                        <td>{{ $overtimes->ot_hour }}</td>
                                        <td>{{ $overtimes->ot_amount }}</td>
                                        @php
                                            $total += $overtimes->ot_amount;
                                        @endphp
                                    @endif

                                    <!-- bonus -->
                                    @if ($bonus == null)
                                        <td>0</td>
                                        @php
                                            $total += 0;
                                        @endphp
                                    @else
                                        <td>{{ $bonus->bonus_amount }}</td>
                                        @php
                                            $total += $bonus->bonus_amount;
                                        @endphp
                                    @endif
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>{{ $total }}</td>
                                </tr>

                                @foreach ($payment_details as $details)
                                    <tr class="text-center">
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>Salary</td>
                                        <td>{{ $details->paid_date }}</td>
                                        <td>{{ $details->voucher_no }}</td>
                                        <td>{{ $details->paid_amount }}</td>
                                        @php
                                            $paid_total += $details->paid_amount;
                                        @endphp
                                        <td>{{ $total - $paid_total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table> --}}
                    </div>
                </div>
            </div>


        </div>

    </div>
@endsection
