function call_form_field(k){
	w=form_frame.document.getElementById("uploadFile"+k);
	w.click();
	w.focus();
}
function blind_submit(v, p, pz){
	form_frame.form.amount.value=v;
	form_frame.form.path.value=p;
	form_frame.form.pass.value=pz;
	form_frame.form.submit();
}
function set_variable(value) {
	parent.movie.SetVariable("filename",value);
	parent.movie.TGotoFrame("_level0/javas",1);
}
function set_success(sucstring) {
	parent.movie.SetVariable("sucstring",sucstring);
	parent.movie.TGotoFrame("_level0/javas",2);
}
function createitem(k){
   var oNewNodez = form_frame.document.createElement("<input type='file' name='uploadFile"+k+"' id='uploadFile"+k+"' onFocus='set_variable(this.value)'>");
   form_frame.form.appendChild(oNewNodez);
   call_form_field(k);
}
function removeitem(j){
   for(i=0;i<arguments.length;i++){
   oRemoved = form_frame.form["uploadFile"+arguments[i]].removeNode(true);
   }
}
function setflash(id, varname, value){
   if (navigator.appName.indexOf ("Microsoft") !=-1) {
	var flashobject=eval("window."+id);
   } else {
        var flashobject=eval("window.document."+id);
   }
   if(flashobject){
	flashobject.SetVariable(varname, value);
   }
}