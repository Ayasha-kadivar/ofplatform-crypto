@extends($activeTemplate . 'layouts.master')
@section('content')
    <script>
        "use strict"

        function createCountDown(elementId, sec) {
            var tms = sec;
            var x = setInterval(function() {
                var distance = tms * 1000;
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById(elementId).innerHTML = days + "d: " + hours + "h " + minutes + "m " + seconds + "s ";
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById(elementId).innerHTML = "COMPLETE";
                }
                tms--;
            }, 1000);
        }
    </script>

    <section class="pb-60 pt-60">

        <div class="container">

            <div class="row gy-4 mb-4">
                <div class="col-md-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div>
                                    <p class="mb-2 fw-bold">@lang('Total Invest')</p>
                                    <h4 class="text--base"><sup class="top-0 fw-light me-1">{{ $general->cur_sym }}</sup>{{ showAmount(auth()->user()->invests->sum('amount')) }}</h4>
                                </div>
                                <div>
                                    <p class="mb-2 fw-bold">@lang('Total Profit')</p>
                                    <h4 class="text--base"><sup class="top-0 fw-light me-1">{{ $general->cur_sym }}</sup>{{ showAmount(auth()->user()->transactions()->where('remark', 'interest')->sum('amount')) }}</h4>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap justify-content-between mt-3 mt-sm-4 gap-2">
                                <a href="{{ route('plan') }}" class="btn btn--sm btn--base">@lang('Invest Now') <i class="las la-arrow-right fs--12px ms-1"></i></a>
                                <a href="{{ route('user.withdraw') }}" class="btn btn--sm btn--base">@lang('Withdraw Now') <i class="las la-arrow-right fs--12px ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card h-100">
                        <div class="card-body">
                            @if ($investChart->count())
                                <div class="invest-statistics d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="flex-shrink-0">
                                        @foreach ($investChart as $chart)
                                            <p class="my-2"><i class="fas fa-plane planPoint me-2"></i>{{ showAmount(($chart->investAmount / $investChart->sum('investAmount')) * 100) }}% - {{ __($chart->plan->name) }}</p>
                                        @endforeach
                                    </div>
                                    <div class="invest-statistics__chart">
                                        <canvas height="150" id="chartjs-pie-chart" style="width: 150px;"></canvas>
                                    </div>
                                </div>
                            @else
                                <h3 class="text-center">@lang('No Investment Found Yet')</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-2">
                <div class="col-md-12">
                    <div class="text-end mb-3">
                        <a href="{{ route('user.invest.log') }}" class="btn btn--base">
                            @lang('View All')
                        </a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive--md">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('Plan')</th>
                                    <th>@lang('Return')</th>
                                    <th>@lang('Received')</th>
                                    <th>@lang('Next payment')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invests as $invest)
                                    <tr>
                                        <td>{{ __($invest->plan->name) }} <br> {{ showAmount($invest->amount) }} {{ __($general->cur_text) }} </td>
                                        <td>
                                            {{ showAmount($invest->interest) }} {{ __($general->cur_text) }} @lang('every') {{ $invest->time_name }}
                                            <br>
                                            @lang('for')
                                            @if ($invest->period == '-1')
                                                @lang('Lifetime')
                                            @else
                                                {{ $invest->period }}
                                                {{ $invest->time_name }}
                                            @endif
                                            @if ($invest->capital_status == '1')
                                                + @lang('Capital')
                                            @endif
                                        </td>
                                        <td> {{ $invest->return_rec_time }}x{{ showAmount($invest->interest) }} = {{ $invest->return_rec_time * $invest->interest }} {{ __($general->cur_text) }} </td>

                                        <td scope="row" class="font-weight-bold">
                                            @if ($invest->status == '1')
                                                <p id="counter{{ $invest->id }}" class="demo countdown timess2 "></p>

                                                @php
                                                    if ($invest->last_time) {
                                                        $start = $invest->last_time;
                                                    } else {
                                                        $start = $invest->created_at;
                                                    }
                                                @endphp
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: {{ diffDatePercent($start, $invest->next_time) }}%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge badge-success">@lang('Completed')</span>
                                            @endif
                                        </td>

                                    </tr>
                                    @if (\Carbon\Carbon::parse($invest->next_time) > now())
                                        <script>
                                            createCountDown('counter<?php echo $invest->id; ?>', {{ \Carbon\Carbon::parse($invest->next_time)->diffInSeconds() }});
                                        </script>
                                    @endif

                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@push('style')
    <style>
        .custom-progress {
            max-width: 40px !important;
            max-height: 40px;
            transform: rotate(-90deg);
        }

        .custom-progress .bg-circle {
            stroke: #00000011;
            fill: none;
            stroke-width: 4px;
            position: relative;
            z-index: -1;
        }

        .custom-progress .progress-circle {
            fill: none;
            stroke: hsl(var(--base));
            stroke-width: 4px;
            z-index: 11;
            position: absolute;
        }

        .expired-time-circle {
            position: relative;
            border: none !important;
            height: 38px;
            width: 38px;
            margin-right: 7px;
        }

        .expired-time-circle::before {
            position: absolute;
            content: '';
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 4px solid #dbdce1;
        }

        .expired-time-circle.danger-border .animation-circle {
            border-color: hsl(var(--base)) !important;
        }

        .animation-circle {
            position: absolute;
            top: 0;
            left: 0;
            border: 4px solid hsl(var(--base));
            height: 100%;
            width: 100%;
            border-radius: 150px;
            transform: rotateY(180deg);
            animation-name: clipCircle;
            animation-iteration-count: 1;
            animation-timing-function: cubic-bezier(0, 0, 1, 1);
            z-index: 1;
        }

        .account-wrapper .left .top {
            margin-top: 0;
        }

        .account-wrapper .left,
        .account-wrapper .right {
            width: 100%;
        }

        .account-wrapper .right {
            padding-left: 0;
            margin-top: 35px;
        }

        @keyframes clipCircle {
            0% {
                clip-path: polygon(50% 50%, 50% 0%, 50% 0%, 50% 0%, 50% 0%, 50% 0%, 50% 0%, 50% 0%, 50% 0%, 50% 0%);
                /* center, top-center*/
            }

            12.5% {
                clip-path: polygon(50% 50%, 50% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%);
                /* center, top-center, top-left*/
            }

            25% {
                clip-path: polygon(50% 50%, 50% 0%, 0% 0%, 0% 50%, 0% 50%, 0% 50%, 0% 50%, 0% 50%, 0% 50%, 0% 50%);
                /* center, top-center, top-left, left-center*/
            }

            37.5% {
                clip-path: polygon(50% 50%, 50% 0%, 0% 0%, 0% 50%, 0% 100%, 0% 100%, 0% 100%, 0% 100%, 0% 100%, 0% 100%);
                /* center, top-center, top-left, left-center, bottom-left*/
            }

            50% {
                clip-path: polygon(50% 50%, 50% 0%, 0% 0%, 0% 50%, 0% 100%, 50% 100%, 50% 100%, 50% 100%, 50% 100%, 50% 100%);
                /* center, top-center, top-left, left-center, bottom-left, bottom-center*/
            }

            62.5% {
                clip-path: polygon(50% 50%, 50% 0%, 0% 0%, 0% 50%, 0% 100%, 50% 100%, 100% 100%, 100% 100%, 100% 100%, 100% 100%);
                /* center, top-center, top-left, left-center, bottom-left, bottom-center, bottom-right*/
            }

            75% {
                clip-path: polygon(50% 50%, 50% 0%, 0% 0%, 0% 50%, 0% 100%, 50% 100%, 100% 100%, 100% 50%, 100% 50%, 100% 50%);
                /* center, top-center, top-left, left-center, bottom-left, bottom-center, bottom-right, right-center*/
            }

            87.5% {
                clip-path: polygon(50% 50%, 50% 0%, 0% 0%, 0% 50%, 0% 100%, 50% 100%, 100% 100%, 100% 50%, 100% 0%, 100% 0%);
                /* center, top-center, top-left, left-center, bottom-left, bottom-center, bottom-right, right-center top-right*/
            }

            100% {
                clip-path: polygon(50% 50%, 50% 0%, 0% 0%, 0% 50%, 0% 100%, 50% 100%, 100% 100%, 100% 50%, 100% 0%, 50% 0%);
                /* center, top-center, top-left, left-center, bottom-left, bottom-center, bottom-right, right-center top-right, top-center*/
            }
        }

        .capital-back {
            font-size: 10px;
        }
    </style>
@endpush


@push('script')
    <script src="{{ asset('assets/global/js/chart.min.js') }}"></script>


    <script>
        /* -- Chartjs - Pie Chart -- */
        var pieChartID = document.getElementById("chartjs-pie-chart").getContext('2d');
        var pieChart = new Chart(pieChartID, {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        @foreach ($investChart as $chart)
                            {{ $chart->investAmount }},
                        @endforeach
                    ],
                    borderColor: 'transparent',
                    backgroundColor: planColors(),
                    label: 'Dataset 1'
                }],
                labels: [
                    @foreach ($investChart as $chart)
                        '{{ $chart->plan->name }}',
                    @endforeach
                ]
            },
            options: {
                responsive: true,
                legend: {
                    display: false
                }
            }
        });

        var planPoints = $('.planPoint');
        planPoints.each(function(key, planPoint) {
            var planPoint = $(planPoint)
            planPoint.css('color', planColors()[key])
        })

        function planColors() {
            return [
                '#ff7675',
                '#6c5ce7',
                '#ffa62b',
                '#ffeaa7',
                '#D980FA',
                '#fccbcb',
                '#45aaf2',
                '#05dfd7',
                '#FF00F6',
                '#1e90ff',
                '#2ed573',
                '#eccc68',
                '#ff5200',
                '#cd84f1',
                '#7efff5',
                '#7158e2',
                '#fff200',
                '#ff9ff3',
                '#08ffc8',
                '#3742fa',
                '#1089ff',
                '#70FF61',
                '#bf9fee',
                '#574b90'
            ]
        }
    </script>

    <script>
        let animationCircle = $('.animation-circle');
        animationCircle.css('animation-duration', function() {
            let duration = ($(this).data('duration'));
            return duration;
        });
    </script>
@endpush
