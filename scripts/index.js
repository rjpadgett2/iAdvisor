//Global Variables
let close;
let modal;

//Functions
function closeModal(){
  if(document.getElementById('advisor_login').style.display='block'){
    document.getElementById('advisor_login').style.display='none';
  }else if(document.getElementById('student_login').style.display='block'){
    document.getElementById('student_login').style.display='none';
  }else if(document.getElementById('student_registration').style.display='block'){
    document.getElementById('student_registration').style.display='none';
  }
};
function studentSubmitForm() {

		var data = $("#student_login_form").serialize();
		$.ajax({
			type : 'POST',
			url  : '../routes/login/registration.php?action=student_login',
			data : data,
			beforeSend: function(){
				$("#error").fadeOut();
				$("#student_login-submit").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; sending ...');
			},
			success : function(response){
        console.log("this");
				if(response){
					console.log('dddd');
          console.log(response);
					$("#student_login-submit").html('Signing In ...');
					setTimeout(' window.location.href = "../index.php"; ',2000);
				} else {
					$("#error").fadeIn(1000, function(){
						$("#error").html(response).show();
					});
				}
			},
      error: function(xhr, textStatus, error){
        console.log(xhr.statusText);
        console.log(textStatus);
        console.log(error);
      }
		});
		return false;
	}

  function advisorSubmitForm() {
  		var data = $("#advisor_login_form").serialize();
  		$.ajax({
  			type : 'POST',
  			url  : '../routes/login/registration.php?action=advisor_login',
  			data : data,
  			beforeSend: function(){
  				$("#error").fadeOut();
  				$("#student_login-submit").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; sending ...');
  			},
  			success : function(response){
  				if(response){
  					console.log('dddd');
            console.log(response);
  					$("#advisor_login-submit").html('Signing In ...');
  					setTimeout(' window.location.href = "../views/advisor_portal.php"; ',2000);
  				} else {
  					$("#error").fadeIn(1000, function(){
  						$("#error").html(response).show();
  					});
  				}
  			},
        error: function(xhr, textStatus, error){
          console.log(xhr.statusText);
          console.log(textStatus);
          console.log(error);
        }
  		});
  		return false;
  	}


  function studentRegisterForm() {
  		var data = $("#student_register-form").serialize();
  		$.ajax({
  			type : 'POST',
  			url  : '../routes/login/registration.php?action=student_register',
  			data : data,
  			beforeSend: function(){
  				$("#error").fadeOut();
  				$("#register_button").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; sending ...');
  			},
  			success : function(response){
  				if($.trim(response) === "1"){
  					$("#register_button").html('Signing In ...');
  					$('#success').html('Successfully! User has been Registered.').show();
  					setTimeout(' window.location.href = "index.php"; ',5000);
  				} else {
  					$("#error").fadeIn(1000, function(){
  						$("#error").html(response).show();
  					});
            window.location.href = "index.php";
  				}
  			}
  		});
  		return false;
  	}

  function advisorRegisterForm() {
  		var data = $("#advisor_register-form").serialize();
      console.log(data);
  		$.ajax({
  			type : 'POST',
  			url  : '../routes/login/registration.php?action=advisor_register',
  			data : data,
  			beforeSend: function(){
  				$("#error").fadeOut();
  				$("#adv_register_button").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; sending ...');
  			},
  			success : function(response){
  				console.log(response);
  				if($.trim(response) === "1"){
  					$("#adv_register_button").html('Signing In ...');
  					$('#success').html('Successfully! User has been Registered.').show();
  					setTimeout(' window.location.href = "index.php"; ',5000);
  				} else {
  					$("#error").fadeIn(1000, function(){
  						$("#error").html(response).show();
  					});
            window.location.href = "index.php";
  				}
  			}
  		});
  		return false;
  	}
//Dom functionality
$(document).ready(function(){
	/* handling form validation */
	$("#student_login_form").validate({
		rules: {
			password: {
				required: true,
			},
			username: {
				required: true,
				email: true
			},
		},
		messages: {
			password:{
			  required: "Please enter your password"
			 },
			username: "Please enter your email address",
		},
		submitHandler: studentSubmitForm
});
//Registration Funcitonality
$("#student_register-form").validate({
  rules: {
    password: {
      required: true,
      minlength: 5,
    },
    re_password: {
      minlength: 5,
      equalTo : "#password",
    },
    f_name: {
      required: true,
    },
    l_name: {
      required: true,
    },
    email: {
      required: true,
      email: true
    },
  },
  messages: {
    password:"Please enter your password",
    email: "Please enter your email address",
    f_name: "Please enter your first name",
    l_name: "Please enter your last name",
  },
  submitHandler: studentRegisterForm
});

/* handling form validation */
$("#advisor_login_form").validate({
  rules: {
    password: {
      required: true,
    },
    username: {
      required: true,
      email: true
    },
  },
  messages: {
    password:{
      required: "Please enter your password"
     },
    username: "Please enter your email address",
  },
  submitHandler: advisorSubmitForm
});

//Registration Funcitonality
$("#advisor_register-form").validate({
  rules: {
    password: {
      required: true,
      minlength: 5,
    },
    re_password: {
      minlength: 5,
      equalTo : "#adv-password",
    },
    f_name: {
      required: true,
    },
    l_name: {
      required: true,
    },
    email: {
      required: true,
      email: true
    },
  },
  messages: {
    password:"Please enter your password",
    email: "Please enter your email address",
    f_name: "Please enter your first name",
    l_name: "Please enter your last name",
  },
  submitHandler: advisorRegisterForm
});

  document.getElementById("advisor-btn").addEventListener("click", function(){
      document.getElementById('advisor_login').style.display='block';
      modal = document.getElementById('advisor_login');
      close = document.getElementById('adv-close');
      close.addEventListener("click", closeModal);
  });
  document.getElementById("student-btn").addEventListener("click", function(){
    document.getElementById('student_login').style.display='block';
    modal = document.getElementById('student_login');
    close = document.getElementById('std-close');
    close.addEventListener("click", closeModal);
  });
  document.getElementById("student-register-btn").addEventListener("click", function(){
    document.getElementById('student_registration').style.display='block';
    modal = document.getElementById('student_registration');
    close = document.getElementById('std-reg-close');
    close.addEventListener("click", closeModal);
  });
  document.getElementById("advisor-register-btn").addEventListener("click", function(){
    document.getElementById('advisor_registration').style.display='block';
    modal = document.getElementById('advisor_registration');
    close = document.getElementById('adv-reg-close');
    close.addEventListener("click", closeModal);
  });


  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
      if (event.target == modal) {
          modal.style.display = "none";
      }
  }
});
