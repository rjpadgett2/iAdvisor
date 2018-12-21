var creditLimit = 0;
var semester;
var credits;
var semesterNumber;
var nextSemester;
var curr_semester_children;
var curr_semester;
var next_semester;
var semesterCredits;

var remaining_classes;
var  class_prereqs;
var saved_classes;


//===================================================
//============Information Div Functionality===========
//===================================================
  function activateButton() {

  }
  function countCredits(ul, origin){
    var newCount = 0;
    var oldCount = 0;
    var old_container = origin.parentNode.childNodes[3].getElementsByTagName("span")[0];
    var new_container = ul.parentNode.childNodes[3].getElementsByTagName("span")[0];
    for(var i = 0; i < ul.childNodes.length; i++){
      var class_li = ul.childNodes[i].getAttribute("data-credits");
      newCount += parseInt(class_li);
    }
    for(var i = 0; i < origin.childNodes.length; i++){
      var class_li = origin.childNodes[i].getAttribute("data-credits");
      oldCount += parseInt(class_li);
    }
    old_container.innerHTML = oldCount;
    new_container.innerHTML = newCount;
    if(newCount > 18){
      new_container.style.color = "red";
    }else {
      new_container.style.color = "#0000FF";
    }

    if (oldCount > 18){
      old_container.style.color = "red";
    }else{
      old_container.style.color = "#0000FF";
    }
  }

  function getInfo(elem) {
    let elem_class = elem.getAttribute("data-class-name");
    let title = document.getElementsByClassName('modal-title')[0];
    let gen_info = document.getElementById('gen_info');
    let grade_method = document.getElementById('grading_method');
    let pre_req = document.getElementById('pre_reqs');
    let sections = document.getElementById('sections');
    $.ajax({
        url:"https://api.umd.io/v0/courses/"+elem_class,
        type: 'GET',
        success: function(data){
          title.innerHTML = data.course_id + " <h5>" + data.name + "</h5>";
          gen_info.innerHTML = data.description;
          grade_method.innerHTML = data.grading_method;
          pre_reqs.innerHTML = data.relationships.prereqs;
          sections.innerHTML = data.sections;
        },
        error: function(xhr){
          alert("Cannot Find Class Info");
        },
    });
  }

  function hideInfoDiv(elem) {
    var infoBox = document.getElementById('infoBox');
    infoBox.innerHTML = "";
    infoBox.style.display= 'none';
    elem.style.backgroundColor = null;
  }
  function showInfoDiv(elem) {
    //Grabs ul identifier of target ul
    var ul = elem.parentElement;
    var ul_id = ul.id;
    var origin = elem.id;
    var infoBox = document.getElementById('infoBox');
    var msg = "";

    if(elem.classList.contains("exceptions")){
      infoBox.style.display= 'block';
      msg = "<h4>You Cannot take <i><strong>" + elem.textContent + "</strong></i> in the same semester as</h4><br><br>";
      infoBox.innerHTML += msg;
        $.ajax({
            url:"../routes/planner/planner_queries.php?action=pre_req_lookup",
            type: 'POST',
            data: {origin: origin},
            success: function(data){
                output = JSON.parse(data);
                for(var i = 0; i < output.length; i++){
                  //iterates through UL and checks li ID's
                  $(ul).find('li').each(function(j){
                    //planner/prits type of values being compared
                      if (parseInt($(this).attr("id")) == parseInt(output[i]["pre_req_of"])) {
                        // console.log("yes:" + $(this).attr("id"));
                        // changes color of Li of dragged element to Red to indicate pre_req error
                        infoBox.innerHTML += "" + output[i].class_abbreviation + " " + output[i].class_name + "<br>";
                      }
                  });
                }
            },
        });
    }else{
      elem.style.backgroundColor = "#B0B8FE";
    }
   }

  function prereqCheck(event, ui) {
    //Grabs ul identifier of target ul
    var receiver = event.target;
    var sender = ui.sender[0];
    countCredits(receiver, sender);
    var curr_elem = document.getElementById(receiver.id).tagName;

    //ID of list elemtent being dragged
    var origin = ui.item.attr('id');
    var originText = ui.item.text();

    var origin_credits = parseInt(originText.replace(/[^0-9]/gi, ''), 10);
    $.ajax({
        url:"../routes/planner/planner_queries.php?action=pre_req_lookup",
        type: 'POST',
        data: {origin: origin},
        success: function(data){
          if(document.getElementById(ui.item.attr('id')).hasAttribute("exception")){
            document.getElementById(ui.item.attr('id')).removeAttribute("exception");
            document.getElementById(ui.item.attr('id')).classList.remove("exceptions");
          }else {
            output = JSON.parse(data);
            for(var i = 0; i < output.length; i++){
              //iterates through UL and checks li ID's
              $(receiver).find('li').each(function(j){
                //planner/prits type of values being compared
                  if (parseInt($(this).attr("id")) == parseInt(output[i]["pre_req_of"])) {
                    // console.log("yes:" + $(this).attr("id"));
                    // changes color of Li of dragged element to Red to indicate pre_req error
                    document.getElementById(ui.item.attr('id')).className = "list-group-item exceptions";
                    document.getElementById(ui.item.attr('id')).setAttribute("exception","1");
                  }
              });
            }
          }
        },
        error: function (jqXHR, exception) {
          var msg = '';
          if (jqXHR.status === 0) {
              msg = 'Not connect.\n Verify Network.';
          } else if (jqXHR.status == 404) {
              msg = 'Requested page not found. [404]';
          } else if (jqXHR.status == 500) {
              msg = 'Internal Server Error [500].';
          } else if (exception === 'parsererror') {
              msg = 'Requested JSON parse failed.';
          } else if (exception === 'timeout') {
              msg = 'Time out error.';
          } else if (exception === 'abort') {
              msg = 'Ajax request aborted.';
          } else {
              msg = 'Uncaught Error.\n' + jqXHR.responseText;
          }
          alert(msg);
        }
    });
  }

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
         if(other_classes_id == pre.pre_req_of && curr_class_id == pre.class_id && other_classes_id != curr_class_id){
           if($.inArray(other_classes, curr_semester_children) != -1){
             next_semester_id.appendChild(other_classes);
           }
         }
       });
     }
    }
  }

//===================================================
//============Submit Functionality===================
//===================================================
  function addDataToDB() {
    var obj = fetchChild();
    var finishedJSON = JSON.stringify(obj, null, 2);
    $.ajax({
        url:"../routes/planner/planner_queries.php?action=save_classes",
        type: 'POST',
        data: {finishedJSON: finishedJSON},
        success: function(data){

        }
    });
    location.reload();
  }

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
    var elem = document.getElementById(li_id);
    var value = 0;
    if (elem.getAttribute("exception")){
      value = elem.getAttribute("exception");
    }
    var subObj = {
      // "student_id": ,
      "id": $(li).attr('id'),
      "class": li.contents().text(),
      "semester": $(li).parent().attr('id'),
      "exceptions": value
    }
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
    location.reload();
  }

  //===================================================
  //============Agenda AJAX Calls===================
  //===================================================
  function doSearch(val){
    var httpxml;
    try{
      // Firefox, Opera 8.0+, Safari
      httpxml=new XMLHttpRequest();
      }catch (e){
      // Internet Explorer
        try{
          httpxml=new ActiveXObject("Msxml2.XMLHTTP");
          }catch (e){
            try{
              httpxml=new ActiveXObject("Microsoft.XMLHTTP");
          }catch (e){
            alert("Your browser does not support AJAX!");
            return false;
          }
        }
      }
    function stateChanged(){
      if(httpxml.readyState==4){
        var myarray = JSON.parse(httpxml.responseText);
        var str = document.getElementById('search_result_list');
        $(str).empty();
        for (i=0;i<myarray.data.length;i++){
          $(str).append('<li title = "Click For Class Info" data-credits = "'+ myarray.data[i].credits +'" data-class-name = "'+myarray.data[i].class_abbreviation+'" onclick = "getInfo(this)" onMouseOver="showInfoDiv(this)" onMouseOut = "hideInfoDiv(this)" data-toggle="modal" data-target="#infoModal" id =  "'
          +myarray.data[i].class_id+'" class="list-group-item single-class">'
          + '<span class = "class_name">'+myarray.data[i].class_abbreviation + " " + myarray.data[i].class_name + '</span> <em><span class = "credit_num">Credits: ' + myarray.data[i].credits + '</span></em></li>');

        }

        // if(myarray.value.status1 != 'T'){
        //   document.getElementById("msg").innerHTML="About " + myarray.value.no_records2 + " & " + myarray.value.no_records + " results " + " Message : "+ myarray.value.message;
        // }else{
        //   document.getElementById("msg").innerHTML="About " + myarray.value.no_records2 + " & " + myarray.value.no_records  + " results " ;
        // }
        var endrecord=myarray.value.endrecord
        document.getElementById("navigation").innerHTML= "<table width=700><tr><td width=350><input type=button id=\'back\' value=Prev onClick=\"ajaxFunction('bk'); return false\"></td><td width=350 align=right><input type=button value=Next id=\"fwd\" onClick=\"ajaxFunction(\'fw\');  return false\"></td></tr></tr> </table>";
        myForm.st.value=endrecord;
        if(myarray.value.end =="yes"){
          document.getElementById("fwd").style.display='inline';
        }else{
          document.getElementById("fwd").style.display='none';}
        if(myarray.value.startrecord =="yes"){
          document.getElementById("back").style.display='inline';
        }else{
          document.getElementById("back").style.display='none';
        }
      }
    }

    var url="../routes/planner/search.php";
    var str=document.getElementById("search_term").value;
    var myendrecord=myForm.st.value;

    url=url+"?txt="+str;
    url=url+"&endrecord="+myendrecord;
    url=url+"&direction="+val;
    url=url+"&sid="+Math.random();
    //document.getElementById("txtHint").innerHTML=url
    httpxml.onreadystatechange=stateChanged;
    httpxml.open("GET",url,true);
    httpxml.send(null);
    // document.getElementById("msg").innerHTML="Please Wait ...";
    // document.getElementById("msg").style.display='inline';
  }

  function resetDB() {
  $.ajax({
      url:"../routes/planner/planner_queries.php?action=reset_planner",
      type: 'POST',
      success: function(data){
        alert(data);
      },
      error: function (jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
        alert(msg);
      }
     });
     location.reload();
  }

  function ajax(url) {
    return new Promise(function(resolve, reject) {
      var xhr = new XMLHttpRequest();
      xhr.onload = function() {
        resolve(this.responseText);
      };
      xhr.onerror = reject;
      xhr.open('GET', url);
      xhr.send();
    });
  }
  function load_classes() {
    return $.ajax({
        // url:"../routes/planner/planner_queries.php?action=load_classes",
        url:"../routes/planner/planner_queries.php?action=load_classes",
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function(data){
          remaining_classes = data;

        }
    });
  }

  function pre_req_classes() {
    return $.ajax({
        // url:"../routes/planner/planner_queries.php?action=pre_req_classes",
        url:"../routes/planner/planner_queries.php?action=load_pre_reqs",
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function(data){
          class_prereqs = data;
        }
    });
  }

  function load_saved_classes() {
    return $.ajax({
        url:"../routes/planner/planner_queries.php?action=load_saved_classes",
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function(data){

          saved_classes = data;

        }
    });
  }

  function umd_load_classes() {
    return $.ajax({
        url:"../routes/planner/planner_queries.php?action=umd_load_classes",
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function(data){
          console.log(data);
        }
    });
  }

$.when(load_classes(), pre_req_classes(), load_saved_classes()).done(function() {

  $(document).ready(function(){

    $( function() {
     $( "#semester1, #semester2, #semester3, #semester4, #semester5, #semester6, #semester7, #semester8, #search_result_list").sortable({
       connectWith: ".connectedSortable"
     }).disableSelection();
    });
    $( function() {
      if (saved_classes.length < 1){
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


          $(curr_semester).append('<li title = "Click For Class Info" data-credits = "'+ items.credits +'" data-class-name = "'+items.class_abbreviation+'" onclick = "getInfo(this)" onMouseOver="showInfoDiv(this)" onMouseOut = "hideInfoDiv(this)" data-toggle="modal" data-target="#infoModal" id =  "'
          +items.class_id+'" class="list-group-item single-class">'
          + '<span class = "class_name">'+ items.class_abbreviation + " " + items.class_name + '</span> <em><span class = "credit_num">Credits: ' + items.credits + '</span></em></li>');

        });
      }else {
        //Upload Saved Data
        $.each(saved_classes, function(i, items) {

          curr_semester = document.getElementById(items.semester);
          $(curr_semester).append('<li title = "Click For Class Info" data-credits = "'+ items.credits +'" data-class-name = "'+items.class_abbreviation+'" onclick = "getInfo(this)" onMouseOver="showInfoDiv(this)" onMouseOut = "hideInfoDiv(this)" data-toggle="modal" data-target="#infoModal" id = "'
          +items.class_id+'" class="list-group-item single-class">'
          + '<span class = "class_name">'+items.class_abbreviation + " " + items.class_name + '</span> <em><span class = "credit_num">Credits:' + items.credits + '</span></em></li>');

          if (items.exceptions == 1){
            get_class = document.getElementById(items.class_id);
            get_class.setAttribute("exception", items.exceptions);
            get_class.classList.add("exceptions");
          }
          countCredits(curr_semester);
        });
      }
   });
    //Makes Year Div dissapear if there are no classes being taken that year
    $( function (){
      //===================================================
      //============Search Functionality===================
      //===================================================
      // umd_load_classes()
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
  });
});
