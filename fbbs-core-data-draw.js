var fbbsGlobalCharInstance = null;

function FBBSDataDraw (ctx, title = "", type = "value-time") {
  this.ctx = ctx;
  this.title = title;
  this.type = type;
  this.xaxis = "time";
  this.processDataDraw = processDataDraw;
  this.generateDataObj = generateTimeValueDataObj;
  if (type == "value-label") {
    this.generateDataObj = generateValueLabelDataObj;
    this.xaxis = "linear";
  }
}

function generateTimeValueDataObj (keyval_obj) {
  var current_time = new Date().getTime();
  var return_obj = { 
      data: "", 
      label: "", 
      html: "",
      min_timestamp: current_time,
      max_timestamp: current_time
  };
  var entry_obj = new Object();
 
  Object.keys(keyval_obj).forEach(function(key,index) {
    Object.keys(keyval_obj).forEach(function(id,idx) {
        entry_obj[id] = keyval_obj[id];
       });
    });
  var new_id = msgId(entry_obj);
  var new_value = msgValue(entry_obj).trim();
  var new_timestamp = msgTimestamp(entry_obj);
  return_obj.label = new_value;
  var timestamp_to_milli = parseInt(new_timestamp,10)*1000;
  if (timestamp_to_milli < return_obj.min_timestamp)
    return_obj.min_timestamp = timestamp_to_milli;
  if (timestamp_to_milli > return_obj.max_timestamp)
    return_obj.max_timestamp = timestamp_to_milli;
  return_obj.data = {x:  new_timestamp*1000, y: new_value.length};
  var entry_time = parseInt(msgTimestamp(entry_obj));
  var timestamp_diff = (timestamp_to_milli - current_time);
  var new_timediff = Math.abs(Math.round(timestamp_diff/6000)/10);
  return_obj.html = "|@" + new_id + "|minsago(" + new_timediff + ")-=> " +
                    new_value + "<br>";
  return return_obj;
}

function generateValueLabelDataObj (keyval_obj) {
  var current_time = new Date().getTime();
  var return_obj = { 
      data: "", 
      label: "", 
      html: "",
      min_timestamp: current_time,
      max_timestamp: current_time
  };
  var entry_obj = new Object();
 
  Object.keys(keyval_obj).forEach(function(key,index) {
    Object.keys(keyval_obj).forEach(function(id,idx) {
        entry_obj[id] = keyval_obj[id];
       });
    });
  var new_id = msgId(entry_obj);
  var new_value = msgValue(entry_obj).trim();
  var new_timestamp = msgTimestamp(entry_obj);
  return_obj.label = new_value;
  var timestamp_to_milli = parseInt(new_timestamp,10)*1000;
  if (timestamp_to_milli < return_obj.min_timestamp)
    return_obj.min_timestamp = timestamp_to_milli;
  if (timestamp_to_milli > return_obj.max_timestamp)
    return_obj.max_timestamp = timestamp_to_milli;
  return_obj.data = new_value.length;
  var entry_time = parseInt(msgTimestamp(entry_obj));
  var timestamp_diff = (timestamp_to_milli - current_time);
  var new_timediff = Math.abs(Math.round(timestamp_diff/6000)/10);
  return_obj.html = "|@" + new_id + "|minsago=" + new_timediff + ")-=> " +
                    new_value + "<br>";
  return return_obj;
}


function processDataDraw( input_json ) {
  var data_array = [];
  var label_array = [];
  var dashHtml = "";
  var min_timestamp = new Date().getTime();
  var max_timestamp = min_timestamp;
  var current_time = min_timestamp/1000;
  var previous_time = current_time;

  try {
    var jsonresponseparsed = JSON.parse(input_json);
  } catch(err) {
    return;
  }

  if ((jsonresponseparsed == undefined) ||
      (jsonresponseparsed.value == undefined) ||
      (jsonresponseparsed.value[0] == undefined)) return;

  var jsonresponseobj = jsonresponseparsed.value[0];

  var dataProcessor = this.generateDataObj;
  Object.keys(jsonresponseobj).forEach(function(key,index) {
    var array_obj = jsonresponseobj[key];
    var raw_obj = {}
    array_obj.forEach(function addObj (var_obj) { 
        Object.keys(var_obj).forEach(function(vkey,vindex) {
            raw_obj[vkey] = var_obj[vkey];
          });
      });
    var data_obj = dataProcessor(raw_obj);
    data_array.push(data_obj.data);
    label_array.push(data_obj.label);
    dashHtml += data_obj.html;
    if (data_obj.min_timestamp < min_timestamp) {
      min_timestamp = data_obj.min_timestamp;
    }
    if (data_obj.min_timestamp > max_timestamp) {
      max_timestamp = data_obj.max_timestamp;
    }
   });

   var dataStruct = {
     labels: label_array,
     datasets: [
       {
         label: this.title,
         data : data_array,
         fill: true,
         fillColor: "rgba(0,0,125,0.5)",
         borderColor: "rgba(0,200,200,0.2)",
         backgroundColor: "rgba(166,9,200,0.2)",
         pointStyle: "rectRot",
         pointRadius: 5,
         pointBackgroundColor: "rgba(40,200,170,0.9)",
         borderWidth: 2
       }
     ]
     };

  var chart_options = {
           responsive: true,
           animation: {
               onComplete: function () {
                   var ctx = this.chart.ctx;
                   ctx.font = "monospace",
                   ctx.fillStyle = "rgba(200, 220, 20, 0.6)";
                   ctx.strokeColor = "rgba(200,220, 20, 0.9)";
                   var chart_elem = document.getElementById("dashChart");
                   var chart_x_max = chart_elem.width;
                   var chart_y_max = chart_elem.height;
                   var current_data;
                   var current_color = "rgb(0,0,0)";
                   var clean_x_div = 1.0;
                   var clean_y_div = 1.0;
                   this.data.datasets.forEach(function (dataset) {
                       var current_x = 0;
                       var current_y = 0;
                       var gradient = ctx.createLinearGradient(0,0,
                                                               chart_x_max, 0);
                       gradient.addColorStop("0","blue");
                       gradient.addColorStop("0.25","cyan");
                       gradient.addColorStop("0.5","yellow");
                       gradient.addColorStop("1.0","white");
                       ctx.fillStyle=gradient;
                       ctx.strokeStyle="rgba(255,255,255,0.9)";
                       var text_wid_pix = 0;
                       var text_height_pix = 0;
                       Object.keys(dataset._meta).forEach(function (key,idx) {
                           var j=0;
                           ctx.font = "8pt monospace";
                           dataset._meta[key].data.forEach(function (p_obj) {
                               current_y = Math.max(p_obj._model.y,
                                                    25);
                               text_wid_pix = ctx.measureText(label_array[j]);
                               current_x = p_obj._model.x;
                               current_x = Math.min(current_x,
                                   chart_x_max-(text_wid_pix.width));
                               ctx.fillStyle="rgba(130,10,10,0.1)";
                               ctx.fillRect(current_x+15, current_y-15,
                                            25+text_wid_pix.width, 16);
                               ctx.strokeStyle="rgba(55,55,55,0.1)";
                               ctx.rect(current_x+5, current_y-15,
                                        25+text_wid_pix.width, 16);
                               ctx.stroke();
                               ctx.fillStyle=gradient;
                               ctx.fillText(label_array[j],
                                            current_x+10, current_y-15);
                               ctx.fillRect(p_obj._model.x, p_obj._model.y,
                                            2,
                                            Math.abs(chart_y_max-current_y));
                               j++;
                             });
                         });
                       });
                     }
                   },
               legend: {
                   display: true,
                   position: 'bottom',
                   labels: {
                       showScaleLabels: true,
                       usePointStyle: true,
                       fontColor: "rgba(190,250,220,0.9)",
                       fontStyle: "bold"
                     },
                   reverse: false
                 },
               scales: {
                  xAxes: [{
                      type: this.xaxis,
                      time: {
                          max: moment(max_timestamp),
                          min: moment(min_timestamp)
                      },
                      display: true,
                      gridLines: {
                          display: true,
                          offsetGridLines: true
                        },
                      position: "bottom",
                      ticks: {
                        fontSize: 12,
                        fontColor: "rgba(0,250,0,0.9)",
                        fontFamily: "monospace",
                        mirror: false,
                        display: true
                      }
                  }],
                  yAxes: [{
                    display: false,
                    position: "right",
                    gridLines: {
                        display: true,
                        lineWidth: 3,
                      },
                    ticks: {
                      fontColor: "rgba(50,50,0,0.3)",
                      fontFamily: "monospace"
                    },
                  }]
                }
              };

  if (fbbsGlobalCharInstance != null) {
    fbbsGlobalCharInstance.destroy();
    fbbsGlobalCharInstance = null;
  }
  fbbsGlobalCharInstance = new Chart(this.ctx, {
      type: "line",
      data: dataStruct,
      labels: label_array,
      options: chart_options
    });

  document.getElementById("dash").innerHTML = dashHtml;
}

function msgId(msgObj) {
  var return_html = "";
  if (msgObj) {
    if (msgObj["id"] !=  undefined) {
      return_html += msgObj["id"];
    }
  }
  return return_html;
}

function msgIP(msgObj) {
  var return_html = "";
  if (msgObj) {
    if (msgObj["ip"] !=  undefined) {
      return_html += msgObj["ip"];
    }
  }
  return return_html;
}

function msgValue(msgObj) {
  var return_html = "";
  if (msgObj) {
    if (msgObj["value"] !=  undefined) {
      return_html += msgObj["value"];
    }
  }
  return return_html;
}

function msgTimestamp(msgObj) {
  var return_html = "";

  if (msgObj) {
    if (msgObj["timestamp"] !=  undefined) {
     return_html += msgObj["timestamp"];
    }
  }
  return return_html ;
}
