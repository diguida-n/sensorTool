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
  #chartdiv {
    width : 100%;
    height  : 300px;
  }
  #chartdivPie {
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
      <h1>{{auth()->user()->enterprise->businessName}}
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
        @foreach(auth()->user()->enterprise->sites as $index=>$site)
          <li class="{{$index==0?'active':''}}">
            <a data-toggle="tab" href="#site{{$site->id}}">{{$site->name}}</a>
          </li>
        @endforeach
        <div class="tab-content">
            @foreach(auth()->user()->enterprise->sites as $index=>$site)
              <div id="site{{$site->id}}" class="tab-pane fade {{$index==0?'in active':''}}">
              @if($index==0)
                <div class="col-md-6">
                    <div id="chartdiv"></div>
                </div>
                <div class="col-md-6">
                    <div id="chartdivPie"></div>
                </div>
                @endif
              </div>
            @endforeach
        </div>
    </div>
@endsection

@section('after_scripts')
  <script>
    var chart = AmCharts.makeChart("chartdiv", {
        "type": "serial",
        "theme": "light",
        "marginTop":0,
        "marginRight": 80,
        "dataProvider": [{
            "year": "1950",
            "value": -0.307
        }, {
            "year": "1951",
            "value": -0.168
        }, {
            "year": "1952",
            "value": -0.073
        }, {
            "year": "1953",
            "value": -0.027
        }, {
            "year": "1954",
            "value": -0.251
        }, {
            "year": "1955",
            "value": -0.281
        }, {
            "year": "1956",
            "value": -0.348
        }, {
            "year": "1957",
            "value": -0.074
        }, {
            "year": "1958",
            "value": -0.011
        }, {
            "year": "1959",
            "value": -0.074
        }, {
            "year": "1960",
            "value": -0.124
        }, {
            "year": "1961",
            "value": -0.024
        }, {
            "year": "1962",
            "value": -0.022
        }, {
            "year": "1963",
            "value": 0
        }, {
            "year": "1964",
            "value": -0.296
        }, {
            "year": "1965",
            "value": -0.217
        }, {
            "year": "1966",
            "value": -0.147
        }, {
            "year": "1967",
            "value": -0.15
        }, {
            "year": "1968",
            "value": -0.16
        }, {
            "year": "1969",
            "value": -0.011
        }, {
            "year": "1970",
            "value": -0.068
        }, {
            "year": "1971",
            "value": -0.19
        }, {
            "year": "1972",
            "value": -0.056
        }, {
            "year": "1973",
            "value": 0.077
        }, {
            "year": "1974",
            "value": -0.213
        }, {
            "year": "1975",
            "value": -0.17
        }, {
            "year": "1976",
            "value": -0.254
        }, {
            "year": "1977",
            "value": 0.019
        }, {
            "year": "1978",
            "value": -0.063
        }, {
            "year": "1979",
            "value": 0.05
        }, {
            "year": "1980",
            "value": 0.077
        }, {
            "year": "1981",
            "value": 0.12
        }, {
            "year": "1982",
            "value": 0.011
        }, {
            "year": "1983",
            "value": 0.177
        }, {
            "year": "1984",
            "value": -0.021
        }, {
            "year": "1985",
            "value": -0.037
        }, {
            "year": "1986",
            "value": 0.03
        }, {
            "year": "1987",
            "value": 0.179
        }, {
            "year": "1988",
            "value": 0.18
        }, {
            "year": "1989",
            "value": 0.104
        }, {
            "year": "1990",
            "value": 0.255
        }, {
            "year": "1991",
            "value": 0.21
        }, {
            "year": "1992",
            "value": 0.065
        }, {
            "year": "1993",
            "value": 0.11
        }, {
            "year": "1994",
            "value": 0.172
        }, {
            "year": "1995",
            "value": 0.269
        }, {
            "year": "1996",
            "value": 0.141
        }, {
            "year": "1997",
            "value": 0.353
        }, {
            "year": "1998",
            "value": 0.548
        }, {
            "year": "1999",
            "value": 0.298
        }, {
            "year": "2000",
            "value": 0.267
        }, {
            "year": "2001",
            "value": 0.411
        }, {
            "year": "2002",
            "value": 0.462
        }, {
            "year": "2003",
            "value": 0.47
        }, {
            "year": "2004",
            "value": 0.445
        }, {
            "year": "2005",
            "value": 0.47
        }],
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
            "autoGridCount":true,
            "selectedGraphFillAlpha":0,
            "graphLineAlpha":0.4,
            "graphLineColor":"#c2c2c2",
            "selectedGraphLineColor":"#888888",
            "selectedGraphLineAlpha":1

        },
        "chartCursor": {
            "categoryBalloonDateFormat": "YYYY",
            "cursorAlpha": 0,
            "valueLineEnabled":true,
            "valueLineBalloonEnabled":true,
            "valueLineAlpha":0.5,
            "fullWidth":true
        },
        "dataDateFormat": "YYYY",
        "categoryField": "year",
        "categoryAxis": {
            "minPeriod": "YYYY",
            "parseDates": true,
            "minorGridAlpha": 0.1,
            "minorGridEnabled": true
        },
        "export": {
            "enabled": true
        }
    });

    chart.addListener("rendered", zoomChart);
    if(chart.zoomChart){
      chart.zoomChart();
    }
    function zoomChart(){
        chart.zoomToIndexes(Math.round(chart.dataProvider.length * 0.4), Math.round(chart.dataProvider.length * 0.55));
    }
    var chartPie = AmCharts.makeChart("chartdivPie", {
  "type": "pie",
  "startDuration": 0,
   "theme": "light",
  "addClassNames": true,
  "innerRadius": "30%",
  "defs": {
    "filter": [{
      "id": "shadow",
      "width": "200%",
      "height": "200%",
      "feOffset": {
        "result": "offOut",
        "in": "SourceAlpha",
        "dx": 0,
        "dy": 0
      },
      "feGaussianBlur": {
        "result": "blurOut",
        "in": "offOut",
        "stdDeviation": 5
      },
      "feBlend": {
        "in": "SourceGraphic",
        "in2": "blurOut",
        "mode": "normal"
      }
    }]
  },
  "dataProvider": [{
    "country": "Lithuania",
    "litres": 501.9
  }, {
    "country": "Czech Republic",
    "litres": 301.9
  }, {
    "country": "Ireland",
    "litres": 201.1
  }, {
    "country": "Germany",
    "litres": 165.8
  }, {
    "country": "Australia",
    "litres": 139.9
  }, {
    "country": "Austria",
    "litres": 128.3
  }, {
    "country": "UK",
    "litres": 99
  }, {
    "country": "Belgium",
    "litres": 60
  }, {
    "country": "The Netherlands",
    "litres": 50
  }],
  "valueField": "litres",
  "titleField": "country",
  "export": {
    "enabled": true
  }
});

chartPie.addListener("init", handleInit);

chartPie.addListener("rollOverSlice", function(e) {
  handleRollOver(e);
});

function handleInit(){
  //chartPie.legend.addListener("rollOverItem", handleRollOver);
}

function handleRollOver(e){
  var wedge = e.dataItem.wedge.node;
  wedge.parentNode.appendChild(wedge);
}
</script>
@endsection
