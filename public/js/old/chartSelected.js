function get_selected_columns(elements,toChart, date_column_name ) {
   var dateCol;
   for( var i = 0; i < elements.length; i++) { 
      if(elements[i].checked === true){
         toChart.set(i, elements[i].name);
      }
      if( elements[i].name == date_column_name) {
         dateCol = i;
      }
   }
   return dateCol;
}

function draw_one_graph(dateCol,toChart) {
   var chartContainer = document.getElementById('chart-container');
   var json_data = json_data_per_id[0].graph_data;
   xDataString = json_data[dateCol].values;
   xDataString = xDataString.replace(/ 00:00/g,'');
   var chartYData = [];
   for (var seriesId of toChart.keys()) {
     var seriesName = json_data[seriesId].column_name;
     var seriesData = json_data[seriesId].values;
     var seriesObj = {"seriesname": seriesName, "data": seriesData};
     chartYData.push(seriesObj);
   }
   draw_fusion_graph(xDataString, chartYData, chartContainer);
}

function draw_multiple_graphs(dateCol,toChart) {
   var nr_of_graphs = ( json_data_per_id.length > 6 ) ? 6 : json_data_per_id.length;
   for(var i = 0; i < nr_of_graphs; i++) {
      var graph_number = i + 1;
      var ci = json_data_per_id[i].graph_id;
      var json_data = json_data_per_id[i].graph_data;
      var container_div = 'chart-container-' + graph_number;
      var chartContainer = document.getElementById(container_div);
      xDataString = json_data[dateCol].values;
      xDataString = xDataString.replace(/ 00:00/g,'');
      var chartYData = [];
      for (var seriesId of toChart.keys()) {
        var seriesName = json_data[seriesId].column_name;
        var seriesData = json_data[seriesId].values;
        var seriesObj = {"seriesname": seriesName, "data": seriesData};
        chartYData.push(seriesObj);
      }
      console.log(chartYData);
      draw_fusion_graph(xDataString, chartYData, chartContainer);
   }
}

function foo() {

}
function createChart() {

   // we have variable json_data send from PHP with all data
   var toChart = new Map();
   var dateCol;

   // Scan all checkboxes, and find selected indexes
   var elements = $("#checkboxes th input");
   dateCol = get_selected_columns(elements,toChart, "STARTTIME" );

   var nr_of_elements = json_data_per_id.length;

   if ( nr_of_elements === 1) {
      draw_one_graph(dateCol,toChart);
   } else {
      draw_multiple_graphs(dateCol,toChart);
   }


// Ako je ovo ajax request
var arr = [ "jedan", "dva", "tri"];
arr.map( function(el) { return el*el; });

}

function draw_fusion_graph(xDataString, chartYData, chartContainer) {

    FusionCharts.ready(function () {
        var visitChart = new FusionCharts({
            type: 'zoomline',
            renderAt: chartContainer,
            width: '100%',
            height: '500px',
            dataFormat: 'json',
            dataSource: {
                "chart": {
                    "caption": "Selected KPIs",
                    "paletteColors": "#0075c2,#1aaf5d,#cc0000,#ff00ff,#cc9900",
                    "captionFontSize": "14",
                    "subcaptionFontSize": "14",
                    "subcaptionFontBold": "0",
                    "showBorder": "1",
                    "bgColor": "#ffffff",
                    "baseFont": "Helvetica Neue,Arial",
                    "showCanvasBorder": "0",
                    "showShadow": "0",
                    "showAlternateHGridColor": "0",
                    "canvasBgColor": "#ffffff",
                    "xaxisname": "Date",
                    // "forceAxisLimits" : "1",
                    "setAdaptiveYMin": "1",
                    "pixelsPerPoint": "0",
                    "pixelsPerLabel": "30",
                    "lineThickness": "2",
                    "compactdatamode" : "1",
                    "dataseparator" : "|",
                    "labelHeight": "30",
                    "scrollheight": "10",
                    "flatScrollBars": "1",
                    "scrollShowButtons": "0",
                    "scrollColor": "#cccccc",
                    "legendBgAlpha": "0",
                    "legendBorderAlpha": "0",
                    "legendShadow": "0",
                    "legendItemFontSize": "12",
                    "legendItemFontColor": "#666666"                
                },
          "categories": [{ "category":  xDataString }],
          "dataset": chartYData
            }
        }).render();
    });

}