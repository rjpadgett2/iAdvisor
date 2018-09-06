
$( function() {
 $( "#semester1, #semester2, #semester3, #semester4, #semester5, #semester6, #semester7, #semester8").sortable({
   connectWith: ".connectedSortable"
 }).disableSelection();

});

//Iterate through JSOn and compares values
$.each(remaining_classes, function(i, items) {
  credits = parseInt(items.credits);
  creditLimit += credits;
  if(creditLimit <= 15){
    semesterCredits = parseInt($('#semester-one-credits').text());
    $('#semester-one-credits').text(semesterCredits + credits);
    semester = 'semester1';
    nextSemester = 'semester2';
  }else if(creditLimit > 15 && creditLimit <= 30){
    semesterCredits = parseInt($('#semester-two-credits').text());
    $('#semester-two-credits').text(semesterCredits + credits);
    semester = 'semester2';
    nextSemester = 'semester3';
  }else if(creditLimit > 30 && creditLimit <= 45){
    semesterCredits = parseInt($('#semester-three-credits').text());
    $('#semester-three-credits').text(semesterCredits + credits);
    semester = 'semester3';
    nextSemester = 'semester4';
  }else if(creditLimit > 45 && creditLimit <= 60){
    semesterCredits = parseInt($('#semester-four-credits').text());
    $('#semester-four-credits').text(semesterCredits + credits);
    semester = 'semester4';
    nextSemester = 'semester5';
  }else if(creditLimit > 60 && creditLimit <= 75){
    semesterCredits = parseInt($('#semester-five-credits').text());
    $('#semester-five-credits').text(semesterCredits + credits);
    semester = 'semester5';
    nextSemester = 'semester6';
  }else if(creditLimit > 75 && creditLimit <= 90){
    semesterCredits = parseInt($('#semester-six-credits').text());
    $('#semester-six-credits').text(semesterCredits + credits);
    semester = 'semester6';
    nextSemester = 'semester7';
  }else if(creditLimit > 90 && creditLimit <= 105){
    semesterCredits = parseInt($('#semester-seven-credits').text());
    $('#semester-seven-credits').text(semesterCredits + credits);
    semester = 'semester7';
    nextSemester = 'semester8';
  }else if(creditLimit > 105 && creditLimit <= 120){
    semesterCredits = parseInt($('#semester-eight-credits').text());
    $('#semester-eight-credits').text(semesterCredits + credits);
    semester = 'semester8';
  }
  listMaker();
  $(curr_semester).append('<li id = "'+items.class_id+'" class="list-group-item">'
  + items.class_num + " " + items.class_name + ' <em><h6>Credits:' + items.credits + '</h6></em></li>');
});

function listMaker(){
  next_semester_id =  document.getElementById(nextSemester);
  curr_semester = document.getElementById(semester);
  curr_semester_children = document.getElementById(semester).children;
  //Checks Pre-Reqs and moves class to next semester if current semester contains classes
  //contains that are pre-reqs of selected class
  for (var i = 0; i < curr_semester_children.length; i++) {
   var curr_class = curr_semester_children[i];
   var curr_class_id = curr_class.id;
   for(var j = 0; j < curr_semester_children.length; j++){
     var other_classes_id = curr_semester_children[j].id;
     var other_classes = curr_semester_children[j];
     $.each(class_prereqs, function(j, pre){
       if(other_classes_id == pre.pre_req_of && curr_class_id == pre.course_class_id && other_classes_id != curr_class_id){
         if($.inArray(other_classes, curr_semester_children) != -1){
           next_semester_id.appendChild(other_classes);
         }
       }
     });
   }
  }  //Changes color of list element based on semester fall/spring
}

function prereqCheck(event, ui) {
  //Grabs ul identifier of target ul
  var receiver = event.target;
  var curr_elem = document.getElementById(receiver.id).tagName;
  // var number_one = $(receiver).prev().text().replace(/[^0-9]/gi, '');
  // var curr_credits = parseInt(number_one, 10);

  //ID of list elemtent being dragged
  var origin = ui.item.attr('id');
  var originText = ui.item.text();

  var origin_credits = parseInt(originText.replace(/[^0-9]/gi, ''), 10);

  //ID of Info Div
  var infoDiv = document.getElementById("infoBox");
  //Ajax call that returns list pf pre-reqs from database
    $.ajax({
        url:"../PHP/pre_req_lookup.php",
        type: 'POST',
        data: {origin: origin},
        success: function(data){
          output = JSON.parse(data);
          for(var i = 0; i < output.length; i++){
            //iterates through UL and checks li ID's
            $(receiver).find('li').each(function(j){
              //prits type of values being compared
                if (parseInt($(this).attr("id")) == parseInt(output[i]["pre_req_of"])) {
                  // console.log("yes:" + $(this).attr("id"));
                  // changes color of Li of dragged element to Red to indicate pre_req error
                  document.getElementById($(this).attr("id")).className = "list-group-item list-group-item-danger";
                  if (infoDiv.style.display === "none") {
                      infoDiv.style.display = "block";
                  }
                  infoDiv.innerHTML += "<H6><em>" + originText +"</em></H6> is a pre-req of <H6><em>"
                  + this.innerHTML + "</em></H6> and cannot be taken in the same semester<br>";
                }
            });
          }
        },
    });
}

//Makes Year Div dissapear if there are no classes being taken that year
$( function (){

   var opts = {
     connectWith: ".connectedSortable",
     receive: prereqCheck
   };
   $('.connectedSortable').sortable(opts).disableSelection();
   $('.connectedSortable').find('li').attr('id', function() {
     return $(this).attr('id');
   });

   if ($('#semester1 li').length == 0 && $('#semester2 li').length == 0){
     $('.year-one').hide();
   } else if ($('#semester3 li').length == 0 && $('#semester4 li').length == 0){
     $('.year-two').hide();
   } else if ($('#semester5 li').length == 0 && $('#semester6 li').length == 0){
     $('.year-three').hide();
   } else if ($('#semester7 li').length == 0 && $('#semester8 li').length == 0){
     $('.year-four').hide();
   }
});

//===================================================
//============Submit Functionality===================
//===================================================

  function fetchChild(){
    var data =[];
      $('#semester1  > li').each(function(){
        data.push(buildJSON($(this)));
      });
      $('#semester2  > li').each(function(){
        data.push(buildJSON($(this)));
      });
      $('#semester3  > li').each(function(){
        data.push(buildJSON($(this)));
      });
      $('#semester4  > li').each(function(){
        data.push(buildJSON($(this)));
      });
      $('#semester5  > li').each(function(){
        data.push(buildJSON($(this)));
      });
      $('#semester6  > li').each(function(){
        data.push(buildJSON($(this)));
      });
      $('#semester7  > li').each(function(){
        data.push(buildJSON($(this)));
      });
      $('#semester8  > li').each(function(){
        data.push(buildJSON($(this)));
      });
     return data;
  }

  function buildJSON(li) {
    var li_id = li[0].id;
    var value;
    if(li.is('.list-group-item-danger')){
      value = document.getElementById(li_id)
    }
    var subObj = {
      "id": $(li).attr('id'),
      "class": li.contents().text(),
      "semester": $(li).parent().attr('id'),
      "exception": "value"
    };
    return subObj;
  }

  function ConvertToCSV(objArray) {
    var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
    var str = '';
    for (var i = 0; i < array.length; i++) {
        var line = '';
        for (var index in array[i]) {
            if (line != '') line += ','
            line += array[i][index];
        }
        str += line + '\r\n';
    }
    return str;
  }
// function goToPage() {
//   alert("Class Schedule Submitted!");
//   window.location.href = "../PHP/home.php";
// }

function addDataToDB() {
  // var obj = fetchChild();
  // var finishedJSON = JSON.stringify(obj, null, 2);
  // console.log(finishedJSON);
}

function submitClasses() {
  // var student_semester = [];
  // var obj = fetchChild();
  // var finishedJSON = JSON.stringify(obj, null, 2);
  // $.each(obj, function(i, items) {
  //   if(items.semester == "semester1"){
  //     console.log(items.id);
  //   }
  // });

  var obj = fetchChild();
  var finishedJSON = JSON.stringify(obj, null, 2);
  console.log(finishedJSON);
  var csv = ConvertToCSV(finishedJSON);
  var filename = 'class_schedule.csv';
  var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    if (navigator.msSaveBlob) { // IE 10+
        navigator.msSaveBlob(blob, filename);
    } else {
        var link = document.createElement("a");
        if (link.download !== undefined) { // feature detection
            // Browsers that support HTML5 download attribute
            var url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

}
