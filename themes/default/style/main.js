// scripts to call
function _(id, tag)
{
  var e = false;
  if (!tag || tag == "undefined") e = document.getElementById(id); //get element by id
  else e = document.getElementsByTagName(tag)[id]; //get element by tag and tag id
  
  if(!e) return false;
  else return e;
}

var fade_elements = [];
var fade_timeouts = [];
function fade_elem_opacity(e, to, op_diff, time_int, display_none)
{
  if(!op_diff) op_diff = 5;
  if(!time_int) time_int = 50;
  if(!display_none) display_none = 0;
  /*
    var fps = 20; //frames per second
    var duration = 350; //duration of slide in millisec
     
    var f = Math.round(fps * (duration / 1000)); //frames number
    var d = duration / f; //delay time
    //var f = 20; //frames number
    //var d = 10; //delay time
  */
  var elem = fade_elements[e];
  var eo = elem.style.opacity;

  if(eo !== 0 && (eo == '' || isNaN(eo))) eo = 100;
  else eo = eo * 100;

  if(to > eo)
  {
    var no = eo + op_diff;
    if(no > to) no = to;
  }
  else// if(to < eo)
  {
  var no = eo - op_diff;
    if(no < to) no = to;
  }
  //dbg(no, 5);

  set_opacity(elem, no);

  if(no == 0)
  {
    //if(display_none == 1) elem.style.display = 'none';
    if(display_none != '') toggle(display_none);
    else elem.style.visibility = 'hidden';
  }

  if(!fade_timeouts[e])
  {
    fade_timeouts[e] = setInterval('fade_elem_opacity(' + e + ', ' + to + ', ' + op_diff + ', ' + time_int + ', "' + display_none + '");', time_int);
  }
  else if(no == to)
  {
    clearInterval(fade_timeouts[e]);
    fade_timeouts[e] = false;
  }

  return true;
}

function set_opacity(elem, op) {
 if(!op) op = 0;
 elem.style.opacity = (op * 0.01);
 elem.style.MozOpacity = (op * 0.01);
 elem.style.KhtmlOpacity = (op * 0.01);
 elem.style.filter = 'alpha(opacity=' + op + ')';
 return true;
}


/*
function toggle(name, type, n)
{
  if (!type) type = 0;

  if (type == 1) var e = document.getElementsByTagName(name)[n];
  else var e = document.getElementById(name);

  e.style.display = e.style.display == "none" ? "" : "none";
}
*/