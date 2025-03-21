@extends('admin.admin_master')
@section('admin')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Begin Page Content -->
    <div class="page-content">
        <!--breadcrumb-->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <div class="m-0 font-weight-bold text-primary">
                <h5 class="m-0 font-weight-bold text-primary">
                    Salary of {{ date('F', strtotime('-1 month')) }}

                </h5>
            </div>
            <h6 class="m-0 font-weight-bold text-primary">
                <p class="m-0 font-weight-bold text-primary">Current Month : {{ date(' F, Y') }}</p>
            </h6>
        </div>
        <!--end breadcrumb-->
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Month</th>
                                <th>Salary</th>
                                <th>Advance</th>
                                <th>Bonus</th>
                                <th>OT Amount</th>
                                <th>Total Salary</th>
                                <th>Paid Salary</th>
                                <th>Due Salary</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Sl</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Month</th>
                                <th>Salary</th>
                                <th>Advance</th>
                                <th>Bonus</th>
                                <th>OT Amount</th>
                                <th>Total Salary</th>
                                <th>Paid Salary</th>
                                <th>Due Salary</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>

                            @foreach ($employees as $key => $employee)
                                @php
                                    $total_sum = '0';
                                    $advanced_amount = '0';
                                    $bonus_amount = '0';
                                    $overtime_amount = '0';
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <img src="{{ asset($employee->image) }}" class="rounded-circle" width="46"
                                            height="40" alt="" />
                                    </td>
                                    <td class="text-capitalize">
                                        {{ $employee->name }}
                                    </td>
                                    <td class="text-capitalize">
                                        <span class="badge bg-info">
                                            {{ date('F', strtotime('-1 month')) }}
                                        </span>
                                    </td>
                                    <td class="text-capitalize">
                                        {{ $employee->salary }}
                                    </td>

                                    <!--- advanced  -->
                                    <td class="text-capitalize">
                                        @php
                                            $advanced = App\Models\Advanced::where('employee_id', $employee->id)
                                                ->where('month', date('F', strtotime('-1 month')))
                                                ->where('year', date('Y'))
                                                ->first();
                                            if ($advanced === null) {
                                                $advanced_amount = 0;
                                            } else {
                                                $advanced_amount = $advanced->advance_amount;
                                            }
                                        @endphp
                                        @if ($advanced_amount == 0)
                                            No Advanced
                                        @else
                                            ({{ number_format($advanced_amount) }})
                                        @endif
                                    </td>


                                    <!--- bonus  -->
                                    <td class="text-capitalize">
                                        @php
                                            $allBonus = App\Models\Bonus::where('employee_id', $employee->id)
                                                ->where('month', date('F', strtotime('-1 month')))
                                                ->where('year', date('Y'))
                                                ->first();
                                            if ($allBonus === null) {
                                                $bonus_amount = 0;
                                            } else {
                                                $bonus_amount = $allBonus->bonus_amount;
                                            }
                                        @endphp
                                        @if ($bonus_amount === 0)
                                            No Bonus
                                        @else
                                            {{ number_format($bonus_amount) }}
                                        @endif
                                    </td>



                                    <!--- overtime  -->
                                    <td>
                                        @php
                                            $overtimes = App\Models\Overtime::where('employee_id', $employee->id)
                                                ->where('month', date('F', strtotime('-1 month')))
                                                ->where('year', date('Y'))
                                                ->get();

                                            if ($overtimes->isEmpty()) {
                                                $overtime_amount = 0;
                                            } else {
                                                foreach ($overtimes as $key => $item) {
                                                    $overtime_amount += $item->ot_amount;
                                                }
                                            }
                                        @endphp

                                        @if ($overtime_amount === 0)
                                            No ovetime
                                        @else
                                            {{ number_format($overtime_amount) }}
                                        @endif
                                    </td>


                                    @php
                                        $pay_salary = App\Models\PaySalaryDetail::where('employee_id', $employee->id)
                                            ->where('paid_month', date('F', strtotime('-1 month')))
                                            ->where('paid_year', date('Y'))
                                            ->get();
                                    @endphp


                                    @if ($pay_salary->isEmpty())
                                        @php
                                            $salary = $employee->salary + $bonus_amount + $overtime_amount - $advanced_amount;
                                        @endphp
                                        <td> {{ number_format($salary) }}
                                        </td>
                                        <td>No Paid</td>
                                        <td> {{ number_format($salary) }}</td>
                                    @else
                                        @php
                                            $salary = $employee->salary + $bonus_amount + $overtime_amount - $advanced_amount;
                                        @endphp
                                        <td> {{ number_format($salary) }}
                                        </td>
                                        <td>{{ $pay_salary->sum('paid_amount') }}</td>
                                        <td> {{ number_format($salary - $pay_salary->sum('paid_amount')) }}
                                        </td>
                                    @endif


                                    <td style="display:flex">
                                        <a title="Pay Salary" href="{{ route('pay.salary.now', $employee->id) }}"
                                            class="btn btn-info text-light">
                                            Pay Now </a>
                                        <a title="Payment Details" style="margin-left: 5px;"
                                            href="{{ route('employee.payment.details', $employee->id) }}"
                                            class="btn btn-dark">
                                            Payment Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <!-- End Page Content -->
@endsection
