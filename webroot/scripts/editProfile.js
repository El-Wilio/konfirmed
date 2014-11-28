$(function() {

$.getScript("scripts/topnav.js");
$.getScript("scripts/left.js");

var the_file;
var displayIt = $('img.profile-picture').attr('alt');
$('#uploader').on('change', function(event) {
  var files = event.target.files;
  var ext = this.value.match(/\.(.+)$/)[1];
  var fd = new FormData();
  $.each(files, function(key, value)
  {
    fd.append(key, value);
  });
  fd.append('type', 'temp_profile');
  var ext = files[0]['name'].match(/\.(.+)$/)[1];
  $.ajax({ 
    url: 'ajaxRequest.php',
    type: 'post',
    processData: false,
    contentType: false,
    cache: false,
    data: fd,
    success: function(data) {
      console.log(data);
      the_file = 'images/profile/tmp/'+data;
      $('.profile-picture').attr('src', 'images/profile/tmp/'+ data+"?"+Math.floor((1000*Math.random())));
    },
    error: function() {console.log('nope')}
    })
});

$('.send-information').on('click', function() {

  $.ajax({
    url: 'ajaxRequest.php',
    type: 'POST',
    data: {
      type: 'setProfile',
      fname: $('input[name="fname"]').val().trim(),
      lname: $('input[name="lname"]').val().trim(),
      displayPic: displayIt,
      loc: $('input[name="location"]').val().trim(),
      gender: $('input[name="gender"]:checked').val(),
      year:   $('select[name="year"]').val(),
      month:  $('select[name="month"]').val(),
      day:    $('select[name="day"]').val(),
      bio:    '',
      occupation: $('input[name="occupation"]').val().trim()
    },
    success: function(data) {
      location.replace("http://www.konfirmed.com/temp/webroot/profile.php");
    }
 });
  
});

     $('div.box').on('click', function() {
      var category = $(this).attr('class').split(' ')[0];
      var selectedID;
      switch (category) {
        case "audio":
            selectedID = '4';
            break;
        case "image":
            selectedID = '1';
            break;
        case "video":
            selectedID = '2';
            break;
        case "text":
            selectedID = '3';
            break;          
      }            

       $.ajax({
          url: 'ajaxRequest.php',
          type: 'POST',
          data: {
           mediumID: selectedID,
           type: 'iconPopulator'
           },
           success: function(data) {
            $('.selected-category').html(data);
           }
      });

      $('.selected-category').addClass(category);
      $('.selected-category').animate({left: '0'}, 500);      
      });
    $('.selected-category').on('click', '.close-selected-category', function() {
      var category = $('.selected-category').attr('class').split(' ')[1];
      $('.selected-category').animate({left: '-100%'}, 500).queue(function() {
        $('.selected-category').removeClass(category);
        $(this).dequeue(); });
      });
          
});