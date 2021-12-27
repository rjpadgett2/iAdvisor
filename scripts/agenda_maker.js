let creditLimit = 0;
let semester;
let credits;
let semesterNumber;
let nextSemester;
let curr_semester_children;
let curr_semester;
let next_semester;
let semesterCredits;
let note;
let originalClasses = {};

let remaining_classes;
let  class_prereqs;
let saved_classes;


//===================================================
//============Information Div Functionality===========
//===================================================

// Filter Box Functionlaity
  function showFilterBox(item){
    // item.toggle('active');
    let content = item.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    }
  }


  function countCredits(ul, origin){
    let newCount = 0;
    let oldCount = 0;
    let new_container = ul.parentNode.childNodes[5].getElementsByTagName("span")[0];
    for(let i = 0; i < ul.childNodes.length; i++){
      let class_li = ul.childNodes[i].getAttribute("data-credits");
      newCount += parseInt(class_li);
    }
    new_container.innerHTML = newCount;
    if(newCount > 18){
      new_container.style.color = "red";
    }else {
      new_container.style.color = "#0000FF";
    }
    if(origin.parentNode.childNodes[5].getElementsByTagName("span")[0] != null){
      let old_container = origin.parentNode.childNodes[5].getElementsByTagName("span")[0];
      for(let i = 0; i < origin.childNodes.length; i++){
        let class_li = origin.childNodes[i].getAttribute("data-credits");
        oldCount += parseInt(class_li);
      }
      old_container.innerHTML = oldCount;
      if (oldCount > 18){
        old_container.style.color = "red";
      }else{
        old_container.style.color = "#0000FF";
      }
    }
  }

  function getInfo(elem) {
    let modal = document.getElementById('infoModal');
    let span = document.getElementsByClassName("close")[0];
    modal.style.display = "block";

    span.onclick = function() {
      modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }

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
        error: function (jqXHR, exception) {
          let msg = '';
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

  function hideInfoDiv(elem) {
    let infoBox = document.getElementById('infoBox');
    infoBox.innerHTML = "";
    infoBox.style.display= 'none';
    elem.style.backgroundColor = null;
  }
  function classAlertInfo(elem) {
    //Grabs ul identifier of target ul
    let ul = elem.parentElement;
    let ul_id = ul.id;
    let origin = elem.id;
    let infoBox = document.getElementById('infoBox');
    let msg = "";

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
                for(let i = 0; i < output.length; i++){
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
            error: function (jqXHR, exception) {
              let msg = '';
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
    }else{
      elem.style.backgroundColor = "#B0B8FE";
    }
   }

  function prereqCheck(event, ui) {
    //Grabs ul identifier of target ul
    let receiver = event.target;
    let sender = ui.sender[0];

    //Semester element is being dragged to
    let nextSemester = receiver.id;

    countCredits(receiver, sender);
    let curr_elem = document.getElementById(receiver.id).tagName;

    //ID of list elemtent being dragged
    let origin = ui.item.attr('id');
    let originText = ui.item.text();

    let origin_credits = parseInt(originText.replace(/[^0-9]/gi, ''), 10);
    $.ajax({
        url:"../routes/planner/planner_queries.php?action=pre_req_lookup",
        type: 'POST',
        data: {origin: origin},
        success: function(data){
          if(document.getElementById(ui.item.attr('id')).hasAttribute("exception")){
            document.getElementById(ui.item.attr('id')).removeAttribute("exception");
            document.getElementById(ui.item.attr('id')).classList.remove("exceptions");
            document.getElementById(ui.item.attr('id')).setAttribute("data-semester",  nextSemester);
          }else {
            output = JSON.parse(data);
            if(output.length != null){
              for(let i = 0; i < output.length; i++){
                //iterates through UL and checks li ID's
                $(receiver).find('li').each(function(j){
                  //planner/prits type of values being compared
                    if (parseInt($(this).attr("id")) == parseInt(output[i]["pre_req_of"])) {
                      // console.log("yes:" + $(this).attr("id"));
                      // changes color of Li of dragged element to Red to indicate pre_req error
                      document.getElementById(ui.item.attr('id')).className = "list-group-item single-class exceptions";
                      document.getElementById(ui.item.attr('id')).setAttribute("data-semester",  nextSemester);
                      document.getElementById(ui.item.attr('id')).setAttribute("exception","1");
                    }
                });
              }
            }else {

            }
          }
        },
        error: function (jqXHR, exception) {
          let msg = '';
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
    for (let i = 0; i < curr_semester_children.length; i++) {
     let curr_class = curr_semester_children[i];
     let curr_class_id = curr_class.id;
     for(let j = 0; j < curr_semester_children.length; j++){
       let other_classes_id = curr_semester_children[j].id;
       let other_classes = curr_semester_children[j];
       $.each(class_prereqs, function(j, pre){
         if(other_classes_id == pre.pre_req_of && curr_class_id == pre.class_id && other_classes_id != curr_class_id){
           if($.inArray(other_classes, curr_semester_children) != -1){
             next_semester_id.appendChild(other_classes);
             other_classes.setAttribute("data-semester", nextSemester);
           }
         }
       });
     }
    }
  }


  function addDataToDB() {
    let obj = fetchChild();
    let finishedJSON = JSON.stringify(obj, null, 2);
    $.ajax({
        url:"../routes/planner/planner_queries.php?action=save_classes",
        type: 'POST',
        data: {finishedJSON: finishedJSON},
        success: function(data){
          alert("Class Saved Succesfully!")
        },
        error: function (jqXHR, exception) {
          let msg = '';
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
  //===================================================
  //============Submit Functionality===================
  //===================================================
  function leaveNote(){
    let modal = document.getElementById('msgModal');
    let span = document.getElementsByClassName("close")[1];
    modal.style.display = "block";

    span.onclick = function() {
      modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  }

  function addMsg(elem){
    note = elem;
    console.log(note);
  }
  function fetchChild(){
    let data =[];

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

  // function buildPersonalJSON(){
  //   let subObj = {
  //     "Student Name":
  //     "Student Email":
  //     "Student UID":
  //     "Note For Advisor":
  //   }
  //   return subObj;
  // }

  function buildJSON(li) {
    let li_id = li[0].id;
    let elem = document.getElementById(li_id);
    let subObj = {
      // "id": $(li).attr('id'),
      "class": li.contents().text(),
      "semester": $(li).parent().attr('id'),
      "credits": $(li).attr('data-credits'),
      "exceptions": 0,
      "Student Message": note
    }
    return subObj;
  }

  function ConvertToCSV(objArray) {
    const items = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
    const replacer = (key, value) => value === null ? '' : value // specify how you want to handle null values here
    const header = Object.keys(items[0])
    let csv = items.map(row => header.map(fieldName => JSON.stringify(row[fieldName], replacer)).join(','))
    csv.unshift(header.join(','))
    csv = csv.join('\r\n')
    return csv
  }

  function submitClasses() {
    if (confirm('Are you sure you want to submit your class schedule?')) {
      let student_semester = [];
      let obj = fetchChild();
      let finishedJSON = JSON.stringify(obj, null, 2);
      let csv = ConvertToCSV(finishedJSON);
      let filename = 'class_schedule.csv';
      let blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        if (navigator.msSaveBlob) { // IE 10+
            navigator.msSaveBlob(blob, filename);
        } else {
            let link = document.createElement("a");
            if (link.download !== undefined) { // feature detection
                // Browsers that support HTML5 download attribute
                let url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", filename);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

        }
        alert("Classes Submitted Succesfully!");
        location.reload();
    } else {

    }
  }

  //===================================================
  //============Agenda AJAX Calls===================
  //===================================================
  function doSearch(val){
    let httpxml;
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
        let myarray = JSON.parse(httpxml.responseText);
        let str = document.getElementById('search_result_list');
        $(str).empty();
        for (i=0;i<myarray.data.length;i++){
          $(str).append('<li title = "Click For Class Info" data-credits = "'+ myarray.data[i].credits +'" data-class-name = "'+myarray.data[i].class_abbreviation+'" onclick = "getInfo(this)" onMouseOver="classAlertInfo(this)" onMouseOut = "hideInfoDiv(this)" id =  "'
          +myarray.data[i].class_id+'" class="list-group-item single-class">'
          + '<span class = "class_name">'+myarray.data[i].class_abbreviation + " " + myarray.data[i].class_name + '</span> <em><span class = "credit_num">Credits: ' + myarray.data[i].credits + '</span></em></li>');

        }
        // let endrecord=myarray.value.endrecord
        // document.getElementById("navigation").innerHTML= "<table width=700><tr><td width=350><input type=button id=\'back\' value=Prev onClick=\"ajaxFunction('bk'); return false\"></td><td width=350 align=right><input type=button value=Next id=\"fwd\" onClick=\"ajaxFunction(\'fw\');  return false\"></td></tr></tr> </table>";
        // myForm.st.value=endrecord;
        // if(myarray.value.end =="yes"){
        //   document.getElementById("fwd").style.display='inline';
        // }else{
        //   document.getElementById("fwd").style.display='none';}
        // if(myarray.value.startrecord =="yes"){
        //   document.getElementById("back").style.display='inline';
        // }else{
        //   document.getElementById("back").style.display='none';
        // }
      }
    }

    let url="../routes/planner/search.php";
    let str=document.getElementById("search_term").value;
    let myendrecord=myForm.st.value;

    url=url+"?txt="+str;
    url=url+"&endrecord="+myendrecord;
    url=url+"&direction="+val;
    url=url+"&sid="+Math.random();

    httpxml.onreadystatechange=stateChanged;
    httpxml.open("GET",url,true);
    httpxml.send(null);
  }

  function resetDB() {
  $.ajax({
      url:"../routes/planner/planner_queries.php?action=reset_planner",
      type: 'POST',
      success: function(data){
        alert("Database Reset Succefully!");
      },
      error: function (jqXHR, exception) {
        let msg = '';
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
      let xhr = new XMLHttpRequest();
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

        },
        error: function (jqXHR, exception) {
          let msg = '';
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

  function pre_req_classes() {
    return $.ajax({
        // url:"../routes/planner/planner_queries.php?action=pre_req_classes",
        url:"../routes/planner/planner_queries.php?action=load_pre_reqs",
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function(data){
          class_prereqs = data;
        },
        error: function (jqXHR, exception) {
          let msg = '';
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

  function load_saved_classes() {
    return $.ajax({
        url:"../routes/planner/planner_queries.php?action=load_saved_classes",
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function(data){
          saved_classes = data;
        },
        error: function (jqXHR, exception) {
          let msg = '';
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

  function umd_load_classes() {
    return $.ajax({
        url:"../routes/planner/planner_queries.php?action=umd_load_classes",
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function(data){
          console.log(data);
        },
        error: function (jqXHR, exception) {
          let msg = '';
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

$.when(load_classes(), pre_req_classes(), load_saved_classes()).done(function() {
  $(document).ready(function(){
    $( function() {
     $( ".semester_ul").sortable({
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
            semester = 'semester1';
            nextSemester = 'semester2';
          }else if(creditLimit > 15 && creditLimit <= 30){
            semester = 'semester2';
            nextSemester = 'semester3';
          }else if(creditLimit > 30 && creditLimit <= 45){
            semester = 'semester3';
            nextSemester = 'semester4';
          }else if(creditLimit > 45 && creditLimit <= 60){
            semester = 'semester4';
            nextSemester = 'semester5';
          }else if(creditLimit > 60 && creditLimit <= 75){
            semester = 'semester5';
            nextSemester = 'semester6';
          }else if(creditLimit > 75 && creditLimit <= 90){
            semester = 'semester6';
            nextSemester = 'semester7';
          }else if(creditLimit > 90 && creditLimit <= 105){
            semester = 'semester7';
            nextSemester = 'semester8';
          }else if(creditLimit > 105 && creditLimit <= 120){
            semester = 'semester8';
          }
          listMaker();

          let li_elem = document.createElement("LI");
          //Setting Attributes for LI
          li_elem.setAttribute("title", "Click For Class Info");
          li_elem.setAttribute("data-credits", items.credits);
          li_elem.setAttribute("data-class-name", items.class_abbreviation);
          li_elem.setAttribute("data-semester", semester);
          li_elem.setAttribute("onclick", "getInfo(this)");
          li_elem.setAttribute("onMouseOver", "classAlertInfo(this)");
          li_elem.setAttribute("onMouseOut", "hideInfoDiv(this)");
          li_elem.setAttribute("id", items.class_id);
          li_elem.setAttribute("class", "list-group-item single-class");
          //Adding Text to LI
          let span = document.createElement('span');
          let em = document.createElement('em');
          let credit_span = document.createElement('span');

          span.setAttribute("class", "class_name");
          em.setAttribute("class", "credit_num");
          let credit_text = document.createTextNode('Credits: ' + items.credits);
          let main_text = document.createTextNode(items.class_abbreviation + " " + items.class_name);
          em.appendChild(credit_text);
          credit_span.appendChild(em);
          span.appendChild(main_text);
          li_elem.appendChild(span);
          li_elem.appendChild(credit_span);

          // originalClasses.push(li_elem);
          $(curr_semester).append(li_elem);
        });
      }else {
        //Upload Saved Data
        $.each(saved_classes, function(i, items) {
          curr_semester = document.getElementById(items.semester);

          let li_elem = document.createElement("LI");
          //Setting Attributes for LI
          li_elem.setAttribute("title", "Click For Class Info");
          li_elem.setAttribute("data-credits", items.credits);
          li_elem.setAttribute("data-class-name", items.class_abbreviation);
          li_elem.setAttribute("onclick", "getInfo(this)");
          li_elem.setAttribute("onMouseOver", "classAlertInfo(this)");
          li_elem.setAttribute("onMouseOut", "hideInfoDiv(this)");
          li_elem.setAttribute("id", items.class_id);
          li_elem.setAttribute("class", "list-group-item single-class");
          //Adding Text to LI
          let span = document.createElement('span');
          let em = document.createElement('em');
          let credit_span = document.createElement('span');

          span.setAttribute("class", "class_name");
          em.setAttribute("class", "credit_num");
          let credit_text = document.createTextNode('Credits: ' + items.credits);
          let main_text = document.createTextNode(items.class_abbreviation + " " + items.class_name);
          em.appendChild(credit_text);
          credit_span.appendChild(em);
          span.appendChild(main_text);
          li_elem.appendChild(span);
          li_elem.appendChild(credit_span);

          originalClasses[li_elem] = semester;
          $(curr_semester).append(li_elem);
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

      //Calculate Credits
      let credit = document.getElementsByClassName('credits');
      for(let i = 0; i < credit.length; i++){
        let ul_elem = credit[i].parentNode.parentNode.parentNode.childNodes[7];
        let li_elem = ul_elem.getElementsByTagName('li');
        let total = 0;
        for(let j = 0; j < li_elem.length;j++){
          total += parseInt(li_elem[j].getAttribute('data-credits'));
        }
        credit[i].innerHTML = total;
      }

      // umd_load_classes()
       let opts = {
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
