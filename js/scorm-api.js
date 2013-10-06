function SCORM_API_1_2 (id_pokusaja) {
	var SCORM_FALSE = "false";
	var SCORM_TRUE = "true";
	var ERROR_CODE = "";
	var SCO_STACK = [];
	var SCO_INITIALIZED = false;
	var SCO_TERMINATED = false;
	var READ_ONLY = ["cmi.core.entry", "cmi.core._children", "cmi.core.student_id", "cmi.core.student_name", "cmi.core.total_time"];
	
	var convert_to_params = function () {
		var param = "";
		for (element in SCO_STACK) {
			param += "data["+encodeURIComponent(element)+"]="+encodeURIComponent(SCO_STACK[element]) + "&";
		}
		return param.slice(0, -1)
	};
	
	$.ajax ({
		url: CI_ROOT + 'index.php/rte/inicializiraj_sesiju/' + id_pokusaja, 
		data:{'sco_id' : encodeURIComponent(SCOS[INDEX].identifier)}, 
		type:"GET",
		async:false,
		dataType:"json",
		error:function(xhr) {
			alert(xhr.status);
		},
		success:function (data) {
			for (i in data) {
				SCO_STACK[data[i].element] = data[i].value;
				$("#bodovi").html(SCO_STACK['cmi.core.score.raw']);
				$("#status").html(SCO_STACK['cmi.core.lesson_status']);
			}
		}
	});
	
    this.LMSInitialize = function (value) {
		ERROR_CODE = "0";
		if (value == "") {
			if (SCO_INITIALIZED || SCO_TERMINATED) { ERROR_CODE = "101"; return SCORM_FALSE; }
			SCO_INITIALIZED = true;
			return SCORM_TRUE;	
		} else {
			ERROR_CODE = "201";	
			return SCORM_FALSE;
		}
	};
    this.LMSFinish = function (value) {
		
		if (value == "") {
			if (!(SCO_INITIALIZED) || SCO_TERMINATED) { 
				ERROR_CODE = "301";
				return SCORM_FALSE; 
			}
			this.LMSCommit("");
			
			$.ajax ({
				url: CI_ROOT + 'index.php/rte/zavrsi_sesiju/' + id_pokusaja, 
				data:{'sco_id' : encodeURIComponent(SCOS[INDEX].identifier), 'masteryscore' : encodeURIComponent(SCOS[INDEX].masteryscore)}, 
				type:"GET",
				async:false,
				dataType:"json",
				error:function(xhr) {
					alert(xhr.status);
				},
				success:function (data) {
					SCO_TERMINATED = true;
				}
			});
			ERROR_CODE = "0";
			return (SCO_TERMINATED == true) ? SCORM_TRUE : SCORM_FALSE;
		}
	};
    this.LMSGetValue = function (name) {

		if (!(SCO_INITIALIZED) || SCO_TERMINATED) { 
			ERROR_CODE = "301";
			return SCORM_FALSE; 
		}
		
		if (typeof(SCO_STACK[name]) != "undefined") { 
			return SCO_STACK[name];
		} else {
			ERROR_CODE = "401";
			return "";
		}
	};
	
    this.LMSSetValue = function (name, value) {
		if (!(SCO_INITIALIZED ) || SCO_TERMINATED) {
			ERROR_CODE = "301";
			return SCORM_FALSE;
		}		
		for (i in READ_ONLY) {
			if (READ_ONLY[i] == name) {
					ERROR_CODE = "403";
					return SCORM_FALSE;		
			}
		}
		for (cmi_name in SCO_STACK) {
			if (cmi_name == name){
				SCO_STACK[name] = value;
			} else {
				ERROR_CODE = "401";
			}
		}
		ERROR_CODE = "0";
		return SCORM_TRUE;
	};
	
    this.LMSCommit = function (param) {
		if (SCO_INITIALIZED == false || SCO_TERMINATED == true) { return SCORM_FALSE; }
		var data = convert_to_params();
		
		var result;
		
		$.ajax ({
				url:CI_ROOT + 'index.php/rte/spasi_sesiju/' + id_pokusaja +'/?'+data+'&sco_id='+encodeURIComponent(SCOS[INDEX].identifier)+"&sco_title="+encodeURIComponent(SCOS[INDEX].title), 
				data:{}, 
				type:"GET",
				async:false,
				dataType:"json",
				error:function (xhr){
                    alert(xhr.status);
                },
				success:function (data) {
					result = data.result;
					$("#bodovi").html(SCO_STACK['cmi.core.score.raw']);
					$("#status").html(SCO_STACK['cmi.core.lesson_status']);
				}
		});
		return (result == 'true') ? SCORM_TRUE : SCORM_FALSE;
	};
	
    this.LMSGetLastError = function () {
		return ERROR_CODE;	
	};
    this.LMSGetErrorString = function (param) {
		 if (param != "") {
            var errorString = new Array();
            errorString["0"] = "No error";
            errorString["101"] = "General exception";
            errorString["201"] = "Invalid argument error";
            errorString["202"] = "Element cannot have children";
            errorString["203"] = "Element not an array - cannot have count";
            errorString["301"] = "Not initialized";
            errorString["401"] = "Not implemented error";
            errorString["402"] = "Invalid set value, element is a keyword";
            errorString["403"] = "Element is read only";
            errorString["404"] = "Element is write only";
            errorString["405"] = "Incorrect data type";
            return errorString[param];
        } else {
                      return "";
        }	
	};
    this.LMSGetDiagnostic = function (param) {
		if (param == "") return ERROR_CODE;
		return param;	
	};
    this.LMSversion = '1.0';
}
/**
*SCORM 1.2 API
*
*@TODO: Implement full datamodel...
*/