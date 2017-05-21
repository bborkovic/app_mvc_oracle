(function(){
   populate_date_fields();
   populate_meas_classes();
})();

function get_array_of_formatted_dates(from, to) {
   var date = new Date();
   date.setDate(date.getDate() - from + 1);
   var arr_of_dates = [];
   for (var i = 0; i <= (to-from); i ++) {
      date.setDate(date.getDate() - 1);
      var mm = ( date.getMonth()+1);
      var yy = date.getFullYear();
      var dd = date.getDate();
      mm = ( mm < 10 ) ? '0' + mm : mm;
      dd = ( dd < 10 ) ? '0' + dd : dd;
      var formatted_date = dd + "." + mm + '.' + yy;
      arr_of_dates.push(formatted_date);
   }
   return arr_of_dates;
}

function populate_date_fields() {
   var date_from = document.getElementById("date_from");
   var dates = get_array_of_formatted_dates(0, 101);
   for (var index in dates) {
      dd = dates[index];
      option_element = document.createElement('option');
      option_element.value = dd;
      option_element.textContent = dd;
      date_from.appendChild(option_element);
   }
   var date_to = document.getElementById("date_to");
   var dates = get_array_of_formatted_dates(0, 100);
   for (var index in dates) {
      dd = dates[index];
      option_element = document.createElement('option');
      option_element.value = dd;
      option_element.textContent = dd;
      date_to.appendChild(option_element);
   }
}

function populate_meas_classes() {
   meas_classes.sort(function(a, b){ return a.id > b.id ? 1:a.id < b.id?-1:0 });

   var meas_class = document.getElementById("meas-class");
   for (var i=0; i < meas_classes.length; i++) {
      option_element = document.createElement('option');
      option_element.value = meas_classes[i].id;
      option_element.textContent = meas_classes[i].value;
      meas_class.appendChild(option_element);
   }
   meas_class.selectedIndex = -1;
}

function meas_class_selected() {
   var meas_levels = document.getElementById("meas-level");
   var meas_ids = document.getElementById("meas-ids");
   while (meas_levels.hasChildNodes()) {
      meas_levels.removeChild(meas_levels.lastChild);
   }
   while (meas_ids.hasChildNodes()) {
      meas_ids.removeChild(meas_ids.lastChild);
   }

   var table_name = document.getElementById("meas-class").value;
   var meas_levels_data = meas_tables_levels[table_name];

   for (var i=0; i < meas_levels_data.length; i++) {
      option_element = document.createElement('option');
      option_element.value = meas_levels_data[i];
      option_element.textContent = meas_levels_data[i];
      meas_levels.appendChild(option_element);
   }
   meas_levels.selectedIndex = 0;
   meas_level_selected();
}

function meas_level_selected() {
   // Clear existing measure ids
   var meas_ids = document.getElementById("meas-ids");
   while (meas_ids.hasChildNodes()) {
      meas_ids.removeChild(meas_ids.lastChild);
   }
   // Get selected meas_level
   var meas_levels = document.getElementById("meas-level");
   var level = meas_levels.value;
   // Get selected table_name
   var table_name = document.getElementById("meas-class").value;

   // Than, find from json object which ids are available
   var measure_ids = meas_tables_levels_ids[table_name + '-' + level];  
   measure_ids.sort();
   
   for (var i=0; i < measure_ids.length; i++) {
      option_element = document.createElement('option');
      option_element.value = measure_ids[i];
      option_element.textContent = measure_ids[i];
      meas_ids.appendChild(option_element);
   }
}
