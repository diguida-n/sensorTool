@extends('backpack::layout')
@section('after_styles')
<!-- Resources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/pie.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>






<style>
    .chartSmoothedLine {
        width : 100%;
        height  : 350px;
    }
    .chartdivPie {
        width: 100%;
        height: 500px;
        font-size: 11px;
    }
    .amcharts-pie-slice {
        transform: scale(1);
        transform-origin: 50% 50%;
        transition-duration: 0.3s;
        transition: all .3s ease-out;
        -webkit-transition: all .3s ease-out;
        -moz-transition: all .3s ease-out;
        -o-transition: all .3s ease-out;
        cursor: pointer;
        box-shadow: 0 0 30px 0 #000;
    }

.amcharts-pie-slice:hover {
  transform: scale(1.1);
  filter: url(#shadow);
}
.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover,
.nav-tabs>li>a, .nav-tabs>li>a:focus, .nav-tabs>li>a:hover{
    border-color: #3c8dbc;
}
</style>

@endsection
@section('header')
    <section class="content-header" style="padding-top: 10px;">
      <h1>{{auth()->user()->enterprise?auth()->user()->enterprise->businessName:''}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section>
@endsection


@section('content')

    <div class="row">
        <ul class="nav nav-tabs">
            @if(auth()->user()->enterprise && !auth()->user()->isGuest())
                @foreach(auth()->user()->enterprise->sites as $index=>$site)
                  <li class="{{$index==0?'active':''}}">
                    <a data-toggle="tab" href="#site{{$site->id}}">{{$site->name}}</a>
                  </li>
                @endforeach
                <div class="tab-content">
                    @foreach(auth()->user()->enterprise->sites as $index=>$site)
                        <div id="site{{$site->id}}" class="tab-pane fade {{$index==0?'in active':''}}">
                            @foreach($site->sensors as $sensor)
                                <div class="col-md-12">
                                    <h4>{{$sensor->getSensorName()}}-{{$sensor->getSensorType()->name}}-({{$sensor->getSensorBrand()->name}})</h4>
                                    <div id="chartdiv{{$sensor->id}}">
                                        
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @else
                @if(auth()->user()->site && auth()->user()->hasRole('Guest'))
                      <li class="active">
                        <a data-toggle="tab" href="#site{{auth()->user()->site->id}}">{{auth()->user()->site->name}}</a>
                      </li>
                    <div class="tab-content">
                        <div id="site{{auth()->user()->site->id}}" class="tab-pane fade in active">
                            @foreach(auth()->user()->site->sensors as $sensor)
                                <div class="col-md-12">
                                    <h4>{{$sensor->getSensorName()}}-{{$sensor->getSensorType()->name}}-({{$sensor->getSensorBrand()->name}})</h4>
                                    <div id="chartdiv{{$sensor->id}}">
                                        
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </ul>
    </div>
@endsection

@section('after_scripts')
    <script>
        $(function() {
            var id = {{auth()->user()->enterprise && !auth()->user()->isGuest()?auth()->user()->enterprise->id:0}};
            $.ajax({
                method:"POST",
                url:"/employee/getSensorsData/"+id,
                async:true,
                success:function(data){
                    for (var siteKey in data) {
                        if (data.hasOwnProperty(siteKey)) {
                            var sensors = data[siteKey];
                            for (var sensorKey in sensors) {
                                if (sensors.hasOwnProperty(sensorKey)) {
                                    var detections = sensors[sensorKey];
                                    var graphicType = detections.graphicType;
                                    var value = detections.data;
                                    var idChart = "chartdiv"+sensorKey;
                                    switch(graphicType){
                                        case 1:
                                            $('#'+idChart).addClass('chartSmoothedLine');
                                            var chart = AmCharts.makeChart(idChart, {
                                                    "type": "serial",
                                                    "theme": "light",
                                                    "marginTop":0,
                                                    "marginRight": 80,
                                                    "dataProvider": value,
                                                    "valueAxes": [{
                                                        "axisAlpha": 0,
                                                        "position": "left"
                                                    }],
                                                    "graphs": [{
                                                        "id":"g1",
                                                        "balloonText": "[[category]]<br><b><span style='font-size:14px;'>[[value]]</span></b>",
                                                        "bullet": "round",
                                                        "bulletSize": 8,
                                                        "lineColor": "#d1655d",
                                                        "lineThickness": 2,
                                                        "negativeLineColor": "#637bb6",
                                                        "type": "smoothedLine",
                                                        "valueField": "value"
                                                    }],
                                                    "chartScrollbar": {
                                                        "graph":"g1",
                                                        "gridAlpha":0,
                                                        "color":"#888888",
                                                        "scrollbarHeight":55,
                                                        "backgroundAlpha":0,
                                                        "selectedBackgroundAlpha":0.1,
                                                        "selectedBackgroundColor":"#888888",
                                                        "graphFillAlpha":0,
                                                        "autoGridCount":false,
                                                        "selectedGraphFillAlpha":0,
                                                        "graphLineAlpha":0.4,
                                                        "graphLineColor":"#c2c2c2",
                                                        "selectedGraphLineColor":"#888888",
                                                        "selectedGraphLineAlpha":1

                                                    },
                                                    "chartCursor": {
                                                        "categoryBalloonDateFormat": "DD/MM/YYYY HH:NN:SS",
                                                        "cursorAlpha": 0,
                                                        "valueLineEnabled":true,
                                                        "valueLineBalloonEnabled":true,
                                                        "valueLineAlpha":0.5,
                                                        "fullWidth":true
                                                    },
                                                    "marginBottom": 20,
                                                    "dataDateFormat": "DD/MM/YYYY HH:NN:SS",
                                                    "categoryField": "date",
                                                    "categoryAxis": {
                                                        "minPeriod": "DD/MM/YYYY HH:NN:SS",
                                                        "parseDates": false,
                                                        "minorGridAlpha": 0.1,
                                                        "minorGridEnabled": true,
                                                        "autoWrap":true
                                                    },
                                                    "export": {
                                                        "enabled": true
                                                    }
                                                });
                                            break;
                                        case 2:
                                            $('#'+idChart).addClass('chartSmoothedLine');
                                            var chart = AmCharts.makeChart(idChart, {
                                                "type": "serial",
                                                "theme": "light",
                                                "marginRight": 80,
                                                "dataProvider": value,
                                                "valueAxes": [{
                                                    "axisAlpha": 0,
                                                    "position": "left"
                                                }],
                                                "graphs": [{
                                                    "id": "g1",
                                                    "fillAlphas": 0.4,
                                                    "valueField": "value",
                                                     "balloonText": "<div style='margin:5px; font-size:19px;'>Visits:<b>[[value]]</b></div>"
                                                }],
                                                "chartScrollbar": {
                                                    "graph": "g1",
                                                    "scrollbarHeight": 80,
                                                    "backgroundAlpha": 0,
                                                    "selectedBackgroundAlpha": 0.1,
                                                    "selectedBackgroundColor": "#888888",
                                                    "graphFillAlpha": 0,
                                                    "graphLineAlpha": 0.5,
                                                    "selectedGraphFillAlpha": 0,
                                                    "selectedGraphLineAlpha": 1,
                                                    "autoGridCount": false,
                                                    "color": "#AAAAAA"
                                                },
                                                "chartCursor": {
                                                    "categoryBalloonDateFormat": "DD/MM/YYYY HH:NN:SS",
                                                    "cursorPosition": "mouse"
                                                },
                                                "marginBottom": 20,
                                                "categoryField": "date",
                                                "categoryAxis": {
                                                    "minPeriod": "DD/MM/YYYY HH:NN:SS",
                                                    "parseDates": false,
                                                    "autoWrap":true
                                                },
                                                "export": {
                                                    "enabled": true,
                                                     "dateFormat": "DD/MM/YYYY HH:NN:SS"
                                                }
                                            });
                                            break;
                                        case 3:
                                        case 4:
                                            $('#'+idChart).addClass('chartSmoothedLine');
                                            var chart = AmCharts.makeChart(idChart, {
                                                "type": "serial",
                                                "theme": "light",
                                                "dataProvider": value,
                                                "valueAxes": [{
                                                    "maximum": 300,
                                                    "minimum": 0,
                                                    "axisAlpha": 0,
                                                    "dashLength": 4,
                                                    "position": "left"
                                                }],
                                                "startDuration": 1,
                                                "graphs": [{
                                                    "balloonText": "<span style='font-size:13px;'>[[category]]: <b>[[value]]</b></span>",
                                                    "bulletOffset": 10,
                                                    "bulletSize": 30,
                                                    "colorField": "color",
                                                    "cornerRadiusTop": 8,
                                                    "customBulletField": "bullet",
                                                    "fillAlphas": 0.8,
                                                    "lineAlpha": 0,
                                                    "type": "column",
                                                    "valueField": "value"
                                                }],
                                                "marginTop": 0,
                                                "marginRight": 0,
                                                "marginLeft": 0,
                                                "marginBottom": 20,
                                                "autoMargins": false,
                                                "categoryField": "date",
                                                "categoryAxis": {
                                                    "axisAlpha": 0,
                                                    "gridAlpha": 0,
                                                    "inside": true,
                                                    "tickLength": 0,
                                                    "autoWrap":true
                                                },
                                                "export": {
                                                    "enabled": true
                                                 }
                                            });
                                        break;
                                        default:
                                            console.log(graphicType,"->",value);
                                            break;
                                    }
                                }
                            }
                        }
                    }
                },
                error:function(e) {
                    console.log(e);
                }

            })
        })

    </script>
@endsection
