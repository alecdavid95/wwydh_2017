/*2. Reset onclick event will show an alert*/

function checkFilled(){
	/*Step 1 create variables from form elements and change style if empty on submit*/
	var errors = "Missing Form Fields:"; 
	/*will take care of errors, form will not submit if valid is false*/
	var valid = true;
	var vname = document.getElementById('vname');
	var vman = document.getElementById('vman');
	var vcolor = document.getElementById('vcolor');
	var numinput = document.forms["survey1"]["quantity"];
	if (vname.value == ""){
		vname.style.backgroundColor = "red";
		errors = errors.concat(" *Name ");
		valid = false;
	}
	if (vman.value == ""){
		vman.style.backgroundColor = "red";
		errors = errors.concat(" *Manufacturer ");
		valid = false;
	}
	if (vcolor.value == ""){
		vcolor.style.backgroundColor = "red";
		errors = errors.concat(" *Color ");
		valid = false;
	}
	if (numinput.value == "" || numinput.value == null){
		errors = errors.concat(" *Price ");
		valid = false;
	}
	var final = confirm("Are you sure you want to submit?")
	if (final == false){
		valid = false;
	}
	if(!valid){
        alert(errors);
        return valid;
    }
	else{
		vname.style.backgroundColor = "#FFFFFFFF";
		vman.style.backgroundColor = "#FFFFFFFF";
		vcolor.style.backgroundColor = "#FFFFFFFF";
		numinput.style.backgroundColor = "#FFFFFFFF";
		companyid.style.backgroundColor = "#FFFFFFFF";
		alert("Form Submission Complete");
		return valid;
	}
}




	


	






