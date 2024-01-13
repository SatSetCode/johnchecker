
function copyResultfunc(option) {
var elm = document.getElementById(option);
  if(document.body.createTextRange) {
    var range = document.body.createTextRange();
    range.moveToElementText(elm);
    range.select();
    document.execCommand("Copy");
    alert("Copied");
  }
  else if(window.getSelection) {
  var selection = window.getSelection();
    var range = document.createRange();
    range.selectNodeContents(elm);
    selection.removeAllRanges();
    selection.addRange(range);
    document.execCommand("Copy");
    alert("Copied");
  }
}


$('#list').focusout(function () {
var avalue = $(this).val();
var newVal = avalue.replace(/^\s*[\r\n]/gm, '');
$(this).val(newVal);
});


$("#send").click(function(){

var emails = $("#list").val();
var lines = emails.split('\n');



if($.trim(emails) === ""){
alert("add your list");
return false;
}

if(lines.length == 1){
alert("minimum number of emails is 2");
return false;
}


$("#count_div").css("visibility" , "visible");
$("#finished").hide();
$("#ygdicek").show();
$("#list").attr("readonly" , "true");

var sendToServer = function(lines, index){
realmail = lines[index];
if (realmail.trim().length != 0){
$.ajax({
type: 'GET',
url: 'request.php',
dataType: 'json',
data: {'email': realmail, 'created_by': 'MC-Script'},
success: function(msg){
if (index < lines.length) {
setTimeout( function () {
 sendToServer(lines, index+1); 

}, 1000 );

$("#counter").html((testlolo= index + 1) + "/" + lines.length);
if(lines.length == testlolo){
$("#ygdicek").hide();
$("#finished").show();
$("#list").removeAttr("readonly");
}

if(msg.status == "registered"){
$("#copyresultlive").append("<span class=\"live_f\">" + realmail + "</span><br/>");
$("#live_counter").html(livecounter_var = index+1);


}else if(msg.status == "unregistered"){
$("#copyresultdied").append("<span class=\"died_f\">" + realmail + "</span><br/>");
$("#died_counter").html(died_counter = index+1);

}else{
$("#copyresultunknown").append("<span class=\"unknown_f\">" + realmail + "</span><br>");
$("#unknown_counter").html(unknown_counter = index+1);


}

}

}

});

}else { sendToServer(lines, index+1);   
  }

};
sendToServer(lines, 0);




});
